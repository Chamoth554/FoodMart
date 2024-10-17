<?php
namespace Depicter\Document\Models\Common\Styles;

use Depicter\Document\CSS\Breakpoints;
use Depicter\Document\Helper\Helper;
use Depicter\Document\Models\Traits\HoverAbleStyleTrait;

class Transition extends States
{
	use HoverAbleStyleTrait;

	/**
	 * @var string
	 */
	public $timingFunction;

	/**
	 * @var float
	 */
	public $duration;

	/**
	 * style name
	 */
	const NAME = 'transition';

	public function set( $css ) {
		$devices = Breakpoints::names();

		foreach ( $devices as $device ) {
			// Turn off transition for a breakpoint if it is disabled in hover options
			if( $this->isHoverDisabled( $device ) ){
				$css[ $device ][ self::NAME . '-property' ] = "none";
			// Check if properties for this breakpoint are available, and generate appropriate styles
			} elseif ( $this->isBreakpointEnabled( $device ) ) {
				$timingFunction = $this->{$device}->timingFunction ?? 'ease';
				$duration = $this->{$device}->duration ?? 1;
				$css[ $device ][ self::NAME ] = "all {$timingFunction} {$duration}s";
			// If no property is set and hover is enabled for this breakpoint, set a default transition
			} elseif( $this->isHoverEnabled( $device ) ){
				$css[ $device ][ self::NAME ] = "all ease 1s";
			}
		}

		return $css;
	}

}
