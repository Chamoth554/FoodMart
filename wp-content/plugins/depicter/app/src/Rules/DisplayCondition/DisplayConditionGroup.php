<?php
namespace Depicter\Rules\DisplayCondition;

class DisplayConditionGroup {

	/**
	 * @var string
	 */
	public $matchingMode = 'all';

	/**
	 * @var int
	 */
	public $order = 0;

	/**
	 * @var string
	 */
	public $operator = 'or';

	/**
	 * Condition instances in this group
	 *
	 * @var \Depicter\Rules\Condition\Base[]
	 */
	public $conditions;

	/**
	 * Condition instances in this group
	 *
	 * @return \Depicter\Rules\Condition\Base[]
	 */
	public function conditions(){
		return $this->conditions;
	}

	/**
	 * Check if conditions are passed in current matchingMode
	 *
	 * @param $value
	 *
	 * @return bool
	 */
	public function check( $value = null ){

		if( ! empty( $this->conditions ) ){

			if( $this->matchingMode === 'any' ){
				foreach( $this->conditions as $condition ){
					if( $condition->check( $value ) ){
						return true;
					}
				}
				return false;

			// if matchingMode is set to 'all'
			} else {
				foreach( $this->conditions as $condition ){
					if( ! $condition->check( $value ) ){
						return false;
					}
				}

				return true;
			}
		}

		return true;
	}
}
