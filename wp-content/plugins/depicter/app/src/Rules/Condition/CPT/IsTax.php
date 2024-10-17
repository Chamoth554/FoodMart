<?php

namespace Depicter\Rules\Condition\CPT;

use Averta\Core\Utility\Arr;
use Depicter\Rules\Condition\Base as ConditionBase;

class IsTax extends ConditionBase {

	/**
	 * @inheritdoc
	 */
	public $slug = 'CPT_IsTax';

	/**
	 * @inheritdoc
	 */
	public $control = 'multiSelect';

	/**
	 * @inheritdoc
	 */
	protected $belongsTo = 'CPT';

	/**
	 * @inheritdoc
	 */
	public function getLabel(): ?string{
		return __( "Taxonomy Page", 'depicter' );
	}

	/**
	 * @inheritDoc
	 */
	public function getDescription(): ?string{
		return __( "When a Taxonomy archive page for specific taxonomy is being displayed.", 'depicter' );
	}

	/**
	 * @inheritDoc
	 */
	public function getControlOptions(){
		$options = parent::getControlOptions();

		$taxonomiesOptions = [];
		$taxonomies = get_taxonomies([
			'public'      => true,
			'show_ui'     => true,
			'_builtin'    => false
        ], 'object');

		foreach( $taxonomies as $taxonomy ) {
			if (  $taxonomy->object_type[0] == 'product' ) {
				continue;
			}

			$taxonomiesOptions[] = [
				'label' => $taxonomy->label,
				'value' => $taxonomy->name
			];
		}

		return Arr::merge( $options, [ 'options' => $taxonomiesOptions ]);
	}

	/**
	 * @inheritdoc
	 */
	public function check( $value = null ): bool{

		$value = $value ?? $this->value;

		$isIncluded = empty( $value ) ? is_archive() : is_tax( $value );

		return $this->selectionMode === 'include' ? $isIncluded : !$isIncluded;
	}

}
