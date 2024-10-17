<?php

namespace Depicter\Rules\Condition\WordPress;

use Depicter\Rules\Condition\Base as ConditionBase;

class HasTag extends ConditionBase {

	/**
	 * @inheritdoc
	 */
	public $slug = 'WordPress_HasTag';

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
		return __('Has WP Tag', 'depicter' );
	}

	public function getDescription(): ?string{
		return __( 'If displayed post has a specified tag.', 'depicter' );
	}

	/**
	 * @inheritdoc
	 */
	public function getQueryResults(){
		$terms = get_terms([
			'taxonomy' => 'post_tag',
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

		$isIncluded = empty( $value ) || has_tag( $value, $post->ID );

		return $this->selectionMode === 'include' ? $isIncluded : !$isIncluded;
	}

}
