<?php

namespace Depicter\Services;

use Averta\Core\Utility\Arr;
use Averta\Core\Utility\Data;
use Averta\Core\Utility\Validator;
use Averta\WordPress\Utility\Sanitize;
use WPEmerge\Requests\RequestInterface;

class LeadService
{
    /**
     * List of reserved field names
     */
    const RESERVED_FILED_NAMES = ['_sourceId', '_contentId', '_contentName', '_csrfToken', 'action', '_g_recaptcha_token', '_g_recaptcha_error'];

    public function add($sourceId, $contentId, RequestInterface $request)
    {
        try {
            $contentName = Sanitize::textfield($request->body('_contentName', ''));

            $leadId = \Depicter::leadRepository()->create($sourceId, $contentId, $contentName);

            if (!$leadId) {
                return [
                    'success' => false,
                    'errors'  => [__('Error while creating the lead', 'depicter')]
                ];
            }

            $formFields = $request->getParsedBody();

            foreach ($formFields as $fieldName => $fieldValue) {
                if (in_array($fieldName, self::RESERVED_FILED_NAMES)) {
                    continue;
                }

                $leadFields = \Depicter::leadFieldRepository()->create(
                    $leadId,
                    $fieldName,
                    $fieldValue,
                    $this->getFieldType($fieldName, $fieldValue)
                );

                if (!$leadFields) {
                    return [
                        'success' => false,
                        'errors'  => [__('Error while saving the fields', 'depicter')]
                    ];
                }
            }

            return ['success' => true];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'errors'  => [$e->getMessage()]
            ];
        }
    }

    public function get($args = [])
    {
        $default = [
            's'         => '',
            'sources'   => '',
            'dateStart' => '',
            'dateEnd'   => '',
            'order'     => 'DESC',
            'orderBy'   => 'id',
            'page'      => 1,
            'perPage'   => 20
        ];

        $args         = Arr::merge($args, $default);
        $hits         = [];
        $commonFields = [];

        try {
            $results = \Depicter::leadRepository()->getResults($args);
            if (!empty($results['leads'])) {
                foreach ($results['leads'] as $lead) {
                    $document   = \Depicter::documentRepository()->select(['id', 'name', 'type'])->findById($lead['source_id']);
                    $leadFields = \Depicter::leadFieldRepository()->select(['name', 'value', 'type'])->where('lead_id', $lead['id'])->get();
                    $fields     = [];
                    $identifier = '';
                    if (!empty($leadFields)) {
                        $leadFields = $leadFields->toArray();
                        foreach ($leadFields as $leadField) {
                            $fields[] = [
                                'name'  => $leadField['name'],
                                'value' => $leadField['value'],
                                'type'  => $leadField['type']
                            ];

                            if ($leadField['name'] === 'email') {
                                $identifier = $leadField['value'];
                                if (!in_array('email', $commonFields)) {
                                    $commonFields[] = 'email';
                                }
                            }

                            if ($leadField['name'] === 'name' && !in_array('name', $commonFields)) {
                                $commonFields[] = 'name';
                            }

                            if ($leadField['name'] === 'phone' && !in_array('phone', $commonFields)) {
                                $commonFields[] = 'phone';
                            }
                        }
                    }
                    $hits[] = [
                        'id'         => $lead['id'],
                        'createdAt'  => $lead['created_at'],
                        'identifier' => $identifier,
                        'source'     => [
                            'id'   => $document->id,
                            'name' => $document->name,
                            'type' => $document->type
                        ],
                        'content' => [
                            'id'   => $lead['content_id'],
                            'name' => $lead['content_name']
                        ],
                        'fields' => $fields
                    ];
                }
            }

            return [
                'hasMore'      => isset($results['numberOfPages']) && $results['numberOfPages'] > $results['page'],
                'page'         => $args['page'],
                'perpage'      => $args['perPage'],
                'totalPages'   => $results['numberOfPages'] ?? 1,
                'totalHits'    => $results['numberOfLeads'],
                'hits'         => Arr::camelizeKeys($hits, '_', [], true),
                'commonFields' => $commonFields
            ];
        } catch (\Exception $e) {
            return [
                'errors' => [$e->getMessage()]
            ];
        }
    }

    /**
     * Tries to retrieve the type of field based on name or value
     *
     * @param string $fieldName Field name
     * @param string $fieldValue Field value
     *
     * @return string
     */
    protected function getFieldType($fieldName, $fieldValue = '')
    {
        $default = 'text';

        $knowFieldNames = [
            'name'       => 'text',
            'first-name' => 'text',
            'last-name'  => 'text',
            'email'      => 'email',
            'phone'      => 'tel',
            'address'    => 'text',
            'website'    => 'url',
            'checkbox'   => 'bool',
        ];

        if (!empty($knowFieldNames[ $fieldName ])) {
            return $knowFieldNames[ $fieldName ];
        }
        if (empty($fieldValue)) {
            return $default;
        }
        if (Validator::isEmail($fieldValue)) {
            return 'email';
        }
        if (Validator::isUrl($fieldValue)) {
            return 'url';
        }
        if (Validator::isTel($fieldValue)) {
            return 'tel';
        }
        if (Data::isTrue($fieldValue)) {
            return 'bool';
        }

        return $default;
    }
}
