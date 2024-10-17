<?php

namespace Depicter\Database\Repository;

use Averta\Core\Utility\Arr;
use Averta\Core\Utility\Data;
use Depicter\Database\Entity\Lead;
use Depicter\Database\Entity\LeadField;
use Depicter\Utility\Sanitize;
use TypeRocket\Database\SqlRaw;
use \TypeRocket\Utility\Arr as TypeRocketArr;

class LeadRepository
{

	/**
	 * @var Lead Lead
	 */
	private Lead $lead;

	/**
	 * @var LeadField
	 */
	private LeadField $leadField;


	public function __construct(){
		$this->lead();
	}

	/**
	 * Access to an instance of Lead entity
	 *
	 * @return Lead
	 */
	public function lead(): Lead{
		try{
			if( empty( $this->lead ) ){
				$this->lead = Lead::new();
			}
		} catch(\Exception $e){}

		return $this->lead;
	}

	/**
	 * Access to an instance of LeadField
	 *
	 * @return LeadField
	 */
	public function leadField(): LeadField{
		try{
			if( empty( $this->leadField ) ){
				$this->leadField = LeadField::new();
			}
		} catch(\Exception $e){}

		return $this->leadField;
	}

	/**
	 * Removes a lead or leads by ID(s)
	 *
	 * @param mixed $id  Can be an ID or list of comma separated IDs
	 *
	 * @return bool
	 * @throws \Exception
	 */
	public function delete( $id )
	{
		$succeed = false;

		if( is_array( $id ) ){
			$ids = $id;
		} elseif( false !== strpos( $id, ',' ) ){
			$ids = explode(',', $id );
		} else {
			$ids = [$id];
		}

		foreach( $ids as $id ){
			$id = Sanitize::int( $id );
			if( $lead = $this->lead()->findById( $id ) ){
				$lead->delete();
				$succeed = true;
			}
		}

		return $succeed;
	}

	/**
	 * Create a lead record
	 *
	 * @param int $sourceId    Document ID
	 * @param int $contentId   The ID of form or survey
	 * @param int $contentName The Name of form or survey
	 *
	 * @return mixed
	 * @throws \Exception
	 */
	public function create( $sourceId, $contentId, $contentName = '' ) {
		return $this->lead()->create([
			'source_id'    => $sourceId,
			'content_id'   => $contentId,
			'content_name' => $contentName,
			'created_at'   => $this->lead()->currentDateTime()
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

		$lead =  $this->lead()->findById( $id );

		if ( $lead && $lead->count() ){
			return $lead->first()->update($fields);
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
		$lead = Lead::new()->findById($id)->get();

		return $lead ? $lead->first()->toArray() : [];
	}

	/**
	 * @throws \Exception
	 */
	public function getResults( $args = [] ){

		// ensure args are valid
		$this->ensureValidArgs( $args );

		// if
		if( ! $args['includeFields'] ){
			return $this->getLeadsResults( $args );
		}

		return $this->getJointResults( $args );
	}

	/**
	 * @throws \Exception
	 */
	protected function getJointResults( $args ){
		// apply query args and find lead ids
		$leads = $this->getLeadsResults( $args );

		// skip if no lead found
		if( empty( $leads['leads'] ) ){
			return [];
		}
		$leads = $leads['leads'];
		// filter found lead ids
		$foundLeadsIds = TypeRocketArr::pluck( $leads, 'id' );
		$args['ids'] = $foundLeadsIds;

		// find all stored field names for above lead ids
		$leadFieldNames = \Depicter::leadFieldRepository()->getFieldNamesByLeadId( $foundLeadsIds );
		$args['fieldNames'] = $leadFieldNames;

		// join fields with found lead records
		return $this->getAppendedFieldsToLeadResults( $args );
	}

	/**
	 * Retrieves leads results based on passed filters.
	 * Finds field values for a search term and returns corresponding lead results
	 *
	 * @param array $args     Filter options
	 *
	 * @return array
	 * @throws \Exception
	 */
	protected function getLeadsResults( $args ){
		// Purpose of joining tables is being able to search in leadField values as well
		$leadTable = $this->lead()->getTable();
		$leads = Lead::new()->select(
			"{$leadTable}.id",
			"{$leadTable}.source_id",
			"{$leadTable}.content_id",
			"{$leadTable}.content_name",
			"{$leadTable}.created_at",
			"lf.name as fieldName",
			"lf.value as fieldValue"
		)->join( "{$this->leadField()->getTable()} AS lf", "{$leadTable}.id", "=", "lf.lead_id" );

		if( ! empty( $args['dateStart'] ) ){
			$leads->where( "{$leadTable}.created_at", '>=', $args['dateStart'] );
		}

		if( ! empty( $args['dateEnd'] ) ){
			$leads->where( "{$leadTable}.created_at", '<=', $args['dateEnd'] );
		}

		if( ! empty( $args['sources'] ) ){
			$leads->where( "{$leadTable}.source_id", 'in', $args['sources'] );
		}

		if( ! empty( $args['s'] ) ){
			$search = "'%". $args['s'] ."%'";
			$leads->appendRawWhere('AND', "( lf.value like {$search} OR {$leadTable}.content_name like {$search} )");
		}

		if( ! empty( $args['orderBy'] ) && ! empty( $args['order'] ) ){
			$leads->orderBy( $args['orderBy'], $args['order'] );
		}

		$leads = $leads->groupBy("{$leadTable}.id");
		$results = $this->paginate( $leads, $args );

		return $results['leads'] ? [
			'numberOfLeads' => $results['numberOfLeads'],
			'numberOfPages' => $results['numberOfPages'],
			'page' => $args['page'],
			'leads' => $results['leads']->toArray()
		] : [];
	}


	/**
	 * Apply pagination if possible
	 *
	 * @param Lead $leads
	 * @param $args
	 *
	 * @return array|mixed
	 */
	protected function paginate( $leads, $args = [] ){
		$numberOfLeads = $leads ? $leads->count() : 0;
		if ( !empty( $args['perPage'] ) ) {
			$args['page'] = $args['page'] ?? 1;

			if ( $pager = $leads->paginate( $args['perPage'], $args['page'] ) ) {
				$leads = $pager->getResults();
			} else {
				$leads = [];
			}
		} else {
			$leads = $leads ? $leads->findAll()->get() : [];
		}

		return [
			'leads' => $leads,
			'numberOfPages' => ceil( $numberOfLeads / $args['perPage'] ),
			'numberOfLeads' => $numberOfLeads,
		];
	}

	/**
	 * Queries records of leads with specified fields
	 *
	 * @param $columns
	 *
	 * @return Lead
	 * @throws \Exception
	 */
	protected function select( $columns = [] ) {
		$entity  = Lead::new();
		$columns = !empty( $columns ) ? $columns : $entity->getTableColumns();
		return $entity->select( $columns );
	}

	/**
	 * Ensure input arguments are valid
	 *
	 * @param $args
	 */
	protected function ensureValidArgs( &$args ){
		// ensure $args['sources'] exists and is an array
		Arr::ensureItemIsArray( $args, 'sources', [] );

		// ensure $args['columns'] exists and is an array
		Arr::ensureItemIsArray( $args, 'columns', [] );

		// ensure $args['ids'] exists and is an array
		Arr::ensureItemIsArray( $args, 'ids', [] );

		// set defaults
		$args['order']   = $args['order']   ?? 'DESC';
		$args['orderBy'] = $args['orderBy'] ?? 'id';

		// whether to do a joint query for retrieving lead fields or not
		$args['includeFields'] = Data::isTrue( $args['includeFields'] ?? false );
		// whether to skip custom fields for just use know form fields
		$args['skipCustomFields'] = Data::isTrue( $args['skipCustomFields'] ?? false );

		// ensure valid column names are set
		if( empty( $args['columns'] ) ) {
			$args['columns'] = $this->lead()->getTableColumns();
		} else {
			// ensure valid column names
			$args['columns'] = array_intersect( $args['columns'], $this->lead()->getTableColumns() ) ;
		}
	}

	/**
	 * Builds and runs a joint query
	 *
	 * @param array $args  Filter arguments
	 *
	 * @return array
	 * @throws \Exception
	 */
	private function getAppendedFieldsToLeadResults( $args ){

		$LeadTableColumns = $args['columns'];
		$leadFieldTableColumns = $args['fieldNames'] ?? [];
		
		// drop custom lead fields from list of query columns
		if( $args['skipCustomFields'] ){
			$leadFieldTableColumns = array_intersect( $leadFieldTableColumns, LeadFieldRepository::KNOWN_FORM_FIELD_NAMES );
		}
		$leadFieldTableColumns = array_unique( $leadFieldTableColumns );

		// prefix lead columns for joint query
		$columns = array_map( function( $column ){
			return "l.{$column}";
		}, $LeadTableColumns );

		// prefix and add leadField columns for joint query
		foreach( $leadFieldTableColumns as $leadFieldTableColumn ){
			// using sqlRaw to bypass 'tickSqlName' filter
			$columns[] = new SqlRaw("MAX(IF(lf.name = '{$leadFieldTableColumn}', lf.value, NULL)) AS '{$leadFieldTableColumn}'");
		}

		$leads = Lead::new()->select( $columns )
			->as( 'l' )
		    ->join( "{$this->leadField()->getTable()} AS lf", "l.id", "=", "lf.lead_id" )
		    ->where( "l.id", 'IN', $args['ids'] );

		if( ! empty( $args['orderBy'] ) && ! empty( $args['order'] ) ){
			$leads->orderBy( $args['orderBy'], $args['order'] );
		}

		$leads = $leads->groupBy("l.id")->get();

		return $leads ? $leads->toArray() : [];
	}
}
