<?php

namespace Depicter\Database\Repository;

use Depicter\Database\Entity\LeadField;
use TypeRocket\Utility\Arr as TypeRocketArr;

class LeadFieldRepository
{

	/**
	 * List of known field names in form element
	 */
	const KNOWN_FORM_FIELD_NAMES = ['name', 'email', 'first-name', 'last-name', 'phone', 'address', 'website', 'message'];


	/**
	 * @var LeadField LeadField
	 */
	private $leadField;


	/**
	 * @throws \Exception
	 */
	public function __construct(){
		$this->leadField = LeadField::new();
	}

	/**
	 * @return LeadField
	 *
	 * @throws \Exception
	 */
	public function leadField(): LeadField{
		return LeadField::new();
	}

	/**
	 * Removes a lead.
	 *
	 * @param $id
	 *
	 * @return array|false|int|object|void|null
	 * @throws \Exception
	 */
	public function delete( $id ){
		if( $leadField = $this->leadField()->findById( $id ) ){
			return $leadField->delete();
		}
	}

	/**
	 * Create a lead record
	 *
	 * @param int $leadId
	 * @param string $fieldName
	 * @param string $fieldValue
	 * @param string $fieldType
	 *
	 * @return mixed
	 * @throws \Exception
	 */
	public function create( $leadId, $fieldName, $fieldValue, $fieldType = '' ) {
		return $this->leadField()->create([
			'lead_id' => $leadId,
			'name' => $fieldName,
			'value' => $fieldValue,
			'type' => $fieldType,
			'created_at' => $this->leadField()->currentDateTime(),
			'updated_at' => $this->leadField()->currentDateTime()
        ]);
	}

	/**
	 * Update a meta by relation, relation ID and meta key
	 *
	 * @param       $id
	 * @param array $fields
	 *
	 * @return mixed
	 * @throws \Exception
	 */
	public function update( $id, array $fields = [] ) {
		if ( empty( $fields ) ) {
			return false;
		}

		$leadField =  $this->leadField()->findById( $id );

		if ( $leadField && $leadField->count() ){
			return $leadField->first()->update($fields);
		}

		return false;
	}

	/**
	 * Get meta value by relation, relation ID and meta key
	 *
	 * @param $id
	 *
	 * @return array
	 * @throws \Exception
	 */
	public function get( $id ): array{
		$leadField  = $this->leadField()->findById($id)->get();

		return $leadField ? $leadField->first()->toArray() : [];
	}

	/**
	 * Queries records of leads with specified fields
	 *
	 * @param $columns
	 *
	 * @return LeadField
	 * @throws \Exception
	 */
	public function select( $columns = [] ) {
		$entity  = LeadField::new();
		$columns = !empty( $columns ) ? $columns : $entity->getTableColumns();
		return $entity->select( $columns );
	}


	/**
	 * Finds field names for lead id(s)
	 *
	 * @param int|array $leadIds
	 *
	 * @return array
	 * @throws \Exception
	 */
	public function getFieldNamesByLeadId( $leadIds ){
		if( ! $leadIds = (array) $leadIds ){
			return [];
		}
		$leadFieldNames = $this->leadField->select('name')->where('lead_id', 'IN', $leadIds )->findAll()->get();
		$leadFieldNames = $leadFieldNames ? TypeRocketArr::pluck( $leadFieldNames->toArray(), 'name' ) : [];
		return array_unique( $leadFieldNames );
	}
}
