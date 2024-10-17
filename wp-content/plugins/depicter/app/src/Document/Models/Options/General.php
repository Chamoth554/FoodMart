<?php
namespace Depicter\Document\Models\Options;

use Depicter\Document\CSS\Breakpoints;

class General
{
	/**
	 * @var string
	 */
	public $fullscreenMargin;

	/**
	 * @var bool
	 */
	public $keepAspect;

	/**
	 * @var Unit
	 */
	public $minHeight;

	/**
	 * @var Unit
	 */
	public $maxHeight;

	/**
	 * @var States
	 */
	public $visible;

	/**
	 * @var string
	 */
	public $backgroundColor = '';

	/**
	 * @var object
	 */
	public $borderRadius;

	/**
	 * @var object
	 */
	public $boxShadow;

	/**
	 * @var object
	 */
	public $margin;

	/**
	 * @var object
	 */
	public $border;

	/**
	 * @var All
	 */
	protected $allOptions;


	public function setAllOptions( $allOptions ){
		$this->allOptions = $allOptions;
	}

	public function getAllOptions(){
		return $this->allOptions;
	}

	public function getStylesList(){
		$styles = [
			'default' => []
		];

		$isFullscreen = $this->getAllOptions()->getLayout() === 'fullscreen';

		if( $isFullscreen || $this->keepAspect ) {
			$styles = $this->getMinHeightStyles( $styles, true );
		}

		if( ! empty( $this->maxHeight->value )  && $isFullscreen ){
			$styles[ 'default' ]['max-height'] = $this->maxHeight;
		}

		if( $this->backgroundColor ){
			$styles['default']['background-color'] = $this->backgroundColor;
		}

		return $styles;
	}

	public function getBoxShadowStyle( $boxShadow ) {
		$offsetX = $boxShadow->offsetX ?? 10;
		$offsetY = $boxShadow->offsetY ?? 10;
		$blur = $boxShadow->blur ?? 25;
		$spread = $boxShadow->spread ?? 0;
		$color = $boxShadow->color ?? '#000';
		$inset = !empty($boxShadow->inset) ? 'inset ' : '';

		return $inset . $offsetX . "px " . $offsetY . 'px ' . $blur . 'px ' . $spread . 'px ' . $color;
	}

	public function getMinHeightStyles( $styles = [], $keepAspect = null ){
		if( empty( $styles ) ){
			$styles = [
				'default' => []
			];
		}

		$keepAspect = $keepAspect ?? $this->keepAspect;

		if( $keepAspect ) {
			$heightSizes = $this->getAllOptions()->getSizes('height', false );
			foreach ( $heightSizes as $device => $height ){
				if( ! empty( $this->minHeight->value ) && ( $device === 'default' || $height > $this->minHeight->value ) ){
					$styles[ $device ]['min-height'] = $this->minHeight;
				}
			}
		}

		return $styles;
	}

	/**
	 * Get carousel styles
	 *
	 * @param object $documentTypeOptions
	 * @return array $styles
	 */
	public function getCarouselSectionStyles( $documentTypeOptions ) {
		$styles = [
			'default' => []
		];

		if ( ! isset( $documentTypeOptions->carousel->styles ) ) {
			return $styles;
		}

		$sectionStyles = $documentTypeOptions->carousel->styles->section;
		if ( ! empty( $sectionStyles->boxShadow->enable ) ) {
			$styles['default']['box-shadow'] = $this->getBoxShadowStyle( $sectionStyles->boxShadow );
		}

		if ( ! empty( $sectionStyles->borderRadius ) ) {
			if ( isset( $sectionStyles->borderRadius->link ) && $sectionStyles->borderRadius->link ) {
				$styles[ 'default' ][ 'border-radius' ] = $sectionStyles->borderRadius->topRight->value . $sectionStyles->borderRadius->topRight->unit;
			} elseif( isset( $sectionStyles->borderRadius->topLeft ) ) {
				$styles[ 'default' ][ 'border-radius' ] = $sectionStyles->borderRadius->topLeft->value . $sectionStyles->borderRadius->topLeft->unit ." ". $sectionStyles->borderRadius->topRight->value . $sectionStyles->borderRadius->topRight->unit ." " . $sectionStyles->borderRadius->bottomRight->value . $sectionStyles->borderRadius->bottomRight->unit ." " .$sectionStyles->borderRadius->bottomLeft->value . $sectionStyles->borderRadius->bottomLeft->unit;
			}
		}

		if ( ! empty( $sectionStyles->border->enabled ) ) {
			$styles[ 'default' ][ 'border-style' ] = $sectionStyles->border->style ?? 'solid';
			if ( isset( $sectionStyles->border->link ) && $sectionStyles->border->link ) {
				$styles[ 'default' ][ 'border-width' ] = $sectionStyles->border->top->value . $sectionStyles->border->top->unit;
			} else {
				$styles[ 'default' ][ 'border-width' ] = $sectionStyles->border->top->value . $sectionStyles->border->top->unit ." ". $sectionStyles->border->right->value . $sectionStyles->border->right->unit ." " . $sectionStyles->border->bottom->value . $sectionStyles->border->bottom->unit ." " .$sectionStyles->border->left->value . $sectionStyles->border->left->unit;
			}

			if ( isset( $sectionStyles->border->color ) ) {
				$styles[ 'default' ][ 'border-color' ] = $sectionStyles->border->color;
			}
		}

		return $styles;
	}


	/**
	 * Get before init document styles
	 *
	 * @return array
	 */
	public function getBeforeInitStyles(){
		$styles = [
			'default' => []
		];
		$layout = $this->getAllOptions()->getLayout();

		if( $layout == 'fullscreen' ){
			if( is_numeric( $this->fullscreenMargin ) ){
				$styles['default']['height'] = "calc( 100vh - {$this->fullscreenMargin}px )";
			} elseif ( $this->fullscreenMargin === 'auto' ) {
				$styles['default']['height'] = "100vh";
			}
		} elseif( $layout == 'boxed' ){
			$responsiveSizes = $this->getAllOptions()->getSizes('width', true);
			foreach ( $responsiveSizes as $device => $value ){
				$styles[ $device ][ 'width' ] = $value;
			}

			$responsiveSizes = $this->getAllOptions()->getSizes('height', true);
			foreach ( $responsiveSizes as $device => $value ){
				$styles[ $device ][ 'height' ] = $value;
			}
		} elseif( $layout == 'fullwidth' ){
			$responsiveSizes = $this->getAllOptions()->getSizes('height', true);
			foreach ( $responsiveSizes as $device => $value ){
				$styles[ $device ][ 'height' ] = $value;
			}
		}

		return $styles;
	}


	public function getPrimaryContainerStyles() {
		$styles = [
			'default' => []
		];

		if ( ! empty( $this->margin ) ) {
			if ( $this->margin->link ) {
				$styles['default']['margin-top'] = $this->margin->top->value . $this->margin->top->unit;
				$styles['default']['margin-bottom'] = $this->margin->top->value . $this->margin->top->unit;
			} else {
				$styles['default']['margin-top'] = $this->margin->top->value . $this->margin->top->unit;
				$styles['default']['margin-bottom'] = $this->margin->bottom->value . $this->margin->bottom->unit;
			}
		}

		if ( ! empty( $this->borderRadius ) ) {
			if ( isset( $this->borderRadius->link ) && $this->borderRadius->link ) {
				$styles[ 'default' ][ 'border-radius' ] = $this->borderRadius->topRight->value . $this->borderRadius->topRight->unit;
			} elseif( isset( $this->borderRadius->topLeft ) ) {
				$styles[ 'default' ][ 'border-radius' ] = $this->borderRadius->topLeft->value . $this->borderRadius->topLeft->unit ." ". $this->borderRadius->topRight->value . $this->borderRadius->topRight->unit ." " . $this->borderRadius->bottomRight->value . $this->borderRadius->bottomRight->unit ." " .$this->borderRadius->bottomLeft->value . $this->borderRadius->bottomLeft->unit;
			}
		}

		if ( ! empty( $this->boxShadow ) && $this->boxShadow->enable ) {
			$styles['default']['box-shadow'] = $this->getBoxShadowStyle( $this->boxShadow );
		}

		if ( ! empty( $this->border->enable ) ) {
			$styles[ 'default' ][ 'border-style' ] = $this->border->style ?? 'solid';
			if ( isset( $this->border->link ) && ! $this->border->link ) {
				$styles[ 'default' ][ 'border-width' ] = ($this->border->top->value    ?? 1) . ($this->border->top->unit    ?? "px") ." ".
				                                         ($this->border->right->value  ?? 1) . ($this->border->right->unit  ?? "px") ." ".
				                                         ($this->border->bottom->value ?? 1) . ($this->border->bottom->unit ?? "px") ." ".
				                                         ($this->border->left->value   ?? 1) . ($this->border->left->unit   ?? "px");
			} else {
				$styles[ 'default' ][ 'border-width' ] = ($this->border->top->value ?? 1) . ($this->border->top->unit ?? "px");

			}

			if ( isset( $this->border->color ) ) {
				$styles[ 'default' ][ 'border-color' ] = $this->border->color;
			}

		}

		return $styles;
	}


}
