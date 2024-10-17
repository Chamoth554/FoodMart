<?php

namespace Depicter\Rules\Condition\WooCommerce;

use Averta\Core\Utility\Arr;
use Depicter\Rules\Condition\Base as ConditionBase;

class IsShop extends ConditionBase
{
	/**
	 * @inheritdoc
	 */
	public $slug = 'WooCommerce_IsShop';

	/**
	 * @inheritdoc
	 */
	public $control = 'dropdown';

	/**
	 * @inheritdoc
	 */
	protected $belongsTo = 'WooCommerce';

	/**
	 * @inheritdoc
	 */
	public function getLabel(): ?string{
		return __('Entire Shop', 'depicter' );
	}

	/**
	 * @inheritDoc
	 */
	public function getControlOptions(){
		$options = parent::getControlOptions();

		return Arr::merge( $options, [ 'options' => [
			[
				'label' => __( 'All', 'depicter' ),
				'value' => true
			],
		]]);
	}

	/**
	 * @inheritdoc
	 */
	public function check( $value = null ): bool{

		$isIncluded = is_shop() || is_product() || is_product_category() || is_product_tag() || is_cart() || is_checkout() || is_account_page();

		return $this->selectionMode === 'include' ? $isIncluded : !$isIncluded;
	}
}
