<?php

namespace Depicter\Document\Migrations\DataMigration;

interface MigrationInterface
{

	/**
	 * Get slider content and migrate required property then return it
	 *
	 * @param string|object $slider_content
	 *
	 * @return mixed
	 */
	public function migrate( $slider_content );
}
