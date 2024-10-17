<?php
/**
 * Handle checking requirements.
 *
 * @package Depicter
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'depicter_requirements_satisfied' ) ) {

	/**
	 * Whether required php version is available or not.
	 *
	 * @param string $name Project name.
	 * @param string $min Minimum PHP version.
	 *
	 * @return bool
	 */
	function depicter_requirements_satisfied( $name, $min ) {

		if ( version_compare( PHP_VERSION, $min, '>=' ) ) {
		    return depicter_check_opcache( $name );
		}

		add_action(
			'admin_notices',
			function () use ( $name, $min ) {
				$message = __( '%1$s plugin requires PHP version %2$s but current version is %3$s. Please contact your host provider and ask them to upgrade PHP version.', 'depicter' );
				?>
				<div class="notice notice-error">
					<p><?php echo wp_kses( sprintf(
						$message,
						'<strong>' . $name . '</strong>',
						'<strong>' . $min . '</strong>',
						'<strong>' . PHP_VERSION . '</strong>'
					), ['strong' => [] ] ); ?></p>
				</div>
				<?php
			}
		);

		// An incompatible version is already loaded.
		return false;
	}

	/**
	 * Check opcache config
	 *
	 * @param $name
	 *
	 * @return bool
	 */
	function depicter_check_opcache( $name ) {

		set_error_handler(function(){});
		$has_opcache_status = function_exists('opcache_get_status') && opcache_get_status();
		restore_error_handler();

		if ( $has_opcache_status ) {
			if ( ! function_exists('opcache_get_configuration') ){
				return true;
			}
			// Some security settings in OpCache can prevent calling `opcache_get_status()` and `opcache_get_configuration()`
			// To prevent any unwanted warnings, we buffer the output here
			ob_start();
			$config = opcache_get_configuration();
			ob_get_clean();

			if ( empty( $config['directives']['opcache.save_comments'] ) ) {
				add_action(
					'admin_notices',
					function () use ( $name ) {
						$message = __( 'Your website uses OpCache but requires the "opcache.save_comments" option enabled for %1$s plugin to work correctly. Please ask your hosting provider to turn on this setting.', 'depicter' );
?>
	<div class="notice notice-error">
		<p><?php echo wp_kses( sprintf( $message, '<strong>'. $name .'</strong>' ), ['strong' => [] ] ); ?></p>
	</div>
<?php
					}
				);

				return false;
			}
		}

		return true;
	}

}
