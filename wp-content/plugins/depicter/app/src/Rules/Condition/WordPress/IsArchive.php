<?php

namespace Depicter\Rules\Condition\WordPress;

use Averta\Core\Utility\Arr;
use Depicter\Rules\Condition\Base as ConditionBase;

class IsArchive extends ConditionBase
{
	/**
	 * @inheritdoc
	 */
	public $slug = 'WordPress_IsArchive';

	/**
	 * @inheritdoc
	 */
	public $control = 'dropdown';

	/**
	 * @inheritdoc
	 */
	protected $belongsTo = 'WordPress';

	/**
	 * @inheritdoc
	 */
	public function getLabel(): ?string{
		return __('WP Archive Page', 'depicter' );
	}

	/**
	 * @inheritDoc
	 */
	public function getDescription(): ?string{
		return __( 'When any type of Archive page is being displayed. Category, Tag, Author and Date based pages are all types of Archives.', 'depicter' );
	}

	/**
	 * @inheritDoc
	 */
	public function getControlOptions(){
		$options = parent::getControlOptions();

		return Arr::merge( $options, [ 'options' => [
			[
				'label' => __( 'WP Archive Page', 'depicter' ),
				'value' => true
			]
		]]);
	}

	/**
	 * @inheritdoc
	 */
	public function check( $value = null ): bool{

		$value = $value ?? $this->value;

		$isIncluded = is_archive();

		return $this->selectionMode === 'include' ? $isIncluded : ! $isIncluded;
	}
}
