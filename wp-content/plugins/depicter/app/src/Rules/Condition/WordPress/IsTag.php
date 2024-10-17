<?php

namespace Depicter\Rules\Condition\WordPress;

use Averta\Core\Utility\Arr;
use Depicter\Rules\Condition\Base as ConditionBase;

class IsTag extends ConditionBase
{
	/**
	 * @inheritdoc
	 */
	public $slug = 'WordPress_IsTag';

	/**
	 * @inheritdoc
	 */
	public $control = 'remoteMultiSelect';

	/**
	 * @inheritdoc
	 */
	protected $belongsTo = 'WordPress';

	/**
	 * @inheritdoc
	 */
	protected $queryable = true;

	/**
	 * @inheritdoc
	 */
	public function getLabel(): ?string{
		return __('WP Tag Page', 'depicter' );
	}

	/**
	 * @inheritDoc
	 */
	public function getDescription(): ?string{
		return __( 'When a Tag archive page is being displayed.', 'depicter' );
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

		$value = $value ?? $this->value;

		$isIncluded = ! empty( $value ) ? is_tag( $value ) : is_tag();

		return $this->selectionMode === 'include' ? $isIncluded : !$isIncluded;
	}
}
