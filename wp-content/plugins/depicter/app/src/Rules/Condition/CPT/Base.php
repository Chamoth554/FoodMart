<?php

namespace Depicter\Rules\Condition\CPT;
use Depicter\Rules\Condition\Base as BaseCondition;
class Base extends BaseCondition
{

	/**
	 * @var \WP_Post_Type[]
	 */
	protected $availablePostTypes;

	public function __construct(){
		$this->availablePostTypes = get_post_types([
            'public' => true,
            '_builtin' => false
        ], 'object');

		if ( !empty( $this->availablePostTypes['product'] ) ) {
			unset( $this->availablePostTypes['product'] );
		}
	}
}
