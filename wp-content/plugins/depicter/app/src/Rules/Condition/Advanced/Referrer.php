<?php

namespace Depicter\Rules\Condition\Advanced;

use Averta\Core\Utility\Arr;
use Depicter\Rules\Condition\Base as ConditionBase;
use Depicter\Utility\Sanitize;

class Referrer extends ConditionBase
{
	/**
	 * @inheritdoc
	 */
	public $slug = 'Advanced_Referrer';

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
		return __('Referrer Path', 'depicter' );
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
			$value = Arr::merge( $value, [
				'targetParam' => $_SERVER['HTTP_REFERER'] ?  Sanitize::textfield( $_SERVER['HTTP_REFERER'] ) : '',
				'comparisonFunction' => 'equal',
				'targetValue' => ''
			]);

			$isIncluded = $this->compare( trim( $value['targetParam'], '/'), $value['comparisonFunction'], trim( $value['targetValue'], '/' ) );
		}

		return $this->selectionMode === 'include' ? $isIncluded : ! $isIncluded;
	}
}
