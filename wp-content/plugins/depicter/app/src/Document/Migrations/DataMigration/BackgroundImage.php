<?php
namespace Depicter\Document\Migrations\DataMigration;

use Averta\WordPress\Utility\JSON;

class BackgroundImage implements MigrationInterface {

	public function migrate( $slider_content ) {

		$slider_data = JSON::isJson( $slider_content ) ? JSON::decode( $slider_content, true ) : JSON::decode( JSON::encode( $slider_content ), true );
		$backgrounds = [];
		if ( ! empty( $slider_data['sections'] ) ) {
			foreach ( $slider_data['sections'] as $section_key => $section ) {
				if ( ! empty( $section['background']['image']['src'] ) ) {
					if ( is_string( $section['background']['image']['src'] ) ) {
						$backgrounds[ $section['background']['image']['src'] ] = JSON::encode( $section['background'], JSON_FORCE_OBJECT );
					}
				}
			}
		}

		if ( ! empty( $backgrounds ) ) {
		
			$modified_data = JSON::isJson( $slider_content ) ? $slider_content : JSON::encode( $slider_content );
			foreach( $backgrounds as $backgroundID => $background ) {
				$new_background = str_replace( '"src":"' . $backgroundID .'"', '"src":{"default":"' . $backgroundID .'"}', $background );
				$modified_data = str_replace( $background, $new_background, $modified_data );
			}

			if ( JSON::isJson( $slider_content ) ) {
				return $modified_data;
			} else {
				return JSON::decode( $modified_data, false );
			}
		}

		return $slider_content;
	}
}
