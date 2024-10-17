<?php

namespace ImageOptimization\Modules\Connect;

use ImageOptimization\Classes\Module_Base;
use ImageOptimization\Modules\Connect\Classes\{
	Data,
	Utils,
};

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Class Module
 */
class Module extends Module_Base {

	/**
	 * Get module name.
	 * Retrieve the module name.
	 * @access public
	 * @return string Module name.
	 */
	public function get_name() {
		return 'connect';
	}

	/**
	 * component_list
	 * @return string[]
	 */
	public static function component_list() : array {
		return [
			'Handler',
		];
	}

	/**
	 * routes_list
	 * @return string[]
	 */
	public static function routes_list() : array {
		return [
			'Authorize',
			'Disconnect',
			'Deactivate',
			'Deactivate_And_Disconnect',
			'Version',
			'Switch_Domain',
		];
	}

	public static function is_connected() : bool {
		return ! ! Data::get_access_token() && Utils::is_valid_home_url();
	}

	public static function is_active() : bool {
		// TODO: Add login to check if the function should be active or not.
		return empty( get_option( 'image_optimizer_client_data' ) );
	}

	public function __construct() {
		$this->register_components();
		$this->register_routes();
	}
}
