<?php

namespace Depicter\Document\Models\Elements;

use Depicter\Document\Models\Element;
use Depicter\Html\Html;

class Icon extends Element
{
	/**
	 * @throws \JsonMapper_Exception
	 */
	public function render(){
		$args = $this->getDefaultAttributes();

		return Html::div( $args, $this->options->content );
	}
}
