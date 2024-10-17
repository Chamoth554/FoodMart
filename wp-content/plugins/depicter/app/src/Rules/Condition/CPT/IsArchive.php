<?php

namespace Depicter\Rules\Condition\CPT;

use Averta\Core\Utility\Arr;
use Depicter\Rules\Condition\CPT\Base as CPTConditionBase;

class IsArchive extends CPTConditionBase
{
	/**
	 * @inheritdoc
	 */
	public $slug = 'CPT_IsArchive';

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
		return __( "Archive Page", 'depicter' );
	}

	/**
	 * @inheritDoc
	 */
	public function getControlOptions(){
		$options = parent::getControlOptions();

		$archiveOptions = [];
		foreach( $this->availablePostTypes as $postType ) {
			if ( $postType->name == 'product' ) {
				continue;
			}

			$archiveOptions[] = [
				'label' => sprintf( __( '%s Archive', 'depicter' ), $postType->labels->singular_name ),
				'value' => $postType->name
			];
		}

		return Arr::merge( $options, [ 'options' => $archiveOptions ]);
	}

	/**
	 * @inheritdoc
	 */
	public function check( $value = null ): bool{

		$isIncluded = !empty( $value ) ? is_post_type_archive( $value ) : is_archive();

		return $this->selectionMode === 'include' ? $isIncluded : ! $isIncluded;
	}
}
