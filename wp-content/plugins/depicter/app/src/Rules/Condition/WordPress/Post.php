<?php

namespace Depicter\Rules\Condition\WordPress;

use Depicter\Rules\Condition\Base as ConditionBase;

class Post extends ConditionBase {

	/**
	 * @inheritdoc
	 */
	public $slug = 'WordPress_Post';

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
	protected $belongsTo = 'WordPress';

	/**
	 * Tier of this condition
	 *
	 * @var string
	 */
	protected $tier = 'free-user';


	/**
	 * @inheritdoc
	 */
	public function getLabel(){
		return __('A WP Post', 'depicter' );
	}

	/**
	 * @inheritdoc
	 */
	public function getQueryResults(){
		$posts = get_posts([
			'post_type'   => $this->postType,
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
	 * @inheritdoc
	 */
	public function check( $value = null ){
		global $post;

		$value = $value ?? $this->value;

		// if it was not a singular WP page
		if ( is_null( $post ) || ! is_singular() ) {
			$isMet = false;
		} else {
			$isMet = ! empty( $value ) ? in_array( $post->ID, $value ) : is_singular( $this->postType );
		}

		return $this->selectionMode === 'include' ? $isMet : !$isMet;
	}

}
