<?php

namespace Depicter\Controllers\Ajax;

use Averta\Core\Controllers\RestMethodsInterface;
use Averta\Core\Utility\Arr;
use Averta\Core\Utility\Data;
use Averta\WordPress\Utility\JSON;
use Depicter;
use Depicter\Editor\EditorLocalization;
use Depicter\GuzzleHttp\Exception\GuzzleException;
use Depicter\Utility\Http;
use Depicter\Utility\Sanitize;
use Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UploadedFileInterface;
use WPEmerge\Requests\RequestInterface;

class EditorAjaxController implements RestMethodsInterface
{
    /**
     * Retrieves Lists of all entries. (GET)
     *
     * @param RequestInterface $request
     * @param string $view
     *
     * @return string
     */
    public function index(RequestInterface $request, $view)
    {
        return '';
    }

    /**
     * Adds a new entry. (POST)
     *
     * @param RequestInterface $request
     * @param string $view
     *
     * @throws Exception
     * @throws GuzzleException
     * @return ResponseInterface
     */
    public function store(RequestInterface $request, $view)
    {
        // fields to update
        $properties = [];

        // Get editor data
        $editor = $request->body('editor');
        if (!empty($editor) && JSON::isJson($editor)) {

            if (defined('DISALLOW_UNFILTERED_HTML') && DISALLOW_UNFILTERED_HTML) {
                $pattern = '/<script[^>]*?>.*?<\/script>/si';
                // Replace script tags with an empty string
                $editor = preg_replace($pattern, '', $editor);

	            $pattern = '/<iframe[^>]*?>/si';
	            // Replace iframe tags with an empty string
	            $editor = preg_replace($pattern, '', $editor);
            }

            $properties['editor'] = $editor;
        }

        // Get document ID
        $id = Sanitize::int($request->body('ID'));

        if (empty($id)) {
            return Depicter::json(['errors' => ['Document "ID" is required.']])->withStatus(400);
        }

        // Get document name
        $name = Sanitize::textfield($request->body('name'));

        if (!empty($name)) {
            $properties['name'] = $name;
        }

        // Get document slug
        $slug = Sanitize::slug($request->body('slug'));

        if (!empty($slug)) {
            $properties['slug'] = $slug;
        }

        // Get document status
        $status               = Sanitize::textfield($request->body('status')) ?? 'draft';
        $properties['status'] = $status === 'published' ? 'publish' : $status;

        try {
            if ($properties['status'] == 'publish') {

                if (\Depicter::auth()->isPaid() && !\Depicter::auth()->verifyActivation()) {
                    throw new Exception(esc_html__('License is not valid.', 'depicter'));
                }

                if (function_exists('get_filesystem_method') && get_filesystem_method() != 'direct') {
                    throw new Exception(esc_html__('Media files cannot be published due to lack of proper file permissions for uploads directory.', 'depicter'));
                }

                $editorRawData = $properties['editor'] ?? Depicter::document()->getEditorRawData($id);

                // Download media if document published
                \Depicter::media()->importDocumentAssets($editorRawData);
            }

            if (!empty($properties['editor'])) {
                $documentHasForm = strpos($properties['editor'], '"type":"form"') ? 1 : 0;
                Depicter::document()->meta()->update($id, 'hasForm', $documentHasForm);

                $documentHasShortcode = strpos($properties['editor'], '"type":"wpShortcode"') ? 1 : 0;
                Depicter::document()->meta()->update($id, 'hasShortcode', $documentHasShortcode);
            }

            $result = Depicter::documentRepository()->saveEditorData($id, $properties);

            if (false === $result) {
                return Depicter::json(['errors' => ['Document does not exist.', $result]])->withStatus(404);
            }

            do_action('depicter/editor/after/store', $id, $properties, $result);

            $this->setDocumentPoster($request, $id);

            $result = ['hits' => [
                'status'      => $status,
                'modifiedAt'  => $result['modifiedAt'],
                'publishedAt' => $result['publishedAt']
            ]
            ];
            $revisions = Depicter::documentRepository()->getRevisionsID($id);
            if (!empty($revisions)) {
                foreach ($revisions as $revisionID) {
                    $revision                      = Depicter::documentRepository()->findById($revisionID)->getApiProperties();
                    $author                        = get_user_by('id', $revision['author']);
                    $result['hits']['revisions'][] = [
                        'id'     => $revisionID,
                        'author' => [
                            'id'     => $author->ID,
                            'name'   => $author->display_name,
                            'avatar' => get_avatar_url($author->ID),
                        ],
                        'publishedAt' => $revision['publishedAt'],
                    ];
                }
            }

            return Depicter::json($result)->withStatus(200);
        } catch (Exception $exception) {
            $error = Http::getErrorExceptionResponse($exception);

            return \Depicter::json([
                'errors' => $error
            ])->withStatus($error['statusCode']);
        }

    }

    /**
     * Displays an entry. (GET)
     *
     * @param RequestInterface $request
     * @param string $view
     *
     * @throws Exception
     * @return ResponseInterface
     */
    public function show(RequestInterface $request, $view)
    {
        if (!$documentId = Sanitize::int($request->query('ID'))) {
            return Depicter::json(['errors' => ['Document ID is required.']])->withStatus(400);
        }

        if (!$document = Depicter::documentRepository()->findById($documentId)) {
            return Depicter::json(['errors' => ['Document ID not found.', $documentId]])
                ->withHeader('X-Document-ID', $documentId)
                ->withStatus(404);
        }

        $hits = Arr::camelizeKeys($document->getApiProperties(), '_');
        $author = get_user_by('id', $hits['author']);
        $hits['author'] = [
            'id'     => $author->ID,
            'name'   => $author->display_name,
            'avatar' => get_avatar_url($author->ID),
        ];

        $revisions = Depicter::documentRepository()->getRevisionsID($documentId);
        if (!empty($revisions)) {
            foreach ($revisions as $revisionID) {
                $revision            = Depicter::documentRepository()->findById($revisionID)->getApiProperties();
                $author              = get_user_by('id', $revision['author']);
                $hits['revisions'][] = [
                    'id'     => $revisionID,
                    'author' => [
                        'id'     => $author->ID,
                        'name'   => $author->display_name,
                        'avatar' => get_avatar_url($author->ID),
                    ],
                    'publishedAt' => $revision['publishedAt'],
                ];
            }
        }

        $hits['content'] = Depicter::document()->migrations()->apply($hits['content']);

        return Depicter::json(['hits' => $hits])->withHeader('X-Document-ID', $documentId)->withStatus(200);
    }

    /**
     * Retrieves list of document revisions with details
     *
     * @param RequestInterface $request
     * @param string $view
     *
     * @throws Exception
     * @return ResponseInterface
     */
    public function getHistory(RequestInterface $request, $view)
    {
        if (!$documentId = Sanitize::int($request->query('ID'))) {
            return Depicter::json(['errors' => ['Document ID is required.']])->withStatus(400);
        }

        if (!$document = Depicter::documentRepository()->findById($documentId)) {
            return Depicter::json(['errors' => ['Document ID not found.', $documentId]])
                ->withHeader('X-Document-ID', $documentId)
                ->withStatus(404);
        }

        $hits      = [];
        $revisions = Depicter::documentRepository()->getRevisionsID($documentId);
        if (!empty($revisions)) {
            foreach ($revisions as $revisionID) {
                $revision = Depicter::documentRepository()->findById($revisionID)->getApiProperties();
                $author   = get_user_by('id', $revision['author']);
                $hits[]   = [
                    'id'     => $revisionID,
                    'author' => [
                        'id'     => $author->ID,
                        'name'   => $author->display_name,
                        'avatar' => get_avatar_url($author->ID),
                    ],
                    'publishedAt' => $revision['publishedAt'],
                ];
            }
        }

        return Depicter::json(['hits' => $hits])->withHeader('X-Document-ID', $documentId)->withStatus(200);
    }

    /**
     * Renames a document name.
     *
     * @param RequestInterface $request
     * @param string $view
     *
     * @throws Exception
     * @return ResponseInterface
     */
    public function checkSlug(RequestInterface $request, $view)
    {
        // Get document ID
        $slug = Sanitize::textfield($request->body('slug'));

        if (is_null($slug)) {
            return Depicter::json(['errors' => ['Document slug is required.']])->withStatus(400);
        }

        $result = Depicter::documentRepository()->checkSlug($slug);

        if ($result) {
            return Depicter::json(['errors' => ['Taken!']])
                ->withHeader('X-Taken-Slug', $slug)
                ->withHeader('X-Available-Slug', Depicter::documentRepository()->makeSlug())
                ->withStatus(423);
        }

        return Depicter::json(['hits' => $slug])->withStatus(200);
    }

    /**
     * Retrieves editor and dashboard localized texts
     *
     * @param RequestInterface $request
     * @param $view
     *
     * @return ResponseInterface
     */
    public function getLocalization(RequestInterface $request, $view)
    {
        return Depicter::json(
            EditorLocalization::getTranslateList()
        )->withHeader('Access-Control-Allow-Origin', '*')->withStatus(200);
    }

    /**
     * Renders a document markup.
     *
     * @param RequestInterface $request
     * @param string $view
     *
     * @throws Exception
     * @return ResponseInterface
     */
    public function render(RequestInterface $request, $view)
    {
        if (!$documentId = Sanitize::int($request->query('ID'))) {
            return Depicter::json(['errors' => ['Document ID is required.']])->withStatus(400);
        }

        $args = [];
        if (Data::isBool($request->query('addImportant'))) {
            $args['addImportant'] = Sanitize::textfield($request->query('addImportant'));
        }

        return Depicter::output(Depicter::front()->render()->document($documentId, $args));
    }

    /**
     * Outputs markup to preview a document
     *
     * @param RequestInterface $request
     * @param $view
     *
     * @return ResponseInterface
     */
    public function preview(RequestInterface $request, $view)
    {
        $gutenberg  = $request->query('gutenberg', '');
        $documentId = Sanitize::int($request->query('ID'));

        if (!$documentId && empty($gutenberg)) {
            return Depicter::json(['errors' => ['Document ID is required.']])->withStatus(400);
        }

        $status = Sanitize::textfield($request->query('status'));

        $previewArgs = [
            'status'    => !empty($status) ? $status : 'auto',
            'start'     => Sanitize::int($request->query('startSection')),
            'gutenberg' => !empty($gutenberg)
        ];

        return Depicter::output(Depicter::front()->preview()->document($documentId, $previewArgs));
    }

    /**
     * Retrieves object of document editor data
     *
     * @param RequestInterface $request
     * @param string $view
     *
     * @return ResponseInterface
     */
    public function getEditorData(RequestInterface $request, $view)
    {
        if (!$documentId = Sanitize::int($request->query('ID'))) {
            return Depicter::json(['errors' => ['Document ID is required.']])->withStatus(400);
        }

        $output = '';

        try {
            $output = Depicter::document()->getEditorData($documentId);
            $output = Depicter::document()->migrations()->apply($output);

        } catch (Exception $exception) {
            return Depicter::json([
                'errors' => [$exception->getMessage()]
            ])->withHeader('X-Document-ID', $documentId)->withStatus(404);
        }

        return Depicter::json($output);
    }

    public function getRevisions(RequestInterface $request, $view)
    {

        $id = Sanitize::int($request->query('ID', ''));
        if (empty($id)) {
            return Depicter::json([
                'errors' => [__('ID required', 'depicter')]
            ])->withStatus(400);
        }

        try {
            $hits = Depicter::documentRepository()->getRevisionsID($id);

            return Depicter::json(['hits' => $hits])->withStatus(200);
        } catch (\Exception $e) {
            return Depicter::json(['errors' => [$e->getMessage()]])->withStatus(400);
        }
    }

    /**
     * Reverts a document to previous snapshots
     *
     * @param RequestInterface $request
     * @param string $view
     *
     * @return ResponseInterface
     */
    public function revert(RequestInterface $request, $view)
    {
        if (!$documentId = Sanitize::int($request->body('ID'))) {
            return Depicter::json(['errors' => ['Document ID is required.']])->withStatus(400);
        }
        $to = Sanitize::textfield($request->body('to'));

        try {
            $output = Depicter::document()->repository()->revert($documentId, $to);

            return Depicter::json($output);

        } catch (Exception $exception) {
            return Depicter::json(['errors' => [$exception->getMessage()]])
                ->withHeader('X-Document-ID', $documentId)
                ->withStatus(404);
        }
    }

    /**
     * Updates an entry. (PUT/PATCH)
     *
     * @param RequestInterface $request
     * @param string $view
     *
     * @return ResponseInterface
     */
    public function update(RequestInterface $request, $view)
    {
        return Depicter::json(['hits' => []])->withStatus(200);
    }

    /**
     * Upload cover photo of slider
     *
     * @param RequestInterface $request
     * @param int $id
     *
     * @return bool
     */
    public function setDocumentPoster(RequestInterface $request, int $id)
    {
        $uploadedFiles = $request->getUploadedFiles();

        if (empty($uploadedFiles['previewImage']) || empty($id)) {
            return false;
        }
        $previewImages = $uploadedFiles['previewImage'];

        if (!is_array($previewImages)) {
            /* @var $previewImages UploadedFileInterface */
            if ($previewImages->getError() || !$previewImages->getSize()) {
                return false;
            }

            return $this->uploadPreviewImage($previewImages, $id);

        }
        /* @var $previewImages UploadedFileInterface[] */
        foreach ($previewImages as $key => $image) {
            if (empty($image) || $image->getError() || !$image->getSize()) {
                continue;
            }
            if ($key === 0) {
                $this->uploadPreviewImage($image, $id);
                $this->uploadPreviewImage($image, $id . '-1');
            } else {
                $fileName = $id . '-' . ($key + 1);
                $this->uploadPreviewImage($image, $fileName);
            }
        }

        return true;

    }

    /**
     * Upload preview image to depicter upload folder
     *
     * @param UploadedFileInterface $imageFile
     * @param string $fileName
     *
     * @return bool
     */
    public function uploadPreviewImage(UploadedFileInterface $imageFile, string $fileName): bool
    {
        $mediaType = $imageFile->getClientMediaType();

        if (false === strpos($mediaType, 'image/')) {
            // Not an image file
            return false;
        }

        return Depicter::storage()->filesystem()->write(Depicter::storage()->getPluginUploadsDirectory() . '/preview-images/' . $fileName . '.png', (string) $imageFile->getStream());
    }

    /**
     * Upload multiple document posters
     *
     * @param RequestInterface $request
     * @param $view
     *
     * @return ResponseInterface
     */
    public function uploadDocumentPosters(RequestInterface  $request, $view)
    {

        $documentId = Sanitize::textfield($request->body('ID', ''));

        try {
            $coverPhoto = $request->getUploadedFiles();

            if (empty($documentId)) {
                throw new \Exception(__('Document ID is required.', 'depicter'), 400);
            }
            if (empty($coverPhoto)) {
                throw new \Exception(__('Cover image not found.', 'depicter'), 400);
            }
            if (!$this->setDocumentPoster($request, $documentId)) {
                throw new \Exception(__('Error occurred! Cannot upload cover photos.', 'depicter'), 400);
            }

        } catch (\Exception $exception) {
            return Depicter::json([
                'errors' => [$exception->getMessage()]
            ])->withStatus($exception->getCode());
        }

        return Depicter::json(['success' => true, 'message' => 'Cover images uploaded successfully.'])->withStatus(200);
    }

    /**
     * Get Document Status
     *
     * @param RequestInterface $request
     * @param string $view
     *
     * @return ResponseInterface
     */
    public function getDocumentStatus(RequestInterface $request, $view)
    {
        $documentId = absint($request->query('ID', 0));
        try {
            return Depicter::json([
                'status' => Depicter::documentRepository()->getStatus($documentId)
            ])->withStatus(200);
        } catch (\Exception $exception) {
            return Depicter::json([
                'errors' => [$exception->getMessage()]
            ])->withStatus(400);
        }
    }

    public function destroy(RequestInterface $request, $view)
    {
        // TODO: Implement destroy() method.
    }

    /**
     * Render Just Shortcodes without any header or footer
     *
     * @param RequestInterface $request
     * @param string $view
     *
     * @return ResponseInterface
     */
    public function renderShortcode(RequestInterface $request, $view): ResponseInterface
    {
        $shortcode = Sanitize::textfield($request->query('shortcode', ''));

        $output = Depicter::view('render-shortcode.php')->with('view_args', [
            'shortcode' => $shortcode
        ])->toString();

        return Depicter::output($output);
    }
}
