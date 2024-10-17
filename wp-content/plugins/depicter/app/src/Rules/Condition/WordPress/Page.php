<?php

namespace Depicter\Rules\Condition\WordPress;

class Page extends Post {

	/**
	 * @inheritdoc
	 */
	public $slug = 'WordPress_Page';

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
	 * Post type
	 *
	 * @var string
	 */
	protected $postType = 'page';

	/**
	 * @inheritdoc
	 */
	public function getLabel(){
		return __('A WP Page', 'depicter' );
	}

}
