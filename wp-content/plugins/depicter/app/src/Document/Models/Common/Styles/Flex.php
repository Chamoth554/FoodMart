<?php
namespace Depicter\Document\Models\Common\Styles;


use Depicter\Document\CSS\Breakpoints;

class Flex extends States
{
	/**
	 * style name
	 */
	const NAME = 'flex';

	/**
	 * @var int
	 */
	public $flex = 1;

	/**
	 * @var string|null
	 */
	public $alignSelf;

	public function set( $css ) {
		$devices = Breakpoints::names();
		foreach ( $devices as $device ) {
			if ( !empty( $this->{$device}->columnGap ) ) {
				$css[ $device ]['column-gap'] = $this->{$device}->columnGap . 'px';
			}
			if ( !empty( $this->{$device}->display ) ) {
				$css[ $device ]['display'] = $this->{$device}->display;
			}
			if ( !empty( $this->{$device}->flex ) ) {
				$css[ $device ]['flex'] = $this->{$device}->flex ?? $this->flex;
			}
			if ( !empty( $this->{$device} ) && !empty( $this->{$device}->rowGap ) ) {
				$css[ $device ]['row-gap'] = $this->{$device}->rowGap . 'px';
			}
			if ( !empty( $this->{$device}->alignSelf ) ) {
				$css[ $device ]['align-self'] = $this->{$device}->alignSelf;
			}
		}

		return $css;
	}
}
