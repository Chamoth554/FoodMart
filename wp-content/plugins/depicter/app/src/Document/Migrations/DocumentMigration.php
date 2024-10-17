<?php
namespace Depicter\Document\Migrations;

// no direct access allowed
use Depicter\Document\Migrations\DataMigration\BackgroundImage;

if ( ! defined( 'ABSPATH' ) ) {
    die();
}

/**
 * Migration for slider data
 *
 * @package Depicter\Database
 */
class DocumentMigration {

	/**
	 * Migrate Background Image
	 *
	 * @return BackgroundImage
	 */
	public function BackgroundImage(): BackgroundImage{
		return new BackgroundImage();
	}

	public function apply( $slider_content ) {
		$slider_content = $this->BackgroundImage()->migrate( $slider_content );

		return $slider_content;
	}
}
