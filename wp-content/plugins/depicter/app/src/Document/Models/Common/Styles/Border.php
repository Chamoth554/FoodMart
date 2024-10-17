<?php
namespace Depicter\Document\Models\Common\Styles;

use Depicter\Document\CSS\Breakpoints;
use Depicter\Document\Models\Traits\HoverAbleStyleTrait;

class Border extends States
{
	use HoverAbleStyleTrait;

	/**
	 * style name
	 */
	const NAME = 'border';

	/**
	 * @var string
	 */
	public $borderWidth = '1px';

	/**
	 * @var string
	 */
	public $borderStyle = 'solid';

	/**
	 * @var string
	 */
	public $borderColor = '#000';

	public function set( $css ) {
		$devices = Breakpoints::names();

		foreach ( $devices as $device ) {

			// If in Hover state and Hover is enabled for a breakpoint, just set the border color and skip the other properties
			if( $this->isHoverEnabled( $device ) ){
				if ( !empty( $this->{$device}->color ) ) {
					$css[ $device ]['border-color'] = $this->{$device}->color;
				}

			// If in "Normal" state and properties for a breakpoint are available, generate appropriate styles
			} elseif ( $this->isBreakpointEnabled( $device ) ) {
				if( isset( $this->{$device}->top->value ) ){
					if ( !empty( $this->{$device}->link ) ) {
						$css[$device]['border-width'] = $this->{$device}->top->value . $this->{$device}->top->unit;
					} else {
						$css[$device]['border-width'] = $this->{$device}->top->value . $this->{$device}->top->unit . " " . $this->{$device}->right->value . $this->{$device}->right->unit . " " . $this->{$device}->bottom->value . $this->{$device}->bottom->unit . " " . $this->{$device}->left->value . $this->{$device}->left->unit;
					}
				}

				// make sure to set default border styles if not defined
				$css[ $device ]['border-width'] = $css[ $device ]['border-width'] ?? "1px";
				$css[ $device ]['border-style'] = $this->{$device}->style ?? "solid";
				$css[ $device ]['border-color'] = $this->{$device}->color ?? "#000";

			// If breakpoint is disabled in normal state, reset the border style
			} elseif( $this->isBreakpointDisabled( $device ) ){
				$css[ $device ][ self::NAME ] = "none";
			}

		}

		return $css;
	}
}
