<?php

namespace Depicter\Rules\Condition\WooCommerce;

use Depicter\Rules\Condition\WordPress\Post;

class Product extends Post
{
	/**
	 * @inheritdoc
	 */
	public $slug = 'WooCommerce_Product';

	/**
	 * @inheritdoc
	 */
	protected $belongsTo = 'WooCommerce';

	/**
	 * @inheritdoc
	 */
	protected $postType = 'product';

	/**
	 * Tier of this condition
	 *
	 * @var string
	 */
	protected $tier = 'pro-user';

	/**
	 * @inheritdoc
	 */
	public function getLabel(): ?string{
		return __('Single Product', 'depicter' );
	}
}
