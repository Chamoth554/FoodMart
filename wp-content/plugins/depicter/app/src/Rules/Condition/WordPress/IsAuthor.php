<?php

namespace Depicter\Rules\Condition\WordPress;

use Averta\Core\Utility\Arr;
use Depicter\Rules\Condition\Base as ConditionBase;

class IsAuthor extends ConditionBase
{
	/**
	 * @inheritdoc
	 */
	public $slug = 'WordPress_IsAuthor';

	/**
	 * @inheritdoc
	 */
	public $control = 'remoteMultiSelect';

	/**
	 * @inheritdoc
	 */
	protected $belongsTo = 'WordPress';

	/**
	 * @inheritdoc
	 */
	protected $queryable = true;

	/**
	 * @inheritdoc
	 */
	public function getLabel(): ?string{
		return __('WP Author Page', 'depicter' );
	}

	/**
	 * @inheritDoc
	 */
	public function getDescription(): ?string{
		return __( 'When any Author page is being displayed.', 'depicter' );
	}

	/**
	 * @inheritdoc
	 */
	public function getQueryResults(){
		$authors = get_users([
			'has_published_posts' => true,
            'fields' => [ 'ID', 'display_name' ]
		]);

		return array_map( function( $author ){
			return [
				'label' => $author->display_name,
				'value' => $author->ID
			];
		}, $authors );
	}

	/**
	 * @inheritdoc
	 */
	public function check( $value = null ): bool{

		$value = $value ?? $this->value;

		$isIncluded = ! empty( $value ) ? is_author( $value ) : is_author();

		return $this->selectionMode === 'include' ? $isIncluded : ! $isIncluded;
	}
}
