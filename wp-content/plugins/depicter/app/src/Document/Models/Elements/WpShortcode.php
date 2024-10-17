<?php
namespace Depicter\Document\Models\Elements;

use Averta\Core\Utility\Arr;
use Depicter\Document\Models;
use Depicter\Html\Html;

class WpShortcode extends Models\Element
{

	public function render() {

		$args = $this->getDefaultAttributes();

        $output = '';

        if ( !empty( $this->options->shortcode ) ) {
            $output =  Html::div( $args, do_shortcode( $this->options->shortcode ) );
        }

		return $output . "\n";
	}

    /**
	 * Get list of selector and CSS for element
	 *
	 * @return array
	 * @throws \JsonMapper_Exception
	 */
	public function getSelectorAndCssList(){
		parent::getSelectorAndCssList();

		$this->selectorCssList[ '.' . $this->getSelector() . ' *' ]['default']['box-sizing'] = 'border-box !important';
        if ( ! empty( $this->options->overflowHidden ) ) {
		    $this->selectorCssList[ '.' . $this->getSelector() ]['default']['overflow'] = 'hidden';
        }

		return $this->selectorCssList;
	}
}
