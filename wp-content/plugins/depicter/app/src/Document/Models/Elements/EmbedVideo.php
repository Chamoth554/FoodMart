<?php
namespace Depicter\Document\Models\Elements;

use Averta\Core\Utility\Arr;
use Averta\Core\Utility\Embed;
use Depicter\Document\Models;
use Depicter\Html\Html;

class EmbedVideo extends Models\Element{

	public function render() {

		$videoUrl = !empty( $this->options->source ) ? $this->options->source : '';

		$elementsAttrs = [
			'data-type' => 'video'
		];

		if( isset( $this->options->autoPlay ) ){
			$elementsAttrs['data-autoplay'] = $this->options->autoPlay ? "true" : "false";
		}
		if( isset( $this->options->autoPause ) ){
			$elementsAttrs['data-auto-pause'] = $this->options->autoPause ? "true" : "false";
		}
		if( !empty( $this->options->muted ) ){
			$elementsAttrs['data-muted'] = $this->options->muted ? "true" : "false";
		} else if( !empty( $this->options->mute ) ){
			$elementsAttrs['data-muted'] = $this->options->mute ? "true" : "false";
		}

		$playerType = [ 'native', 'youtube', 'vimeo' ];
		$elementsAttrs['data-player-type'] = isset( $this->options->type ) && in_array( $this->options->type, $playerType ) ? $this->options->type : "native";

		if( isset( $this->options->goNextSection ) ){
			$elementsAttrs['data-goto-next'] = $this->options->goNextSection ? "true" : "false";
		}
		if( isset( $this->options->loop ) ){
			$elementsAttrs['data-loop'] = $this->options->loop ? "true" : "false";
		} else {
			$elementsAttrs['data-loop'] = "false";
		}

		if( !empty( $this->options->related ) ){
			$elementsAttrs['data-limit-related'] = $this->options->related ? "true" : "false";
		}

		if( !empty( $this->options->startingTime ) ){
			$elementsAttrs['data-starting-time'] = $this->options->startingTime ?? "0";
		}

		if( !empty( $this->options->endingTime ) ){
			$elementsAttrs['data-ending-time'] = $this->options->endingTime ?? "null";
		}

		$elementsAttrs['data-controls'] = esc_attr( $this->options->controls ) ?? "true";

		$elementsAttrs['data-video-src'] = $this->options->source;
		if ( $elementsAttrs['data-player-type'] == 'youtube' ) {
			$elementsAttrs['data-video-poster'] = Embed::getYouTubePosterUrl( $this->options->source );
		}

		$args = Arr::merge( $this->getDefaultAttributes(), $elementsAttrs );

		return Html::div( $args );
	}
}


