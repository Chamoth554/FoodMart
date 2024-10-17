<?php
namespace Depicter\Document\Models\Common\Styles;


use Depicter\Document\CSS\Breakpoints;

class BlendingMode extends States
{
	/**
	 * style name
	 */
	const NAME = 'mix-blend-mode';

	public function set( $css ) {
		$devices = Breakpoints::names();
		foreach ( $devices as $device ) {

			if ( $this->isBreakpointEnabled( $device ) && !empty( $this->{$device}->type ) ) {
				$css[ $device ][ self::NAME ] = $this->{$device}->type;
			} elseif( $this->isBreakpointDisabled( $device ) ){
				$css[$device][ self::NAME ] = 'normal';
			}
		}

		return $css;
	}
}
