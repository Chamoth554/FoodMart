<?php

namespace Depicter\Rules\Condition\CPT;

use Depicter\Rules\Condition\Base as ConditionBase;

class SingleType extends ConditionBase {

	/**
	 * @inheritdoc
	 */
	public $slug = 'CPT_SingleType';

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
	 * @param string $postType
	 */
	public function __construct( string $postType = '' ){
		if ( !empty( $postType ) ) {
			$this->slug = $this->slug . '|' . $postType;
			$this->setPostType();
		}
	}

	/**
	 * @inheritdoc
	 */
	public function getLabel(){
		return sprintf( __( "%s Page", 'depicter' ), $this->postType->labels->singular_name ?? $this->postType  );
	}

	/**
	 * @inheritdoc
	 */
	public function getQueryResults(){
		$posts = get_posts([
           'post_type'   => $this->postType->name ?? $this->postType,
           'numberposts' => $this->maxOptionsNumber,
           'fields'      => [ 'ids', 'post_title']
       ]);

		return array_map( function( $post ){
			return [
				'label' => $post->post_title,
				'value' => $post->ID
			];
		}, $posts );
	}

	/**
	 * Set post type as object
	 *
	 * @return void
	 */
	public function setPostType(){
		if ( ! strpos( $this->slug, '|' ) ) {
			return;
		}

		$postType = explode( '|', $this->slug )[1];
		$this->postType = get_post_type_object( $postType );
	}

	/**
	 * @inheritdoc
	 */
	public function check( $value = null ){
		global $post;

		$this->setPostType();

		if ( is_null( $post ) || is_null( $this->postType ) || $post->post_type != $this->postType->name ) {
			return false;
		}

		$value = $value ?? $this->value;

		$isIncluded = ! empty( $value ) ? in_array( $post->ID, $value ) : is_singular($this->postType->name );

		return $this->selectionMode === 'include' ? $isIncluded : !$isIncluded;
	}

}
