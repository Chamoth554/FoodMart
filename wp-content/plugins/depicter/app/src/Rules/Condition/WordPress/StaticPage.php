<?php

namespace Depicter\Rules\Condition\WordPress;

use Averta\Core\Utility\Arr;
use Depicter\Rules\Condition\Base as ConditionBase;

class StaticPage extends ConditionBase
{
	/**
	 * @inheritdoc
	 */
	public $slug = 'WordPress_StaticPage';

	/**
	 * @inheritdoc
	 */
	public $control = 'multiSelect';

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
	public function getLabel(): ?string{
		return __('WP Static Pages', 'depicter' );
	}

	/**
	 * @inheritDoc
	 */
	public function getControlOptions(){
		$options = parent::getControlOptions();

		return Arr::merge( $options, [ 'options' => [
			[
				'label' => __( 'Home Page', 'depicter' ),
				'value' => 'is_home'
			],
			[
				'label' => __( '404 Page', 'depicter' ),
				'value' => 'is_404'
			],
			[
				'label' => __( 'Search Page', 'depicter' ),
				'value' => 'is_search'
			],
			[
				'label' => __( 'Blog Page', 'depicter' ),
				'value' => 'is_blog'
			],
			[
				'label' => __( 'Privacy Policy page', 'depicter' ),
				'value' => 'is_privacy_policy'
			]
		]]);
	}

	/**
	 * @inheritdoc
	 */
	public function check( $value = null ): bool{
		$isIncluded = false;

		$value = $value ?? $this->value ?? $this->defaultValue;

		if( !empty( $value ) && is_array( $value ) ){
			foreach( $value as $page ){
				if( $page == 'all' ){
					$isIncluded = is_home() || is_404() || is_search() || $this->is_blog() || is_privacy_policy();
				} elseif( $page == 'is_home' ) {
					$isIncluded = is_front_page();
				} elseif( $page == 'is_404' ) {
					$isIncluded = is_404();
				} elseif( $page == 'is_search' ) {
					$isIncluded = is_search();
				} elseif( $page == 'is_blog' ) {
					$isIncluded = $this->is_blog();
				} elseif( $page == 'is_privacy_policy' ) {
					$isIncluded = is_privacy_policy();
				}

				if( $isIncluded ){
					break;
				}
			}

		}

		return $this->selectionMode === 'include' ? $isIncluded : ! $isIncluded;
	}

	/**
	 * Check if page is blog or not
	 *
	 * @return bool
	 */
	public function is_blog(): bool{
		return ( is_archive() || is_author() || is_category() || is_tag() || is_home() ) && 'post' == get_post_type();
	}
}
