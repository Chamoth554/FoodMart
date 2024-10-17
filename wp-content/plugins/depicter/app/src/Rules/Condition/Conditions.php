<?php

namespace Depicter\Rules\Condition;

use Averta\Core\Utility\Arr;
use http\Exception;

class Conditions {

	/**
	 * Collection of all Condition instances
	 *
	 * @var array
	 */
	protected $items = [];

	/**
	 * List of slug of all defined conditions
	 *
	 * @var array
	 */
	protected $registeredIDs = [
		'WordPress_Post',
		'WordPress_Page',
		'WordPress_IsArchive',
		'WordPress_StaticPage',
		'WordPress_IsCategory',
		'WordPress_IsTag',
		'WordPress_InCategory',
		'WordPress_HasTag',
		'WordPress_IsAuthor',
		'CPT_IsSingle',
		'CPT_IsArchive',
		'CPT_IsTax',
		'WooCommerce_IsShop',
		'WooCommerce_IsArchive',
		'WooCommerce_Product',
		'WooCommerce_InProductCategory',
		'WooCommerce_HasProductTag',
		'WooCommerce_ByAuthor',
		'WooCommerce_InChildProductCategory',
		'WooCommerce_StaticPage',
		'Audience_Device',
		'Audience_Browser',
		'Audience_Country',
		'Advanced_Cookie',
		'Advanced_Referrer',
		'Advanced_URL'
	];


	/**
	 * List of all defined Condition groups
	 *
	 * @return array
	 */
	public function getGroups(){
		return [
			'WordPress'  => [
				'label'  => __('WordPress', 'depicter' ),
				'items'  => []
			],
			'CPT' => [
				'label'  => __('Custom Post Types', 'depicter' ),
				'items'  => []
			],
			'WooCommerce'=> [
				'label'  => __('WooCommerce', 'depicter' ),
				'items'  => []
			],
			'Audience' => [
				'label'  => __('Audience', 'depicter' ),
				'items'  => []
			],
			'Advanced' => [
				'label'  => __('Advanced', 'depicter' ),
				'items'  => []
			]
		];
	}

	/**
	 * Get dynamic CPT IDs
	 *
	 * @return array
	 */
	public function getDynamicCptIDs() {
		$IDs = [];

		$postTypes = get_post_types([
            'public' => true,
            '_builtin' => false
        ]);
		if ( !empty( $postTypes['product'] ) ) {
			unset( $postTypes['product'] );
		}

		foreach( $postTypes as $postType ) {
			$IDs[] = 'CPT_SingleType|' . $postType;

			$taxonomies = get_taxonomies([
				'object_type' => [ $postType ],
				'public'      => true,
				'show_ui'     => true,
            ], 'object');

			if ( ! empty( $taxonomies ) ) {
				foreach( $taxonomies as $tax ) {
					$IDs[] = 'CPT_HasTerm|' . $tax->name;
				}
			}
		}

		return $IDs;
	}

	/**
	 * List of slug of all defined conditions
	 *
	 * @return array
	 */
	public function getIDs(){
		return Arr::merge( $this->registeredIDs, $this->getDynamicCptIDs() );
	}

	/**
	 * Get all Condition instances
	 *
	 * @return array
	 */
	public function all(){
		return array_values( $this->collect() );
	}

	/**
	 * Get all properties of conditions in an array
	 *
	 * @param bool $inGroups  Whether to return items in plain array or in groups
	 *
	 * @return array
	 */
	public function toArray( $inGroups = false ){

		if( $inGroups ){
			$groups = $this->getGroups();

			foreach( $this->collect() as $conditionSlug => $conditionInstance ){
				[ $groupID, $className ] = explode('_', $conditionSlug );

				if( isset( $groups[ $groupID ]['items'] ) && is_array( $groups[ $groupID ]['items'] ) ){
					$groups[ $groupID ]['items'][] = $conditionInstance->getProperties();
				}
			}
			return $groups;

		} else {
			return array_map( function( Base $item ){
				return $item->getProperties();
			}, $this->all() );
		}
	}

	/**
	 * Get the condition instance by slug
	 *
	 * @param $conditionSlug
	 *
	 * @return Base
	 */
	public function find( $conditionSlug ){
		$param = '';
		[ $groupID, $className ] = explode('_', $conditionSlug );
		if ( strpos( $className, '|' ) ) {
			$param = explode( '|', $conditionSlug )[1];
			$className = explode( '|', $className )[0];
		}

		$fullyQualifiedClassName = "\\Depicter\\Rules\\Condition\\{$groupID}\\{$className}";

		if( class_exists( $fullyQualifiedClassName ) ){
			return ! empty( $param ) ? new $fullyQualifiedClassName( $param ) : new $fullyQualifiedClassName();
		}
		return null;
	}


	/**
	 * Collect defined Condition classes in a list
	 *
	 * @return array
	 */
	protected function collect(){
		if( ! empty( $this->items ) ){
			return $this->items;
		}

		foreach( $this->getIDs() as $conditionSlug ){
			if( $conditionInstance = $this->find( $conditionSlug ) ){
				$this->items[ $conditionSlug ] = $conditionInstance;
			}
		}

		return $this->items;
	}
}
