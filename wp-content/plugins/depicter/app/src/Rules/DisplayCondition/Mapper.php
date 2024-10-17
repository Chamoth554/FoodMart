<?php

namespace Depicter\Rules\DisplayCondition;

use Averta\Core\Utility\Arr;
use Averta\WordPress\Utility\JSON;
use Depicter\Exception\EntityException;

/**
 * Hydrates displayConditions of a document in corresponding classes to process conditions
 */
class Mapper {

	/**
	 * @var mixed
	 */
	private $displayConditionsSlim;

	/**
	 * @var DisplayConditionGroup[]
	 */
	private $displayConditionGroups = [];


	/**
	 * Constructor
	 */
	public function __construct( $displayConditionsSlim ){
		$this->displayConditionsSlim = $displayConditionsSlim;
		$this->displayConditionGroups = $this->hydrate( $this->displayConditionsSlim );
	}

	/**
	 * Hydrates DisplayConditions data into appropriate classes
	 */
	private function hydrate( $displayConditionsSlim ){
		// convert displayConditions to object
		$displayConditionsSlim = is_array( $displayConditionsSlim ) ? JSON::decode( JSON::encode( $this->displayConditionsSlim ), false ) : $displayConditionsSlim;

		$groups = [];

		try{
			foreach( $displayConditionsSlim as $displayConditionGroup ){
				$mapper = new \JsonMapper();
				$mapper->classMap['\Depicter\Rules\Condition\Base'] = function ( $class, $conditionParams ){
					// Skip if slug of condition cannot be found
					if( empty( $conditionParams->slug ) || !strpos( $conditionParams->slug , '_') ){
						return $class;
					}

					// find exclusive class of each Condition by its corresponding slug
					[ $groupID, $className ] = explode('_', $conditionParams->slug );
					if ( strpos( $className, '|' ) ) {
						$className = explode( '|', $className )[0];
					}

					return "\\Depicter\\Rules\\Condition\\{$groupID}\\{$className}";
				};

				$groups[] = $mapper->map( $displayConditionGroup, new DisplayConditionGroup() );
			}
		} catch( EntityException|\JsonMapper_Exception $e ){}

		return $groups;
	}

	/**
	 * Get all DisplayConditionGroups and corresponding conditions in
	 *
	 * @return DisplayConditionGroup[]
	 */
	public function groups(){
		return $this->displayConditionGroups;
	}

	public function all(){
		$conditions = [];

		if( ! empty( $this->displayConditionGroups ) ){
			foreach( $this->displayConditionGroups as $displayConditionGroup ){
				$conditions = Arr::merge( $displayConditionGroup->conditions(), $conditions );
			}
		}

		return $conditions;
	}

	/**
	 * Checks if all conditions in groups are passed or not
	 *
	 * @param $value
	 *
	 * @return bool
	 */
	public function areMet( $value = null ){

		$currentOperator = 'and';
		$overAllCheck = true;

		if( ! empty( $this->displayConditionGroups ) ){

			foreach( $this->displayConditionGroups as $displayConditionGroup ){
				$currentGroupResult = $displayConditionGroup->check( $value );
				if( $currentOperator === 'and' ){
					if( ! $currentGroupResult ){
						return false;
					}
					$overAllCheck = true;
				} else {
					if( $currentGroupResult ){
						return true;
					}
				}

				$currentOperator = $displayConditionGroup->operator;
			}

		}

		return $overAllCheck;
	}

}
