<?php

namespace Depicter\Document\Models\Traits;

use Depicter\Document\CSS\Breakpoints;

trait HoverAbleStyleTrait {

	/**
	 * @var array
	 */
	protected $hoverEnabled = [];

	public function setHoverStatus( $hoverEnabledList ){
		$this->hoverEnabled = (array) $hoverEnabledList;
		return $this;
	}

	public function inHoverState(){
		return ! empty( $this->hoverEnabled );
	}

	public function isHoverEnabled( $breakpoint = 'default' ){
		return !empty( $this->hoverEnabled[$breakpoint] );
	}

	public function isHoverDisabled( $breakpoint = 'default' ){
		return isset( $this->hoverEnabled[$breakpoint] ) && ! $this->hoverEnabled[$breakpoint];
	}

}


