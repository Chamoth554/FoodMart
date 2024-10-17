<?php

namespace Depicter\Rules\Condition\Advanced;

use Averta\Core\Utility\Arr;
use Depicter\Rules\Condition\Base as ConditionBase;

class Cookie extends ConditionBase
{
	/**
	 * @inheritdoc
	 */
	public $slug = 'Advanced_Cookie';

	/**
	 * @inheritdoc
	 */
	public $control = 'paramComparison';

	/**
	 * @inheritdoc
	 */
	protected $belongsTo = 'Advanced';

	/**
	 * @inheritdoc
	 */
	public function getLabel(): ?string{
		return __('Cookie Value', 'depicter' );
	}

	/**
	 * @inheritDoc
	 */
	public function getControlOptions(): array{
		return [
			"targetParamPlaceholder" => "cookie_name"
		];
	}

	/**
	 * @inheritdoc
	 */
	public function check( $value = null ): bool{

		$value = $value ?? $this->value;
		$isIncluded = empty( $value );
		if ( ! $isIncluded ) {
			$value = Arr::merge( $value, [
				'targetParam' => '',
				'comparisonFunction' => 'equal',
				'targetValue' => ''
			]);

			if ( !empty( $_COOKIE[ $value['targetParam'] ] ) ) {
				$isIncluded = $this->compare( $_COOKIE[ $value['targetParam'] ], $value['comparisonFunction'], $value['targetValue'] );
			}
		}

		return $this->selectionMode === 'include' ? $isIncluded : ! $isIncluded;
	}
}
