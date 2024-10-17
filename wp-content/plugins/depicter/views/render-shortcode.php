<?php
/**
 * Blank canvas.
 *
 * @package Depicter
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// $view_args is required to be passed

$view_defaults = [
	'title' => 'Depicter - Rendering Shortcode',
	'shortcode' => '',
];

$view_args = array_merge( $view_defaults, $view_args );

global $wp_version;
extract( $view_args );

$body_classes[] = 'depicter-render-shortcode';
$body_classes[] = 'wp-version-' . str_replace( '.', '-', $wp_version );

if ( is_rtl() ) {
	$body_classes[] = 'rtl';
}

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title><?php echo esc_html( $title ); ?></title>
</head>
<body class="<?php echo Depicter\Utility\Sanitize::attribute( implode( ' ', $body_classes ) ); ?>" style="background-color:transparent;">
<?php
wp_head();
if ( ! empty( $shortcode ) ) {
	echo do_shortcode( $shortcode );
}
wp_footer();
?>
</body>
</html>
