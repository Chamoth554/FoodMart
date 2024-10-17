<?php

namespace Depicter\Rules\Condition\CPT;

use Averta\Core\Utility\Arr;
use Depicter\Rules\Condition\CPT\Base as CPTConditionBase;
class IsSingle extends CPTConditionBase
{
	/**
	 * @inheritdoc
	 */
	public $slug = 'CPT_IsSingle';

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
		return  __( "Single Page", 'depicter');
	}

	/**
	 * @inheritDoc
	 */
	public function getControlOptions(){
		$options = parent::getControlOptions();

		$singleOptions = array_map( function( $postType ){
			return [
				'label' => sprintf( __( '%s Single Page', 'depicter' ), $postType->labels->singular_name ),
				'value' => $postType->name
			];
		}, array_values( $this->availablePostTypes ) );

		return Arr::merge( $options, [ 'options' => $singleOptions ]);
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

		$isIncluded = ! empty ( $value ) ? is_singular( $value ) : is_singular();

		return $this->selectionMode === 'include' ? $isIncluded : ! $isIncluded;
	}
}
