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

<script type="text/javascript">
    (function () {
        var init = function () {
            document.querySelectorAll(".depicter-renew-notice-wrapper").forEach(function( element ) {
                if (!element.dataset.hasEvent) {
                    element.dataset.hasEvent = true;
                    element
                    .querySelector(".close-icon")
                    .addEventListener("click", function() {
                        element.remove();
                    });
                }
            });
        };

        if (document.readyState === "complete") {
            init();
        } else {
            document.addEventListener("DOMContentLoaded", init);
        }
    })();
</script>

<style>
.depicter-renew-notice-wrapper{
    display: flex !important;
    align-items: flex-start !important;
    background-color: #935C00E5 !important;
    border-radius: 5px !important;
    border: 2px solid #FEB130;
    padding: 10px !important;
    box-shadow: 5px 10px 30px #00000026 !important;
    max-width: 450px !important;
}

.depicter-renew-notice-wrapper span {
    color: #fff !important;
    font-size: 12px !important;
    line-height: 17px !important;
    font-weight: 600 !important;
    font-family: sans-serif;
}

.depicter-renew-notice-wrapper span.notice-icon {
    display: inline-block;
    width: 34px;
    height: 17px;
    background-image: url('data:image/svg+xml, %3Csvg%20width%3D%2217%22%20height%3D%2217%22%20viewBox%3D%220%200%2017%2017%22%20fill%3D%22none%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%3E%0A%3Cpath%20fill-rule%3D%22evenodd%22%20clip-rule%3D%22evenodd%22%20d%3D%22M8.5%2017C13.1944%2017%2017%2013.1944%2017%208.5C17%203.80558%2013.1944%200%208.5%200C3.80558%200%200%203.80558%200%208.5C0%2013.1944%203.80558%2017%208.5%2017Z%22%20fill%3D%22black%22%20fill-opacity%3D%220.4%22%2F%3E%0A%3Cpath%20fill-rule%3D%22evenodd%22%20clip-rule%3D%22evenodd%22%20d%3D%22M3.01564%207.3584H4.29239H5.62965L7.70674%2013.7672L7.6892%2013.7904L3.01564%207.3584ZM6.39258%207.35859H8.49197L8.48482%207.35059H8.52227L8.51513%207.35859H10.6018L8.4976%2013.73L8.46957%2013.7674L6.39258%207.35859ZM11.3648%207.35841H12.7149H13.9789L9.25986%2013.7298L11.3648%207.35841ZM12.7147%206.66425H11.3645L11.367%206.65625H9.28516L11.651%204L11.7021%204.05747L11.7026%204.05888L13.9993%206.63635L13.9786%206.66426L12.7147%206.66425ZM6.23047%204.00801H8.31309L8.3107%204H9.66073H10.9247L10.9455%204.02792L8.64816%206.60679L8.59718%206.66425L6.23047%204.00801ZM4.29191%206.66425H3.01515L3%206.64424L5.30362%204.05822L5.35546%204L7.72141%206.65701H5.62678L5.62917%206.66426L4.29191%206.66425Z%22%20fill%3D%22url%28%23paint0_linear_3193_20061%29%22%2F%3E%0A%3Cdefs%3E%0A%3ClinearGradient%20id%3D%22paint0_linear_3193_20061%22%20x1%3D%228.49966%22%20y1%3D%224%22%20x2%3D%228.49966%22%20y2%3D%2213.7904%22%20gradientUnits%3D%22userSpaceOnUse%22%3E%0A%3Cstop%20stop-color%3D%22%23EEFF00%22%2F%3E%0A%3Cstop%20offset%3D%221%22%20stop-color%3D%22%23FFD500%22%2F%3E%0A%3C%2FlinearGradient%3E%0A%3C%2Fdefs%3E%0A%3C%2Fsvg%3E');
    border-radius: 50% !important;
    margin-right: 7px !important;
    background-repeat: no-repeat;
}

.depicter-renew-notice-wrapper a {
    color: #fff !important;
    text-decoration: underline !important;
}

.depicter-renew-notice-wrapper .close-icon {
    width: 10px !important;
    height: 10px !important;
    margin-left: 10px !important;
    cursor: pointer !important;
}

.depicter-renew-notice-wrapper .close-icon:before,
    .depicter-renew-notice-wrapper .close-icon:after {
    width: 2px !important;
    height: 10px !important;
    background-color: #fff !important;
    display: block !important;
    content: " " !important;
    position: absolute !important;
}

.depicter-renew-notice-wrapper .close-icon:before {
    transform: rotate(45deg) !important;
}
.depicter-renew-notice-wrapper .close-icon:after {
    transform: rotate(-45deg) !important;
}

</style>

<div class="depicter-renew-notice-wrapper">
    <span class="notice-icon"></span>
<?php
        echo '<span>';
        echo sprintf(
			esc_html__('Your Depicter Pro subscription has expired. Visible only to site admins. %sRenew%s to remove this message and restore functionality.', 'depicter' ),
			'<a href="' . esc_url( $renew_url ) . '" target="_blank">', '</a>' ) . '<br>';
        echo '</span><span class="close-icon"></span>';
?>
</div>
