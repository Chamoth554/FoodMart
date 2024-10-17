<?php

namespace Depicter\Rules\Condition\WooCommerce;

use Depicter\Rules\Condition\Base;

class InProductCategory extends Base
{
	/**
	 * @inheritdoc
	 */
	public $slug = 'WooCommerce_InProductCategory';

	/**
	 * @inheritdoc
	 */
	public $control = 'remoteMultiSelect';

	/**
	 * @inheritdoc
	 */
	protected $queryable = true;

	/**
	 * @inheritdoc
	 */
	protected $belongsTo = 'WooCommerce';

	/**
	 * @inheritdoc
	 */
	public function getLabel(): ?string{
		return __('In Product Category', 'depicter' );
	}

	public function getDescription(): ?string{
		return __( 'If displayed product is in the specified category', 'depicter' );
	}

	/**
	 * @inheritdoc
	 */
	public function getQueryResults(): array{
		$terms = get_terms([
            'taxonomy' => 'product_cat',
            'hide_empty' => false,
            'orderby' => 'count',
            'order' => 'ASC',
        ]);

		return array_map( function( $term ){
			return [
				'label' => $term->name,
				'value' => $term->term_id
			];
		}, $terms );
	}

	/**
	 * @inheritdoc
	 */
	public function check( $value = null ): bool{
		global $post;

		if ( is_null( $post ) || ! is_product() ) {
			return false;
		}

		$value = $value ?? $this->value;

		$isIncluded = empty( $value ) || has_term( $value, 'product_cat' );

		return $this->selectionMode === 'include' ? $isIncluded : !$isIncluded;
	}
}
