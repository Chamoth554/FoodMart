<?php
/**
 * Blank canvas.
 *
 * @package Depicter
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

extract( $view_args );
$renew_url = ! empty( $subscription_id ) ? "https://my.depicter.com/subscriptions/" . $subscription_id : "https://my.depicter.com";
?>
<style>
.dep-renew-subscription-notice {
	padding: 10px !important;
	border: none;
}
.dep-renew-subscription-notice .wrap {
	padding: 17px 42px 24px;
	margin: 0;
	background: #FFF7F7 url(<?php echo \Depicter::core()->assets()->getUrl() . '/resources/images/renew.png';?>);
	background-size: 200px;
	background-repeat: no-repeat;
	background-position: calc(100% + 15px) -17px;
	border-left: 4px solid #C03534;
}
.dep-renew-subscription-notice h3 {
	font-size: 26px;
	font-weight: 700;
	line-height: 35px;
	letter-spacing: -0.04em;
	color: #070707;
	margin: 0;
	font-family: sans-serif;
}
.dep-renew-subscription-notice p {
	font-size: 17px;
	font-weight: 400;
	line-height: 23px;
	letter-spacing: -0.04em;
	color: #070707;
	margin-top: 5px;
	margin-bottom: 0;
	max-width: 700px;
	font-family: sans-serif;
}
.dep-renew-subscription-notice p code {
	background-color: #070707;
	color: #fff;
	font-size: 17px;
	font-weight: 600;
	line-height: 23.15px;
	letter-spacing: -0.04em;
	text-align: left;
	border-radius: 5px;
	padding: 2px 10px;
}
.dep-renew-subscription-notice a {
	display: inline-block;
	text-decoration: none;
	color: #fff;
	margin-top: 12px;
	padding: 8px 12px;
	font-weight: 700;
	font-size: 14px;
	border-radius: 5px;
	background-color: #DC4645;
	font-family: sans-serif;

}
</style>
<div class="notice dep-renew-subscription-notice is-dismissible">
	<div class="wrap">
		<h3><?php esc_html_e( 'Your Depicter Pro subscription has expired', 'depicter' ); ?></h3>
		<p><?php echo sprintf( esc_html__( 'Renew now to restore full functionality. Enjoy a %s discount with code: %s', 'depicter' ), '20%', '<code>RENEW20</code>'); ?></p>
		<a href="<?php echo esc_url( $renew_url );?>" target="_blank"><?php esc_html_e( 'Upgrade Now', 'depicter' ); ?></a>
	</div>
</div>
