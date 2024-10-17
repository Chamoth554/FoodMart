<?php

namespace Depicter\Rules\Condition\WordPress;

use Depicter\Rules\Condition\Base as ConditionBase;

class InCategory extends ConditionBase {

	/**
	 * @inheritdoc
	 */
	public $slug = 'WordPress_InCategory';

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
	protected $belongsTo = 'WordPress';


	/**
	 * @inheritdoc
	 */
	public function getLabel(): ?string{
		return __('In WP Category', 'depicter' );
	}

	public function getDescription(): ?string{
		return __( 'If displayed post is in the specified category', 'depicter' );
	}

	/**
	 * @inheritdoc
	 */
	public function getQueryResults(){
		$terms = get_terms([
			'taxonomy' => 'category',
            'hide_empty' => false,
            'orderby' => 'name',
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

		if ( is_null( $post ) ) {
			return false;
		}

		$value = $value ?? $this->value;

		$isIncluded = empty( $value ) || has_category( $value, $post->ID );

		return $this->selectionMode === 'include' ? $isIncluded : !$isIncluded;
	}

}
