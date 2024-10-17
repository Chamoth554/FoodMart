<?php

namespace Depicter\Document\Models\Common\Layout;

use Depicter\Document\CSS\Breakpoints;

class AutoLayout {

	/**
	 * @var bool
	 */
	public $enable = false;

	/**
	 * @var States|null
	 */
	public $mode;

	/**
	 * @var States|null
	 */
	public $gap;

	/**
	 * @var States|null
	 */
	public $direction;

	/**
	 * @var States|null
	 */
	public $alignment;

	/**
	 * @var States|null
	 */
	public $columns;

	/**
	 * @var States|null
	 */
	public $reverse;

	/**
	 * @var States|null
	 */
	public $wrap;

	public string $latestDirection = 'horizontal';

	public string $latestMode = 'flex';



	public function getStyles() {
		$css = [];

		if( empty( $this->mode ) ){
			return $css;
		}

		foreach ( Breakpoints::names() as $device ) {

			if ( !empty( $this->mode->{$device} ) ) {
				$this->latestMode = $this->mode->{$device};
				$css[ $device ]['display'] = $this->latestMode;
			}

			if ( !empty( $this->alignment->{$device} ) ) {
				if ( $this->latestMode == 'grid' ) {
					$css[ $device ]['place-items'] = $this->getChildrenAlignment( 'place-items', $this->alignment->{$device}, '' );
				} else {
					$this->latestDirection = $this->direction->{$device} ?? $this->latestDirection;
					$css[ $device ]['align-items'] = $this->getChildrenAlignment( 'align-items', $this->alignment->{$device}, $this->latestDirection );
					$css[ $device ]['justify-content'] = $this->getChildrenAlignment( 'justify-content', $this->alignment->{$device}, $this->latestDirection );
				}
			}

			if ( !empty( $this->direction->{$device} ) ) {
				$css[ $device ]['flex-direction'] = !empty( $this->reverse->{$device} ) ? $this->direction->{$device} . '-reverse' : $this->direction->{$device};
			}
			
			if ( $this->latestMode == 'grid') {
				// get or inherit the column number
				$column = $this->columns->{$device} ?? $this->columns->tablet ?? $this->columns->default;
				$css[ $device ]['grid-template-columns'] = "repeat( " . $column . ", 1fr)";
			}

			if ( !empty( $this->gap->{$device} ) ) {
				$css[ $device ]['gap'] = is_numeric( $this->gap->{$device} ) ? $this->gap->{$device} . 'px' : $this->gap->{$device};
			}

			if ( !empty( $this->wrap->{$device} ) ) {
				$css[ $device ]['flex-wrap'] = 'wrap';
			}
		}

		return $css;
	}

	/**
	 * Get children alignment if display type is flex
	 *
	 * @param string $property
	 * @param string $alignment
	 * @return string
	 */
	public function getChildrenAlignment( $property, $alignment, $direction ) {
		if ( $direction == 'column' ) {
			if ( $property == 'align-items') {
				switch ( $alignment[1] ) {
					case 'c':
						return 'center';
					case 'r':
						return 'end';
					case 'l':
					default:
						return 'start';
				}
			} else if ( $property == 'justify-content' ) {
				switch ( $alignment[0] ) {
					case 'm':
						return 'center';
					case 'b':
						return 'end';
					case 't':
					default:
						return 'start';
				}
			}
		} else {
			if ( $property == 'justify-content') {
				switch ( $alignment[1] ) {
					case 'c':
						return 'center';
					case 'r':
						return 'end';
					case 'l':
					default:
						return 'start';
				}
			} else if ( $property == 'align-items' ) {
				switch ( $alignment[0] ) {
					case 'm':
						return 'center';
					case 'b':
						return 'end';
					case 't':
					default:
						return 'start';
				}
			} else if ( $property == 'place-items' ) {
				switch( $alignment ) {
					case 'tl':
						return 'flex-start start';
					case 'ml':
						return 'center start';
					case 'bl':
						return 'flex-end start';
					case 'tc':
						return 'flex-start center';
					case 'mc':
						return 'center';
					case 'bc':
						return 'flex-end center';
					case 'tr':
						return 'flex-start end';
					case 'mr':
						return 'center end';
					case 'br':
						return 'flex-end end';
					default:
						return 'center';
				}
			}
		}

		return '';
	}
}
