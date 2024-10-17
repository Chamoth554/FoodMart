<?php
namespace Depicter\Database;

use Averta\WordPress\Database\Migration as BaseMigration;

// no direct access allowed
if ( ! defined('ABSPATH') ) {
    die();
}

/**
 * Migration for custom tables
 *
 * @package Depicter\Database
 */
class Migration extends BaseMigration {

	/**
	 * Current tables migration version
	 */
	const MIGRATION_VERSION = "0.4.6";

	/**
	 * Prefix for version option name
	 */
	const VERSION_PREFIX = "depicter_";

	/**
	 * Table prefix
	 */
	const TABLE_PREFIX = 'depicter_';

	/**
	* Table names
	*/
	protected $table_names = [ 'documents', 'options', 'meta', 'leads', 'lead_fields' ];


	/**
	 * Create documents table
	 *
	 * @since 1.0
	 * @return null
	 */
	protected function create_table_documents() {

		$sql_create_table = "CREATE TABLE {$this->documents} (
            id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            name  text NOT NULL,
            slug  varchar(100) NOT NULL,
            type  varchar(50) NOT NULL DEFAULT 'custom',
            author bigint(20) unsigned NOT NULL DEFAULT '0',
            sections_count mediumint NOT NULL DEFAULT '0',
            created_at  datetime DEFAULT NULL,
            modified_at datetime DEFAULT NULL,
            thumbnail varchar(255) NOT NULL,
            content longtext NOT NULL,
            status varchar(20) NOT NULL DEFAULT 'draft',
            parent bigint(20) unsigned NOT NULL DEFAULT '0',
            password varchar(255) NOT NULL DEFAULT '',
            PRIMARY KEY  (id),
            KEY created_at (created_at),
            KEY slug (slug)
        ) {$this->charset_collate()};\n";

		$this->dbDelta( $sql_create_table );
	}


	/**
	 * Create options table
	 *
	 * @since 1.0
	 * @return null
	 */
	public function create_table_options() {

		$sql_create_table = "CREATE TABLE {$this->options} (
            id mediumint unsigned NOT NULL AUTO_INCREMENT,
            option_name varchar(191) NOT NULL DEFAULT '',
            option_value text NOT NULL DEFAULT '',
            PRIMARY KEY  (id),
            UNIQUE KEY option_name (option_name)
        ) {$this->charset_collate()};\n";

	 	$this->dbDelta( $sql_create_table );
	}

	/**
	 * Create meta table
	 *
	 * @since 1.0
	 * @return null
	 */
	public function create_table_meta() {

		$sql_create_table = "CREATE TABLE {$this->meta} (
            id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            relation varchar(30) NOT NULL DEFAULT '',
            relation_id bigint(20) NOT NULL,
            meta_key varchar(30) NOT NULL DEFAULT '',
            meta_value text NOT NULL DEFAULT '',
            PRIMARY KEY  (id)
        ) {$this->charset_collate()};\n";

		$this->dbDelta( $sql_create_table );
	}

	/**
	 * Create meta table
	 *
	 * @since 1.0
	 * @return null
	 */
	public function create_table_leads() {

		global $wpdb;

		$sql_create_table = "CREATE TABLE {$this->leads} (
            id bigint(20) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
            source_id bigint(20) unsigned NOT NULL,
			content_id bigint(20) NOT NULL,
			content_name varchar(150) NOT NULL,
            created_at  datetime DEFAULT NULL,
            FOREIGN KEY (source_id) REFERENCES " . $wpdb->prefix . self::TABLE_PREFIX . "documents(id)
        ) {$this->charset_collate()};\n";

		$this->dbDelta( $sql_create_table );
	}

	/**
	 * Create meta table
	 *
	 * @since 1.0
	 * @return null
	 */
	public function create_table_lead_fields() {

		global $wpdb;

		$sql_create_table = "CREATE TABLE {$this->lead_fields} (
            id bigint(20) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
            lead_id bigint(20) unsigned NOT NULL,
			name varchar(255) NOT NULL,
            type varchar(50) NOT NULL DEFAULT '',
			value text NOT NULL DEFAULT '',
            created_at  datetime DEFAULT NULL,
			updated_at  datetime DEFAULT NULL,
            FOREIGN KEY (lead_id) REFERENCES " . $wpdb->prefix . self::TABLE_PREFIX . "leads(id) ON DELETE CASCADE
        ) {$this->charset_collate()};\n";

		$this->dbDelta( $sql_create_table );
	}

}


