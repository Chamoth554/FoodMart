<?php
namespace Depicter\Front;


use Depicter\Html\Html;

class Symbols
{

    private $symbols = [];

	private $clipPathIDs = [];

    /**
     * Add symbol id to symbols list
     *
     * @param string $symbolID
     * @return void
     */
    public function add( $symbolID ) {
        if ( !in_array( $symbolID, $this->symbols ) ) {
	        $this->symbols[] = $symbolID;
        }
    }

	/**
	 * Add symbol id to symbols list
	 *
	 * @param string $symbolID
	 * @return void
	 */
	public function addClipPath( $clipPathID ) {
		if ( !in_array( $clipPathID, $this->clipPathIDs ) ) {
			$this->clipPathIDs[] = $clipPathID;
		}
	}

	/**
	 * Render registered svg symbols
	 *
	 * @return string|\TypeRocket\Html\Html
	 */
    public function render() {
		$symbolsContent = '';

		$clipPathContent = '';
        if ( !empty( $this->symbols ) ) {
            foreach ( $this->symbols as $key => $symbolID ) {
            	if ( file_exists( DEPICTER_PLUGIN_PATH .'/resources/scripts/svg-symbols/' . $symbolID . '.svg' ) ) {
            		$symbolsContent .= \Depicter::storage()->filesystem()->read( DEPICTER_PLUGIN_PATH .'/resources/scripts/svg-symbols/' . $symbolID . '.svg' );
	            }
            }

            $symbolsContent = Html::el('svg', [ 'xmlns' => "http://www.w3.org/2000/svg" ], $symbolsContent );
        }

	    if ( !empty( $this->clipPathIDs ) ) {
		    foreach ( $this->clipPathIDs as $key => $clipPathID ) {
			    if ( file_exists( DEPICTER_PLUGIN_PATH .'/resources/scripts/svg-symbols/clipPaths/' . $clipPathID . '.svg' ) ) {
				    $clipPathContent .= \Depicter::storage()->filesystem()->read( DEPICTER_PLUGIN_PATH .'/resources/scripts/svg-symbols/clipPaths/' . $clipPathID . '.svg' ). "\n";
			    }
		    }

			$clipPathContent = "\n" .Html::el('defs', [], "\n" . $clipPathContent );
		    $clipPathContent = Html::el('svg', [
				//'xmlns' => "http://www.w3.org/2000/svg",
			    'width' => '0',
			    'height' => '0'
		    ], $clipPathContent . "\n" );
	    }

        return $symbolsContent . "\n". $clipPathContent;
    }
}
