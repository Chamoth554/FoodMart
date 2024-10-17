<?php
namespace Depicter\Document\Models\Common\Styles;


use Depicter\Document\CSS\Breakpoints;

class Height extends States
{
	/**
	 * style name
	 */
	const NAME = 'height';

	public function set( $css ) {
		$devices = Breakpoints::names();
		foreach ( $devices as $device ) {
			if ( !empty( $this->{$device}->value ) ) {
				$css[ $device ][ self::NAME ] = $this->{$device}->value . $this->{$device}->unit;
			}
		}

		return $css;
	}
}
