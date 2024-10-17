<?php
namespace Depicter\Document\Models\Common\Styles;


use Depicter\Document\CSS\Breakpoints;

class BoxShadow extends States
{
	/**
	 * style name
	 */
	const NAME = 'box-shadow';

	/**
	 * @var int
	 */
	public $offsetX = 10;

	/**
	 * @var int
	 */
	public $offsetY = 10;

	/**
	 * @var int
	 */
	public $blur = 25;

	/**
	 * @var int
	 */
	public $spread = 0;

	/**
	 * @var bool
	 */
	public $inset = false;

	/**
	 * @var string
	 */
	public $color = '#000';

	public function set( $css ) {
		$devices = Breakpoints::names();
		foreach ( $devices as $device ) {
			// If properties for a breakpoint are available, generate appropriate styles
			if ( $this->isBreakpointEnabled( $device ) ) {

				$this->offsetX = $this->{$device}->offsetX ?? $this->offsetX;
				$this->offsetY = $this->{$device}->offsetY ?? $this->offsetY;
				$this->blur = $this->{$device}->blur ?? $this->blur;
				$this->spread = $this->{$device}->spread ?? $this->spread;
				$this->color = $this->{$device}->color ?? $this->color;
				$inset = !empty($this->{$device}->inset) ? 'inset ' : '';

				$css[$device][self::NAME] = $inset . $this->offsetX . "px " . $this->offsetY . 'px ' . $this->blur . 'px ' . $this->spread . 'px ' . $this->color;

			} elseif( $this->isBreakpointDisabled( $device ) ){
				$css[ $device ][ self::NAME ] = "none";
			}
		}

		return $css;
	}
}
