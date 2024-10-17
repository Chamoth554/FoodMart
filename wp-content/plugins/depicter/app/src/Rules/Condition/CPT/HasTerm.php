<?php

namespace Depicter\Rules\Condition\CPT;

use Depicter\Rules\Condition\Base as ConditionBase;

class HasTerm extends ConditionBase {

	/**
	 * @inheritdoc
	 */
	public $slug = 'CPT_HasTerm';

	/**
	 * @inheritdoc
	 */
	public $control = 'remoteMultiSelect';

	/**
	 * @inheritdoc
	 */
	protected $queryable = true;

	/**
	 * @inheritdoc
	 */
	protected $belongsTo = 'CPT';

	/**
	 * @var \WP_Taxonomy
	 */
	protected $tax;

	public function __construct( string $tax = '' ){
		if ( ! empty( $tax ) ) {
			$this->slug = $this->slug . '|' . $tax;
			$this->setTaxonomy();
		}
	}

	/**
	 * Set taxonomy object
	 *
	 * @return void
	 */
	public function setTaxonomy(){
		if ( ! strpos( $this->slug, '|' ) ) {
			return;
		}

		$tax = explode( '|', $this->slug )[1];
		$this->tax = get_taxonomy( $tax );
	}

	/**
	 * @inheritdoc
	 */
	public function getLabel(): ?string{
		return $this->tax->label;
	}

	/**
	 * @inheritdoc
	 */
	public function getQueryResults(){
		$this->setTaxonomy();

		$terms = get_terms([
            'taxonomy' => $this->tax->name,
            'hide_empty' => false,
            'orderby' => 'name',
            'order' => 'ASC',
        ]);

		return array_map( function( $term ){
			return [
				'label' => $term->name,
				'value' => $term->term_id
			];
		}, $terms );
	}

	/**
	 * @inheritdoc
	 */
	public function check( $value = null ): bool{
		global $post;

		if ( is_null( $post ) ) {
			return false;
		}

		$this->setTaxonomy();

		$value = $value ?? $this->value;

		$isIncluded = empty( $value ) || $this->hasTerm( $post, $value );

		return $this->selectionMode === 'include' ? $isIncluded : !$isIncluded;
	}

	/**
	 * @param \WP_Post $post
	 * @param string   $tax
	 * @param array    $value
	 *
	 * @return bool
	 */
	public function hasTerm( \WP_Post $post, array $value ): bool{

		if ( ! $this->tax || $post->post_type != $this->tax->object_type[0] ) {
			return false;
		}

		if ( empty( $value ) ) {
			return true;
		}

		foreach( $value as $termID ){
			$result = $termID == 'all' || has_term( $termID, $this->tax->name );

			if( $result ){
				return true;
			}
		}

		return false;
	}

}
