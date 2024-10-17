<?php

namespace Depicter\Rules;

use Averta\Core\Utility\Data;
use Averta\Core\Utility\JSON;
use Depicter\Rules\DisplayCondition\Mapper;

/**
 * Class to parsing DisplayRules of a document
 *
 * @property array|null $displayConditions      Get displayConditions of current document in array
 * @property array|null $displayConditionsSlim  Get displayConditions of current document without redundant group and condition IDs in array
 */
class DisplayRules {

	/**
	 * Document ID
	 *
	 * @var int
	 */
	protected $documentID;

	/**
	 * Stores DisplayRules
	 *
	 * @var array
	 */
	protected $rules = [];

	/**
	 * Stores DisplayConditions
	 *
	 * @var array
	 */
	protected $displayConditionsInArray = [];


	/**
	 * Stores DisplayConditions
	 *
	 * @var Mapper
	 */
	protected $displayConditionsMapper;



	public function __construct( $documentID ){
		$this->documentID   = $documentID;
		$this->rules['raw'] = \Depicter::metaRepository()->get( $documentID, 'rules', '');
	}

	/**
	 * Retrieves RAW DisplayRules
	 *
	 * @return mixed
	 */
	public function raw(){
		return $this->rules['raw'];
	}

	/**
	 * Get displayRules in array format
	 *
	 * @return array
	 */
	public function toArray(){
		if( !empty( $this->rules['array'] ) ){
			return $this->rules['array'];
		}

		if ( JSON::isJson( $this->raw() ) ) {
			$this->rules['array'] = JSON::decode( $this->raw(), true );
		}

		return $this->rules['array'] ?? [];
	}

	/**
	 * Get displayRules in object format
	 *
	 * @return object
	 */
	public function get(){
		if( !empty( $this->rules['object'] ) ){
			return $this->rules['object'];
		}

		if ( JSON::isJson( $this->raw() ) ) {
			$this->rules['object'] = JSON::decode( $this->raw(), false );
		}

		return $this->rules['object'] ?? new \stdClass();
	}

	/**
	 * Stores displayRules for the document
	 *
	 * @param $content
	 *
	 * @return false|mixed
	 */
	public function set( $content ){
		$result = \Depicter::metaRepository()->update( $this->documentID, 'rules', $content );

		$this->rules = [];
		$this->rules['raw'] = \Depicter::metaRepository()->get( $this->documentID, 'rules', '');

		return $result;
	}

	/**
	 * Get value of a defined property
	 *
	 * @param $name
	 *
	 * @return array|null
	 */
	public function __get( $name ){

		// Get all displayConditions of current document in array
		if( $name === 'displayConditions' ){
			if( !empty( $this->displayConditionsInArray ) ){
				return $this->displayConditionsInArray;
			}
			$displayConditions = $this->get()->displayConditions ?? [];
			$this->displayConditionsInArray = $displayConditions ? Data::cast( $displayConditions, 'array' ) : $displayConditions;

			return $this->displayConditionsInArray;

		// Get all displayConditions of current document without redundant group and condition IDs
		} elseif( $name === 'displayConditionsSlim' ){
			// remove all redundant displayGroup IDs
			$displayConditions = array_values( $this->displayConditions );
			// remove all redundant condition IDs
			return array_map( function( $displayConditionGroup ){
				if( is_array( $displayConditionGroup['conditions'] ) ){
					$displayConditionGroup['conditions'] = array_values( $displayConditionGroup['conditions'] );
				}
				return $displayConditionGroup;
			}, $displayConditions );
		}

		return null;
	}

	/**
	 * Get displayConditionsMapper instance containing all parsed data of displayConditions
	 *
	 * @return Mapper
	 */
	public function displayConditions(){
		if( empty( $this->displayConditionsMapper ) ){
			$this->displayConditionsMapper = new Mapper( $this->displayConditionsSlim );
		}
		return $this->displayConditionsMapper;
	}

	/**
	 * Whether the document can be visible or not
	 *
	 * @param array    $args  params
	 *
	 * @return bool
	 */
	public function isVisible( $args = [] ){
		$rules = $this->get();

		if ( !empty( $args['isPrivilegedUser'] ) && !empty( $rules->visibilitySchedule ) && !empty( $rules->visibilitySchedule->enable ) ) {
			$visibilityTime = $rules->visibilitySchedule;
			if ( !empty( $visibilityTime->start ) && ! \Depicter::schedule()->isDatePassed( $visibilityTime->start ) ) {
				return false;
			}

			if ( !empty( $visibilityTime->end ) && \Depicter::schedule()->isDatePassed( $visibilityTime->end ) ) {
				return false;
			}
		}

		return true;
	}
}
