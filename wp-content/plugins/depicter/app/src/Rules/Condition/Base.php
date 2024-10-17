<?php

namespace Depicter\Rules\Condition;

use Averta\Core\Utility\Str;

class Base {

	/**
	 * @var string
	 */
	public $id;

	/**
	 *
	 * @var string
	 */
	public $slug;

	/**
	 * Control type 'multiSelect', 'remoteMultiSelect', 'dropdown'
	 *
	 * @var string
	 */
	public $control;

	/**
	 * Selection mode of condition
	 *
	 * @var string
	 */
	public $selectionMode = 'include';

	/**
	 * Value of this condition
	 *
	 * @var array
	 */
	public $value = [];

	/**
	 * Tier of this condition
	 *
	 * @var string
	 */
	protected $tier = 'pro-user';

	/**
	 * Whether the condition is queryable or not
	 *
	 * @var bool
	 */
	protected $queryable = false;

	/**
	 * Default value
	 *
	 * @var array|string
	 */
	protected $defaultValue = ["all"];

	/**
	 * Group id that condition belongs to
	 *
	 * @var string
	 */
	protected $belongsTo = '';

	/**
	 * Post type
	 *
	 * @var string
	 */
	protected $postType = 'post';

	/**
	 * Maximum number of allowed options for the control
	 * Only useful for conditions having query option enabled
	 *
	 * @var int
	 */
	protected $maxOptionsNumber = 2000;


	/**
	 * Whether current condition is queryable or not
	 *
	 * @return bool
	 */
	public function queryable(){
		return $this->queryable;
	}

	/**
	 * Condition label
	 *
	 * @return string
	 */
	public function getLabel(){
		return '';
	}

	/**
	 * Condition description
	 *
	 * @return string|null
	 */
	public function getDescription(){
		return null;
	}

	/**
	 * Retrieves control options
	 *
	 * @return array
	 */
	public function getControlOptions(){
		$options = [
			'defaultValue' => $this->defaultValue
		];
		if( $this->queryable() ){
			$options['query'] = $this->slug;
		}

		return $options;
	}

	/**
	 * Retrieves properties which can be exposed
	 *
	 * @return array
	 */
	public function getProperties(){
		$properties = [
			'slug'    => $this->slug,
			'label'   => $this->getLabel(),
			'control' => $this->control,
			'tier'    => $this->tier,
			'controlOptions'  => $this->getControlOptions()
		];
		if( $this->getDescription() ){
			$properties['description'] = $this->getDescription();
		}

		return $properties;
	}

	/**
	 * Get query results for dynamic options of this condition
	 *
	 * @return array|null
	 */
	public function getQueryResults(){
		return null;
	}

	/**
	 * Check if the condition is qualified or not
	 *
	 * @param $value
	 *
	 * @return bool
	 */
	public function check( $value = null ){
		return false;
	}

	/**
	 * Compare two variables
	 *
	 * @param $var
	 * @param $operator
	 * @param $value
	 *
	 * @return bool
	 */
	public function compare( $var, $operator, $value ): bool{
		switch( $operator ) {
			case 'equal':
				return $var == $value;
			case 'greaterThan':
				return (float) $var > (float) $value;
			case 'lowerThan':
				return (float) $var < (float) $value;
			case 'greaterThanOrEqual':
				return (float) $var >= (float) $value;
			case 'lowerThanOrEqual':
				return (float) $var <= (float) $value;
			case 'containing':
				return false !== strpos( $var, $value );
			case 'notContaining':
				return false === strpos( $var, $value );
			case 'endsWith':
				return Str::ends( $var, $value );
			case 'beginsWith':
				return Str::starts( $var, $value );
			case 'regExpMatch':
				return (bool) preg_match( "/$value/", $var );
			case 'oneOf':
				$lines = explode( '\n', $value );
				return in_array( $var, $lines);
			case 'nonOf':
				$lines = explode( '\n', $value );
				return ! in_array( $var, $lines);
			default:
				return false;
		}
	}
}
