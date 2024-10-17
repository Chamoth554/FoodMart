<?php

namespace Depicter\Rules\Condition\Audience;

use Averta\Core\Utility\Arr;
use Depicter\Rules\Condition\Base as ConditionBase;
use foroco\BrowserDetection;

class Browser extends ConditionBase
{
	/**
	 * @inheritdoc
	 */
	public $slug = 'Audience_Browser';

	/**
	 * @inheritdoc
	 */
	public $control = 'multiSelect';

	/**
	 * @inheritdoc
	 */
	protected $belongsTo = 'Audience';

	/**
	 * @inheritdoc
	 */
	public function getLabel(): ?string{
		return __('Visitor Browser', 'depicter' );
	}

	/**
	 * @inheritDoc
	 */
	public function getControlOptions(){
		$options = parent::getControlOptions();

		return Arr::merge( $options, [ 'options' => [
			[
				'label' => __( 'Chrome', 'depicter' ),
				'value' => 'Chrome'
			],
			[
				'label' => __( 'Firefox', 'depicter' ),
				'value' => 'Firefox'
			],
			[
				'label' => __( 'Safari', 'depicter' ),
				'value' => 'Safari'
			],
			[
				'label' => __( 'Edge', 'depicter' ),
				'value' => 'Edge'
			],
			[
				'label' => __( 'Opera', 'depicter' ),
				'value' => 'Opera'
			],
			[
				'label' => __( 'Internet Explorer', 'depicter' ),
				'value' => 'Internet Explorer'
			],
			[
				'label' => __( 'Brave', 'depicter' ),
				'value' => 'Brave'
			],
			[
				'label' => __( 'Samsung Browser', 'depicter' ),
				'value' => 'Samsung Browser'
			],
			[
				'label' => __( 'UC Browser', 'depicter' ),
				'value' => 'UC Browser'
			],
			[
				'label' => __( 'Huawei Browser', 'depicter' ),
				'value' => 'Huawei Browser'
			],
			[
				'label' => __( 'Vivaldi', 'depicter' ),
				'value' => 'Vivaldi'
			],
		]]);
	}

	/**
	 * @inheritdoc
	 */
	public function check( $value = null ): bool{

		$value = $value ?? $this->value;
		$isIncluded = empty( $value );
		if ( ! $isIncluded ) {
			$userAgent = ! empty( $_SERVER['HTTP_USER_AGENT'] ) ? $_SERVER['HTTP_USER_AGENT'] : '';
			$browser = new BrowserDetection();
			foreach( $value as $device ) {
				$isIncluded = strtolower( $device ) == strtolower( $browser->getBrowser( $userAgent )['browser_name'] );
				if ( $isIncluded ) {
					break;
				}
			}
		}

		return $this->selectionMode === 'include' ? $isIncluded : ! $isIncluded;
	}
}
