<?php

namespace Depicter\Rules\Condition\Audience;

use Averta\Core\Utility\Arr;
use Depicter\Rules\Condition\Base as ConditionBase;

class Device extends ConditionBase
{
	/**
	 * @inheritdoc
	 */
	public $slug = 'Audience_Device';

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
		return __('Visitor Device', 'depicter' );
	}

	/**
	 * @inheritDoc
	 */
	public function getControlOptions(){
		$options = parent::getControlOptions();

		return Arr::merge( $options, [ 'options' => [
			[
				'label' => __( 'Desktop', 'depicter' ),
				'value' => 'desktop'
			],
			[
				'label' => __( 'Mobile', 'depicter' ),
				'value' => 'mobile'
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
			foreach( $value as $device ) {
				if ( $device == 'mobile' ) {
					$isIncluded = wp_is_mobile();
				}

				if ( $device == 'desktop' ) {
					$isIncluded = ! wp_is_mobile();
				}

				if ( $isIncluded ) {
					break;
				}
			}
		}

		return $this->selectionMode === 'include' ? $isIncluded : ! $isIncluded;
	}
}
