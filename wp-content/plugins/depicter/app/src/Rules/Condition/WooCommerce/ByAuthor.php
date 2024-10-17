<?php

namespace Depicter\Rules\Condition\WooCommerce;

use Averta\Core\Utility\Arr;
use Depicter\Rules\Condition\Base as ConditionBase;

class ByAuthor extends ConditionBase
{
	/**
	 * @inheritdoc
	 */
	public $slug = 'WooCommerce_ByAuthor';

	/**
	 * @inheritdoc
	 */
	public $control = 'remoteMultiSelect';

	/**
	 * @inheritdoc
	 */
	protected $belongsTo = 'WooCommerce';

	/**
	 * @inheritdoc
	 */
	protected $queryable = true;

	/**
	 * @inheritdoc
	 */
	public function getLabel(): ?string{
		return __('Products By Author', 'depicter' );
	}

	/**
	 * @inheritDoc
	 */
	public function getDescription(): ?string{
		return __( 'When any product by specified author is being displayed.', 'depicter' );
	}

	/**
	 * @inheritdoc
	 */
	public function getQueryResults(){
		$authors = get_users([
			'has_published_posts' => true,
	        'fields' => [ 'ID', 'display_name' ]
		]);

		return array_map( function( $author ){
			return [
				'label' => $author->display_name,
				'value' => $author->ID
			];
		}, $authors );
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

		$isIncluded = empty( $value ) || in_array( $post->post_author, $value );

		return $this->selectionMode === 'include' ? $isIncluded : ! $isIncluded;
	}
}
