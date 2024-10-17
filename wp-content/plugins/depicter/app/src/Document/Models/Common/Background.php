<?php
namespace Depicter\Document\Models\Common;


use Averta\Core\Utility\Arr;
use Averta\Core\Utility\Embed;
use Averta\WordPress\Utility\JSON;
use Depicter\Document\CSS\Breakpoints;
use Depicter\Document\CSS\Selector;
use Depicter\Document\Models\Traits\HasDataSheetTrait;
use Depicter\Document\Models\Traits\MediaSourceTrait;
use Depicter\Html\Html;
use Depicter\Services\MediaBridge;

class Background
{
	use HasDataSheetTrait;

	use MediaSourceTrait;

	/**
	 * @var Color
	 */
	public $color;

	/**
	 * @var string
	 */
	public $fitMode;

	/**
	 * @var object
	 */
	public $video;

	/**
	 * @var object
	 */
	public $image;

	/**
	 * @var Color
	 */
	public $overlay;

	/**
	 * @var Styles\Filter
	 */
	public $filter;

	/**
	 * @var array
	 */
	public $kenBurnsData = [];

	/**
	 * @var string
	 */
	protected $markup;

	/**
	 * Whether in preview mode or not
	 *
	 * @var bool
	 */
	protected $isPreviewMode;

	/**
	 * @var mixed|string[]
	 */
	private $breakpoints;

	/**
	 * @var mixed
	 */
	private $renderArgs;

	/**
	 * Check if section has background or not
	 *
	 * @return boolean
	 */
	public function hasBackground() {
		return !empty( $this->image->src->default ) || !empty( $this->video->src );
	}

	/**
	 * Check if section has background image or not
	 *
	 * @return boolean
	 */
	public function hasBackgroundImage() {
		return !empty( $this->image->src->default ) || !empty( $this->image->src->tablet ) || !empty( $this->image->src->mobile );
	}

	/**
	 * Calculates render options
	 *
	 * @return $this
	 */
	protected function setRenderOptions(){
		$this->breakpoints = Breakpoints::all();
		$this->breakpoints['default'] = 1025;

		$defaultAssetID = $this->image->src->default;
		$tabletAssetID = $this->image->src->tablet ?? $this->image->src->default;
		$mobileAssetID = $this->image->src->mobile ?? ( $this->image->src->tablet ?? $this->image->src->default );

		$this->renderArgs['default']['assetId'] = $this->hasDataSheet() ? $this->maybeReplaceDataSheetTags( $defaultAssetID ) : $defaultAssetID;
		$this->renderArgs['tablet']['assetId'] = $this->hasDataSheet() ? $this->maybeReplaceDataSheetTags( $tabletAssetID ) : $tabletAssetID;
		$this->renderArgs['mobile']['assetId'] = $this->hasDataSheet() ? $this->maybeReplaceDataSheetTags( $mobileAssetID ) : $mobileAssetID;

		$this->renderArgs['tablet']['assetId'] = $this->renderArgs['tablet']['assetId'] ?? $this->renderArgs['default']['assetId'];
		$this->renderArgs['mobile']['assetId'] = $this->renderArgs['mobile']['assetId'] ?? $this->renderArgs['tablet']['assetId'];


		$this->renderArgs['isPreview'] = \Depicter::front()->preview()->isPreview();
		$this->renderArgs['default']['attachmentId'] = \Depicter::media()->getAttachmentId( $this->renderArgs['default']['assetId'] );
		$this->renderArgs['tablet']['attachmentId'] = !empty( $this->renderArgs['tablet']['assetId'] ) ? \Depicter::media()->getAttachmentId( $this->renderArgs['tablet']['assetId'] ) : $this->renderArgs['default']['attachmentId'];
		$this->renderArgs['mobile']['attachmentId'] = !empty( $this->renderArgs['mobile']['assetId'] ) ? \Depicter::media()->getAttachmentId( $this->renderArgs['mobile']['assetId'] ) : $this->renderArgs['tablet']['attachmentId'];

		$this->renderArgs['isAttachment'] = is_numeric( $this->renderArgs['default']['attachmentId'] );
		$this->renderArgs['altText'] = \Depicter::media()->getAltText( $this->renderArgs['default']['attachmentId'] );

		$this->isPreviewMode = $this->renderArgs['isPreview'] || ! $this->renderArgs['isAttachment'];

		return $this;
	}

	/**
	 * Render background markup
	 *
	 * @return string
	 * @throws \Exception
	 */
	public function render() {
		$this->markup = '';

		if ( $this->hasBackgroundImage() ) {

			$this->setRenderOptions();

			$this->renderPictureWrapper();

			$this->renderSourceTags();
			$this->renderImageTag();

			return $this->markup;
		}

		if ( !empty( $this->video->src ) ) {
			$sourceID = \Depicter::media()->getAttachmentId( $this->video->src );
			$url = $sourceID ? \Depicter::media()->getSourceUrl( $sourceID ) : $this->video->src;
			$this->markup .= $this->renderVideo( $url );
		}

		return $this->markup;
	}

	/**
	 * Render images
	 *
	 *
	 * @return Html
	 */
	protected function renderImageTag() {
		$imageID = \Depicter::media()->getAttachmentId( $this->renderArgs['default']['assetId'] );

		$imageUrl = \Depicter::media()->getSourceUrl( $this->renderArgs['default']['assetId'] );

		$args = [
			'src' =>  \Depicter::media()::IMAGE_PLACEHOLDER_SRC,
			'alt' => is_numeric( $imageID ) ? \Depicter::media()->getAltText( $imageID ) : ''
		];

		if( !empty( $this->renderArgs['default']['assetId'] ) ){
			$args[ 'data-depicter-src' ] = $imageUrl;
		}

		if ( !empty( $this->image->alt ) ) {
			$args['alt'] = $this->image->alt;
		}

		$available_args = [
			'responsiveArgs' => [
				'data-object-fit'       => 'fitMode',
				'data-object-position'  => 'position'
			]
		];

		$args = $this->getElementAttributes( 'image', $args, $available_args );

		if ( !empty( $this->kenBurnsData ) ) {
			$args = Arr::merge( $args, $this->kenBurnsData );
		}

		$cropAttributes = $this->getCropAttributes( 'image' );

		$img = Html::img( $imageUrl, Arr::merge( $cropAttributes, $args ) );

		$this->markup->nest( $img . "\n" );

		return $this->markup;
	}

	/**
	 * Render the element wrapper tag
	 *
	 * @throws \JsonMapper_Exception
	 */
	protected function renderPictureWrapper(){

		$defaultBackgroundImageID = $this->renderArgs['default']['assetId'] ?? ( $this->renderArgs['tablet']['assetId'] ?? $this->renderArgs['mobile']['assetId'] );
		$imageID = \Depicter::media()->getAttachmentId( $defaultBackgroundImageID );

		$args = [
			'class' => 'depicter-bg',
			'alt'   => is_numeric( $imageID ) ? \Depicter::media()->getAltText( $imageID ) : ''
		];

		if ( !empty( $this->image->alt ) ) {
			$args['alt'] = $this->image->alt;
		}

		$this->markup = Html::picture( $args );
	}

	/**
	 * Renders and appends necessary source tags with media queries for breakpoints
	 */
	protected function renderSourceTags(){

		$desktopSources = $this->getSourceUrls( 'default' );
		$tabletSources  = $this->getSourceUrls( 'tablet' );
		$mobileSources  = $this->getSourceUrls( 'mobile' );

		if( ! $tabletSources && ! $mobileSources  ){
			$this->appendSourceTag( $desktopSources );
			return;
		}

		if( $desktopSources == $tabletSources ){
			if( $tabletSources == $mobileSources ){
				// if all breakpoints sources are the same
				$this->appendSourceTag( $desktopSources );
			} else {
				// if desktop and tablet sources are the same
				$this->appendSourceTag( $mobileSources, 'max-width', $this->breakpoints['mobile'] );
				$this->appendSourceTag( $desktopSources, 'min-width', $mobileSources ? (int) $this->breakpoints['mobile'] + 1 : 0 );
			}
		} elseif( $tabletSources == $mobileSources ){
			// if tablet and mobile sources are the same
			$this->appendSourceTag( $tabletSources, 'max-width', $this->breakpoints['tablet'] );
			$this->appendSourceTag( $desktopSources, 'min-width', ( $tabletSources ? (int) $this->breakpoints['tablet'] + 1 : 0 ) );
		} else {
			$this->appendSourceTag( $mobileSources, 'max-width', $this->breakpoints['mobile'] );
			$this->appendSourceTag( $tabletSources, 'max-width', $this->breakpoints['tablet'] );

			$mediaQuerySize = (int) $this->breakpoints['default'];
			if( ! $tabletSources ){
				$mediaQuerySize = $mobileSources ? (int) $this->breakpoints['mobile'] + 1 : 0;
			}
			$this->appendSourceTag( $desktopSources, 'min-width', $mediaQuerySize );
		}

	}

	/**
	 * Retrieves source urls (srcset) for a breakpoint in array
	 *
	 * @param string $device
	 *
	 * @return array
	 */
	protected function getSourceUrls( $device = 'default' ){

		$imageSources = [];

		try{
			if ( $device == 'default' && ! empty( $this->renderArgs['default']['assetId'] ) ) {
				$imageSources[] = \Depicter::media()->getSourceUrl( $this->renderArgs['default']['assetId'] );
				if ( !empty( $imageSources ) && null !== $defaultAssetID = \Depicter::media()->getAttachmentId( $this->renderArgs['default']['assetId'] ) ) {
					[ $imageSource, $width, $height ] = wp_get_attachment_image_src( $defaultAssetID, 'full');
					$retinaImageSource = \Depicter::media()->resizeSourceUrl(
						$defaultAssetID,
						$width  * 2,
						$height * 2 );

					if( $retinaImageSource ){
						$imageSources[] = $retinaImageSource . ' 2x';
					}
				}
			} else if ( $device == 'tablet' && !empty( $this->renderArgs['tablet']['assetId'] ) ) {
				if ( $this->renderArgs['tablet']['assetId'] == $this->renderArgs['default']['assetId'] ) {
					$imageSources[] = \Depicter::media()->getSourceUrl( $this->renderArgs['default']['assetId'], 'medium');
					$imageSources[] = \Depicter::media()->getSourceUrl( $this->renderArgs['default']['assetId'], 'full') . ' 2x';
				} else {
					$imageSources[] = \Depicter::media()->getSourceUrl( $this->renderArgs['tablet']['assetId'] );
					if ( !empty( $imageSources ) && null !== $tabletAssetID = \Depicter::media()->getAttachmentId( $this->renderArgs['tablet']['assetId'] ) ) {
						[ $imageSource, $width, $height ] = wp_get_attachment_image_src( $tabletAssetID, 'full');
						$retinaImageSource = \Depicter::media()->resizeSourceUrl(
							$tabletAssetID,
							$width  * 2,
							$height * 2 );

						if( $retinaImageSource ){
							$imageSources[] = $retinaImageSource . ' 2x';
						}
					}
				}
			} else if ( $device == 'mobile' && !empty( $this->renderArgs['mobile']['assetId'] ) ) {
				if ( $this->renderArgs['mobile']['assetId'] == $this->renderArgs['default']['assetId'] || $this->renderArgs['mobile']['assetId'] == $this->renderArgs['tablet']['assetId'] ) {
					$imageSources[] = \Depicter::media()->getSourceUrl( $this->renderArgs['mobile']['assetId'], 'medium');
					$imageSources[] = \Depicter::media()->getSourceUrl( $this->renderArgs['mobile']['assetId'], 'large') . ' 2x';
				} else {
					$imageSources[] = \Depicter::media()->getSourceUrl( $this->renderArgs['mobile']['assetId'] );
					if ( !empty( $imageSources ) && null !== $mobileAssetID = \Depicter::media()->getAttachmentId( $this->renderArgs['mobile']['assetId'] ) ) {
						[ $imageSource, $width, $height ] = wp_get_attachment_image_src( $mobileAssetID, 'full');
						$retinaImageSource = \Depicter::media()->resizeSourceUrl(
							$mobileAssetID,
							$width  * 2,
							$height * 2 );

						if( $retinaImageSource ){
							$imageSources[] = $retinaImageSource . ' 2x';
						}
					}
				}
			}
		} catch( \Exception $e ){
		}

		return $imageSources;
	}

	/**
	 * Generates and appends a source tag with media query to element markup
	 *
	 * @param array  $imageSources
	 * @param string $mediaQueryCondition
	 * @param int   $mediaQuerySize
	 */
	protected function appendSourceTag( $imageSources = [], $mediaQueryCondition = 'max-width', $mediaQuerySize = null ){
		if( ! $imageSources ){
			return;
		}

		$this->addMediaUlrToDictionary( $imageSources, $mediaQueryCondition, $mediaQuerySize );

		$attributes = [
			'data-depicter-srcset' => trim( implode( ', ', $imageSources ), ', ' ),
			'srcset' => \Depicter::media()::IMAGE_PLACEHOLDER_SRC,
		];
		if( $mediaQueryCondition && $mediaQuerySize ){
			$attributes['media'] = '(' . $mediaQueryCondition . ': ' . $mediaQuerySize . 'px)';
		}
		$sourceTag = Html::source( $attributes );

		$this->markup->nest( "\n" . $sourceTag . "\n" );
	}

	/**
	 * Renders video tag
	 *
	 * @param $videoUrl
	 *
	 * @return \TypeRocket\Html\Html
	 */
	public function renderVideo( $videoUrl ) {

		$args = [
			'class' 		=> Selector::prefixify( 'bg-video' ),
			'data-video-src' => $videoUrl,
			'preload'       => 'metadata',
			'playsinline'   => "true"
		];

		$available_args = [
			'data-loop'  => 'loop',
			'data-goto-next'  => 'goNextSlide',
			'data-auto-pause' => 'pause',
			'data-player-type' => 'type',
			'responsiveArgs' => [
				'data-object-fit'       => 'fitMode',
				'data-object-position'  => 'position'
			]
		];

		$args = $this->getElementAttributes( 'video', $args, $available_args );

		return Html::div( $args );
	}

	/**
	 * Get crop attributes
	 *
	 * @param string $type
	 *
	 * @return array
	 */
	protected function getCropAttributes( string $type = 'image' ){
		if ( ! $this->hasCustomFitMode( $type ) ) {
			return [];
		}

		$attributes = [];
		$breakpointNames = Breakpoints::names();

		foreach ( $breakpointNames as $device ) {
			if( ! empty( $this->{$type}->cropData->{$device} ) ){
				$attributeDeviceValue = $this->{$type}->cropData->{$device};
				$attributeDeviceValue = is_object( $attributeDeviceValue ) || is_array( $attributeDeviceValue ) ? JSON::encode( $attributeDeviceValue ) : '';
				$attributeDeviceName  = $device === 'default' ? 'data-crop' : "data-{$device}-crop";
				$attributes[ $attributeDeviceName ] = $attributeDeviceValue;
			}
		}

		return $attributes;
	}

	/**
	 * Whether background types has custom fit mode or not
	 *
	 * @param string $type
	 *
	 * @return bool
	 */
	protected function hasCustomFitMode( string $type = 'image' ){
		if ( empty( $this->{$type}->fitMode->default ) ) {
			return false;
		}

		$hasCustomFitMode= false;
		$breakpointNames = Breakpoints::names();

		foreach ( $breakpointNames as $device ) {
			if( !empty( $this->{$type}->fitMode->{$device} ) && $this->{$type}->fitMode->{$device} === 'custom' ){
				return true;
			}
		}

		return false;
	}

	public function getElementAttributes( $element_type, $args, $available_args ) {
		foreach ( $available_args as $attribute => $property ) {
			// for object properties that has responsive value like fitMode and position
			if ( $attribute == 'responsiveArgs' ) {
				foreach ( $property as $key => $value ) {
					$breakpointNames = Breakpoints::names();
					if ( !empty( $this->{$element_type}->{$value} ) ) {
						foreach ( $breakpointNames as $device ) {
							$args[ $key ][] = !empty( $this->{$element_type}->{$value}->{$device} ) ? $this->{$element_type}->{$value}->{$device} : '';
						}
						$args[ $key ] = implode(',', $args[ $key ] );
					}
				}

			// for simple object properties
			} elseif ( !empty( $this->{$element_type}->{$property} ) ) {
				$value = $this->{$element_type}->{$property} == "1" ? "true" : $this->{$element_type}->{$property};
				$args[ $attribute ] = $value;
			}

			// In video background, if each of `muted` or `loop` are not set, consider it as `true` by default
			if( $element_type === 'video' ){
				if( $property == 'loop' && ! isset( $this->{$element_type}->{$property} ) ) {
					$args[ $attribute ] = 'true';
				}

				if ( $property == 'type' && ! empty( $this->{$element_type}->{$property} ) ) {
					if ( $this->{$element_type}->{$property} == 'selfHostedVideo' ) {
						$args[ $attribute ] = 'native';
					} else if ( $this->{$element_type}->{$property} == 'embedVideo' ) {
						$args[ $attribute ] = $this->{$element_type}->embedType;
						if ( $this->{$element_type}->embedType == 'youtube' && ! empty( $args['data-video-src'] ) ) {
							$args['data-video-poster'] = Embed::getYouTubePosterUrl( $args['data-video-src'] );
						}
					}
				}
			}
		}
		return $args;
	}

	/**
	 * Get css background color
	 *
	 * @return array
	 */
	public function getColor() {
		$devices = Breakpoints::names();

		$css = [];
		foreach ( $devices as $device ) {
			if ( !empty( $this->color->{$device} ) ) {
				if ( false == strpos( $this->color->{$device}, 'gradient' ) ) {
					$css[ $device]['background-color'] = $this->color->{$device};
				} else {
					$css[ $device]['background-image'] = $this->color->{$device};
				}
			}
		}

		return $css;
	}

	/**
	 * Get background overlay styles
	 *
	 * @return array
	 */
	public function getOverlayStyles() {
		$default = [
			"content" => '""',
			"display" => "block",
			"position"=> "absolute",
			"top"     => "0",
			"bottom"  => "0",
			"right"   => "0",
			"left"    => "0",
			"z-index" => "1",
		];

		$devices = Breakpoints::names();

		$css = [];
		foreach ( $devices as $device ) {
			if ( !empty( $this->overlay->{$device} ) && ('transparent' !== $this->overlay->{$device} ) ) {
				$css[ $device] = $default;
				if ( false == strpos( $this->overlay->{$device}, 'gradient' ) ) {
					$css[ $device]['background-color'] = $this->overlay->{$device};
				} else {
					$css[ $device]['background-image'] = $this->overlay->{$device};
				}
			}
		}

		return $css;
	}

	/**
	 * Get filter styles of background
	 *
	 * @return array
	 */
	public function getSectionBackgroundFilter() {
		if( empty( $this->filter ) ){
			return [];
		}
		return $this->filter->set([]);
	}

	/**
	 * Get class name of background container
	 *
	 * @return string
	 */
	public function getContainerClassName() {
		if ( !empty( $this->video->src ) && ( ( $this->video->type ?? '' ) !== "embedVideo" ) ) {
			return Selector::prefixify('bg-video');
		}

		return Selector::prefixify('section-background');
	}

	/**
	 * Set ken burns data
	 *
	 * @param array $kenBurnsData
	 *
	 * @return void
	 */
	public function setKenBurnsData( array $kenBurnsData = [] ) {
		$this->kenBurnsData = $kenBurnsData;
	}

	/**
	 * Renders embed video tag
	 *
	 * @return \TypeRocket\Html\Html
	 */
	public function renderEmbedVideo() {
		$embedUrl = $this->video->src;

		if ( $this->video->embedType == 'youtube' ) {
			if( $embedUrl = Embed::getYouTubeVimeoEmbedUrl( $embedUrl ) ){
				$embedUrl = add_query_arg([
					'controls' => '0',
					'mute' => '1',
					'rel' => '0'
				], $embedUrl );
			}
		} else if ( $this->video->embedType == 'vimeo' ) {
			if( $embedUrl = Embed::getYouTubeVimeoEmbedUrl( $embedUrl ) ){
				$embedUrl = add_query_arg([
					'controls' => '0',
					'background' => '1'
				], $embedUrl );
			}
		}

		$iframe = Html::iframe([
			'data-depicter-src' => $embedUrl,
			"frameborder" => "0",
			"allowfullscreen" => "",
			'data-type' => $this->video->embedType,
			'data-width' => $this->video->size->width,
			'data-height' => $this->video->size->height,
			'data-goto-next' => $this->video->goNextSlide ?? false,
			'data-auto-pause' => $this->video->pause ?? false,
			'data-loop' => $this->video->loop ?? true
		]);

		if ( $this->video->embedType == 'youtube' ) {
			$iframe .= Html::img( Embed::getYouTubePosterUrl( $this->video->src ), [
				'class' => Selector::prefixify( 'bg-embed-poster' )
			]);
		}

		return Html::div(['class' => 'depicter-bg-embed'], $iframe );
	}
}
