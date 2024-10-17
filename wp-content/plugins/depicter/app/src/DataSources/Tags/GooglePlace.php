<?php

namespace Depicter\DataSources\Tags;

use Averta\WordPress\Utility\Plugin;

/**
 * Asset Group for WooCommerce
 *
 * {{{module->slug|func}}}
 * {{{module->slug|func('a','b')}}}
 *
 */
class GooglePlace extends TagBase implements TagInterface {
	/**
	 *  Asset group ID
	 */
	const ASSET_GROUP_ID = 'googlePlace';

	/**
	 * Get label of asset group
	 *
	 * @return string
	 */
	public function getName(){
		return __( "Place", 'depicter' );
	}

	/**
	 * Get list of assets in this group
	 *
	 * @param array  $args
	 *
	 * @return array
	 */
	public function getAssetBlocks( array $args = [] ){

		return [
			[
				'id'    => 'placeName',
				'title' => __( 'Place Name', 'depicter' ),
				'previewOptions' => [
					"size" => 50,
					'multiline' => false,
					'textSize' => 'regular',
					'badge' => null
				],
				'type'  => 'dynamicText',
				'func'  => null,
				'payload' => [
					'source' => $this->wrapCurly( 'googlePlace->placeName' )
				]
			],
			[
				'id'    => 'placeAverageRating',
				'title' => __( 'Place Average Rating', 'depicter' ),
				'previewOptions' => [
					"size" => 50,
					'multiline' => false,
					'textSize' => 'regular',
					'badge' => null
				],
				'type'  => 'dynamicText',
				'func'  => null,
				'payload' => [
					'source' => $this->wrapCurly( 'googlePlace->placeAverageRating' )
				]
			],
			[
				'id'    => 'placeRatingCount',
				'title' => __( 'Place Rating Count', 'depicter' ),
				'previewOptions' => [
					"size" => 50,
					'multiline' => false,
					'textSize' => 'regular',
					'badge' => null
				],
				'type'  => 'dynamicText',
				'func'  => null,
				'payload' => [
					'source' => $this->wrapCurly( 'googlePlace->placeRatingCount' )
				]
			]
		];

	}

	/**
	 * Get value of tag slug
	 *
	 * @param string $tagName  Tag name
	 * @param array  $args     Arguments of current document section
	 *
	 * @return string|null
	 */
	public function getSlugValue( string $tagName = '', array $args = [] ){

		if ( empty( $args['id'] ) ) {
			return $tagName;
		}

		$result = $tagName;

		if ( ! empty( $args[ $tagName ] ) ) {
			$result = $args[ $tagName ];
		}

		return $result;
	}
}
