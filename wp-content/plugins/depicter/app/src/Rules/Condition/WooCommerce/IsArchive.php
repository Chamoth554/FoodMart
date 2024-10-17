<?php

namespace Depicter\Rules\Condition\WooCommerce;

use Averta\Core\Utility\Arr;
use Depicter\Rules\Condition\Base as ConditionBase;

class IsArchive extends ConditionBase
{
	/**
	 * @inheritdoc
	 */
	public $slug = 'WooCommerce_IsArchive';

	/**
	 * @inheritdoc
	 */
	public $control = 'multiSelect';

	/**
	 * @inheritdoc
	 */
	protected $belongsTo = 'WooCommerce';

	/**
	 * @inheritdoc
	 */
	public function getLabel(): ?string{
		return __('Product Archive', 'depicter' );
	}

	/**
	 * @inheritDoc
	 */
	public function getControlOptions(){
		$options = parent::getControlOptions();

		return Arr::merge( $options, [ 'options' => [
			[
				'label' => __( 'Shop Page', 'depicter' ),
				'value' => 'isShop'
			],
			[
				'label' => __( 'Search Results', 'depicter' ),
				'value' => 'isSearch'
			],
			[
				'label' => __( 'Product Categories', 'depicter' ),
				'value' => 'isCategory'
			],
			[
				'label' => __( 'Product Tags', 'depicter' ),
				'value' => 'isTags'
			],
		]]);
	}

	/**
	 * @inheritdoc
	 */
	public function check( $value = null ): bool{

		$value = $value ?? $this->value;
		if ( empty( $value ) ) {
			$isIncluded = is_shop() || is_product_tag() || is_product_category();
		} else {
			$isIncluded = false;
			foreach( $value as $archiveType ) {
				if ( $archiveType == 'isShop' ) {
					$isIncluded = is_shop();
				} elseif ( $archiveType == 'isSearch' ) {
					$isIncluded = is_search();
				} elseif ( $archiveType == 'isCategory' ) {
					$isIncluded = is_product_category();
				} elseif ( $archiveType == 'isTag' ) {
					$isIncluded = is_product_tag();
				}

				if ( $isIncluded ) {
					break;
				}
			}
		}

		return $this->selectionMode === 'include' ? $isIncluded : !$isIncluded;
	}
}
