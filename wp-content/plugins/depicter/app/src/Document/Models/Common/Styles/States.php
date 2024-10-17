<?php
namespace Depicter\Document\Models\Common\Styles;


class States
{
	/**
	 * @var Base
	 */
	public $default;

	/**
	 * @var Base
	 */
	public $tablet;

	/**
	 * @var Base
	 */
	public $mobile;


	/**
	 * Checks whether properties for a breakpoint are available and breakpoint is enabled or not
	 *
	 * @param $device
	 *
	 * @return bool
	 */
	public function isBreakpointEnabled( $device = 'default' ) {
		return isset( $this->{$device}->enable ) ? !empty( $this->{$device}->enable ) : !empty( $this->{$device} ) ;
	}

	/**
	 * Checks whether a breakpoint is disabled or not
	 *
	 * @param $device
	 *
	 * @return bool
	 */
	public function isBreakpointDisabled( $device = 'default' ) {
		return isset( $this->{$device}->enable ) && ( $this->{$device}->enable === false );
	}
}
