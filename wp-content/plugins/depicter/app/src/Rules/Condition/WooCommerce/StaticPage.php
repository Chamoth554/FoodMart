<?php

namespace Depicter\Rules\Condition\WooCommerce;

use Averta\Core\Utility\Arr;
use Depicter\Rules\Condition\Base as ConditionBase;

class StaticPage extends ConditionBase
{
	/**
	 * @inheritdoc
	 */
	public $slug = 'WooCommerce_StaticPage';

	/**
	 * @inheritdoc
	 */
	public $control = 'multiSelect';

	/**
	 * @inheritdoc
	 */
	protected $belongsTo = 'WooCommerce';

	/**
	 * Tier of this condition
	 *
	 * @var string
	 */
	protected $tier = 'pro-user';

	/**
	 * @inheritdoc
	 */
	public function getLabel(): ?string{
		return __('WooCommerce Static Pages', 'depicter' );
	}

	/**
	 * @inheritDoc
	 */
	public function getControlOptions(){
		$options = parent::getControlOptions();

		return Arr::merge( $options, [ 'options' => [
			[
				'label' => __( 'Cart Page', 'depicter' ),
				'value' => 'is_cart'
			],
			[
				'label' => __( 'Checkout Page', 'depicter' ),
				'value' => 'is_checkout'
			],
		]]);
	}

	/**
	 * @inheritdoc
	 */
	public function check( $value = null ): bool{
		$isIncluded = false;

		$value = $value ?? $this->value ?? $this->defaultValue;

		if( !empty( $value ) && is_array( $value ) ){
			foreach( $value as $page ){
				if( $page == 'all' ){
					$isIncluded = is_cart() || is_checkout();
				} elseif( $page == 'is_cart' ) {
					$isIncluded = is_cart();
				} elseif( $page == 'is_checkout' ) {
					$isIncluded = is_checkout();
				}

				if( $isIncluded ){
					break;
				}
			}

		}

		return $this->selectionMode === 'include' ? $isIncluded : ! $isIncluded;
	}
}
