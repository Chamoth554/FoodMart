<?php

namespace Depicter\Rules\Condition\WooCommerce;

use Depicter\Rules\Condition\Base;

class InChildProductCategory extends Base
{
	/**
	 * @inheritdoc
	 */
	public $slug = 'WooCommerce_InChildProductCategory';

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
		return __('In Child Product Category', 'depicter' );
	}

	public function getDescription(): ?string{
		return __( 'If displayed product has a category which that category has a specific parent', 'depicter' );
	}

	/**
	 * @inheritdoc
	 */
	public function getQueryResults(): array{
		$terms = get_terms([
            'taxonomy' => 'product_cat',
            'hide_empty' => true,
            'orderby' => 'count',
            'order' => 'ASC',
			'parent' => 0,
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
		$isIncluded = empty( $value ) || $this->hasParentProductCategory( $post->ID, $value );

		return $this->selectionMode === 'include' ? $isIncluded : !$isIncluded;
	}

	/**
	 * Check if product has a term which that term has a specific parent or not
	 *
	 * @param int   $productID
	 * @param array $parents
	 *
	 * @return bool
	 */
	public function hasParentProductCategory( int $productID, array $parents = [] ): bool{
		$productCategories = get_the_terms( $productID, 'product_cat' );
		if ( ! empty( $productCategories ) ) {
			foreach( $productCategories as $category ) {
				foreach( $parents as $parent ){
					if ( in_array( $parent, get_ancestors( $category->term_id, 'product_cat') ) ) {
						return true;
					}
				}
			}
		}

		return false;
	}
}
