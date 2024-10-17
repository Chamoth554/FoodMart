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
class GooglePlaceReview extends TagBase implements TagInterface {
	/**
	 *  Asset group ID
	 */
	const ASSET_GROUP_ID = 'googlePlaceReview';

	/**
	 * Get label of asset group
	 *
	 * @return string
	 */
	public function getName(){
		return __( "Review", 'depicter' );
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
				'id'    => 'authorName',
				'title' => __( 'Author Name', 'depicter' ),
				'previewOptions' => [
					"size" => 50,
					'multiline' => false,
					'textSize' => 'regular',
					'badge' => null
				],
				'type'  => 'dynamicText',
				'func'  => null,
				'payload' => [
					'source' => $this->wrapCurly( 'googlePlaceReview->author.name' )
				]
			],
            [
				'id'    => 'rating',
				'title' => __( 'Rating', 'depicter' ),
				'previewOptions' => [
					"size" => 50,
					'multiline' => false,
					'textSize' => 'regular',
					'badge' => null
				],
				'type'  => 'dynamicRating',
				'func'  => null,
				'payload' => [
					'source' => $this->wrapCurly( 'googlePlaceReview->rating' )
				]
			],
            [
				'id'    => 'content',
				'title' => __( 'Review Text', 'depicter' ),
				'previewOptions' => [
					"size" => 50,
					'multiline' => false,
					'textSize' => 'regular',
					'badge' => null
				],
				'type'  => 'dynamicText',
				'func'  => null,
				'payload' => [
					'source' => $this->wrapCurly( 'googlePlaceReview->content' )
				]
			],
            [
				'id'    => 'excerpt',
				'title' => __( 'Review Trimmed Text', 'depicter' ),
				'previewOptions' => [
					"size" => 50,
					'multiline' => false,
					'textSize' => 'regular',
					'badge' => null
				],
				'type'  => 'dynamicText',
				'func'  => null,
				'payload' => [
					'source' => $this->wrapCurly( 'googlePlaceReview->excerpt' )
				]
			],
            [
				'id'    => 'date',
				'title' => __( 'Review Time', 'depicter' ),
				'previewOptions' => [
					"size" => 50,
					'multiline' => false,
					'textSize' => 'regular',
					'badge' => null
				],
				'type'  => 'dynamicText',
				'func'  => null,
				'payload' => [
					'source' => $this->wrapCurly( 'googlePlaceReview->date' )
				]
			],
            [
				'id'    => 'relativeDate',
				'title' => __( 'Review Relative Time', 'depicter' ),
				'previewOptions' => [
					"size" => 50,
					'multiline' => false,
					'textSize' => 'regular',
					'badge' => null
				],
				'type'  => 'dynamicText',
				'func'  => null,
				'payload' => [
					'source' => $this->wrapCurly( 'googlePlaceReview->relativeDate' )
				]
			],
            [
				'id'    => 'authorPhoto',
				'title' => __( 'Author Photo', 'depicter' ),
				'previewOptions' => [
					"size" => 50,
					'badge' => null
				],
				'type'  => 'dynamicMedia',
				'sourceType' => 'image',
				'func'  => null,
				'payload' => [
					'source' => $this->wrapCurly( 'googlePlaceReview->authorPhoto|toImage' ),
					'src'    => $this->wrapCurly( 'googlePlaceReview->author.src' )
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

		if ( $tagName == 'author.name' ) {
			$result = $args['author']['name'];
		} else if ( $tagName == 'author.photo' ) {
			$result = $args['authorPhoto'];
		} elseif ( ! empty( $args[ $tagName ] ) ) {
			$result = $args[ $tagName ];
		}

		return $result;
	}
}
