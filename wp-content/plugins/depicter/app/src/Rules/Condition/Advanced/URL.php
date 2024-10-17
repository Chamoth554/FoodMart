<?php

namespace Depicter\Rules\Condition\Advanced;

use Averta\Core\Utility\Arr;
use Depicter\Rules\Condition\Base as ConditionBase;

class URL extends ConditionBase
{
	/**
	 * @inheritdoc
	 */
	public $slug = 'Advanced_URL';

	/**
	 * @inheritdoc
	 */
	public $control = 'comparison';

	/**
	 * @inheritdoc
	 */
	protected $belongsTo = 'Advanced';


	/**
	 * @inheritdoc
	 */
	public function getLabel(): ?string{
		return __('Page URL', 'depicter' );
	}

	/**
	 * @inheritDoc
	 */
	public function getControlOptions(): array{
		return [];
	}

	/**
	 * @inheritdoc
	 */
	public function check( $value = null ): bool{

		$value = $value ?? $this->value;
		$isIncluded = empty( $value );
		if ( ! $isIncluded ) {
			global $wp;
			$value = Arr::merge( $value, [
				'targetParam' => home_url( $wp->request ),
				'comparisonFunction' => 'equal',
				'targetValue' => ''
			]);

			$isIncluded = $this->compare( trim( $value['targetParam'], '/'), $value['comparisonFunction'], trim( $value['targetValue'], '/') );
		}

		return $this->selectionMode === 'include' ? $isIncluded : ! $isIncluded;
	}
}
