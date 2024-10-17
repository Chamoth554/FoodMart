<?php
/**
 * Added Omega Page. */

/**
 * Add a new page under Appearance
 */
function grocery_shopping_store_menu()
{
  add_theme_page(__('Omega Options', 'grocery-shopping-store'), __('Omega Options', 'grocery-shopping-store'), 'edit_theme_options', 'grocery-shopping-store-theme', 'grocery_shopping_store_page');
}
add_action('admin_menu', 'grocery_shopping_store_menu');

// Add Getstart admin notice
function grocery_shopping_store_admin_notice() { 
    global $pagenow;
    $theme_args = wp_get_theme();
    $meta = get_option( 'grocery_shopping_store_admin_notice' );
    $name = $theme_args->get( 'Name' );
    $current_screen = get_current_screen();

    if ( ! $meta ) {
        if ( is_network_admin() ) {
            return;
        }

        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        if ( $current_screen->base != 'appearance_page_grocery-shopping-store-theme' ) {
            ?>
            <div class="notice notice-success notice-content">
                <h2><?php esc_html_e( 'Thank You for installing Grocery Shopping Store Theme!', 'grocery-shopping-store' ); ?> </h2>
                <div class="info-link">
                    <a href="<?php echo esc_url( admin_url( 'themes.php?page=grocery-shopping-store-theme' ) ); ?>"><?php esc_html_e( 'Omega Options', 'grocery-shopping-store' ); ?></a>
                </div>
                <div class="info-link">
                    <a href="<?php echo esc_url( GROCERY_SHOPPING_STORE_LITE_DOCS_PRO ); ?>" target="_blank"><?php esc_html_e( 'Documentation', 'grocery-shopping-store' ); ?></a>
                </div>
                <div class="info-link">
                    <a href="<?php echo esc_url( GROCERY_SHOPPING_STORE_BUY_NOW ); ?>" target="_blank"><?php esc_html_e( 'Upgrade to Pro', 'grocery-shopping-store' ); ?></a>
                </div>
                <div class="info-link">
                    <a href="<?php echo esc_url( GROCERY_SHOPPING_STORE_DEMO_PRO ); ?>" target="_blank"><?php esc_html_e( 'Premium Demo', 'grocery-shopping-store' ); ?></a>
                </div>
                <p class="dismiss-link"><strong><a href="?grocery_shopping_store_admin_notice=1"><?php esc_html_e( 'Dismiss', 'grocery-shopping-store' ); ?></a></strong></p>
            </div>
            <?php
        }
    }
}
add_action( 'admin_notices', 'grocery_shopping_store_admin_notice' );

if ( ! function_exists( 'grocery_shopping_store_update_admin_notice' ) ) :
/**
 * Updating admin notice on dismiss
 */
function grocery_shopping_store_update_admin_notice() {
    if ( isset( $_GET['grocery_shopping_store_admin_notice'] ) && $_GET['grocery_shopping_store_admin_notice'] == '1' ) {
        update_option( 'grocery_shopping_store_admin_notice', true );
    }
}
endif;
add_action( 'admin_init', 'grocery_shopping_store_update_admin_notice' );

// After Switch theme function
add_action( 'after_switch_theme', 'grocery_shopping_store_getstart_setup_options' );
function grocery_shopping_store_getstart_setup_options() {
    update_option( 'grocery_shopping_store_admin_notice', false );
}

/**
 * Enqueue styles for the help page.
 */
function grocery_shopping_store_admin_scripts($hook)
{
  wp_enqueue_style('grocery-shopping-store-admin-style', get_template_directory_uri() . '/inc/get-started/get-started.css', array(), '');
}
add_action('admin_enqueue_scripts', 'grocery_shopping_store_admin_scripts');

/**
 * Add the theme page
 */
function grocery_shopping_store_page(){
$grocery_shopping_store_user = wp_get_current_user();
$grocery_shopping_store_theme = wp_get_theme();
?>
<div class="das-wrap">
  <div class="grocery-shopping-store-panel header">
    <div class="grocery-shopping-store-logo">
      <span></span>
      <h2><?php echo esc_html( $grocery_shopping_store_theme ); ?></h2>
    </div>
    <p>
      <?php
        $grocery_shopping_store_theme = wp_get_theme();
        echo wp_kses_post( apply_filters( 'omega_theme_description', esc_html( $grocery_shopping_store_theme->get( 'Description' ) ) ) );
      ?>
    </p>
    <a class="btn btn-primary" href="<?php echo esc_url(admin_url('/customize.php?'));
?>"><?php esc_html_e('Edit With Customizer - Click Here', 'grocery-shopping-store'); ?></a>
  </div>

  <div class="das-wrap-inner">
    <div class="das-col das-col-7">
      <div class="grocery-shopping-store-panel">
        <div class="grocery-shopping-store-panel-content">
          <div class="theme-title">
            <h3><?php esc_html_e('If you are facing any issue with our theme, submit a support ticket here.', 'grocery-shopping-store'); ?></h3>
          </div>
          <a href="<?php echo esc_url( GROCERY_SHOPPING_STORE_SUPPORT_FREE ); ?>" target="_blank"
            class="btn btn-secondary"><?php esc_html_e('Lite Theme Support.', 'grocery-shopping-store'); ?></a>
        </div>
      </div>
      <div class="grocery-shopping-store-panel">
        <div class="grocery-shopping-store-panel-content">
          <div class="theme-title">
            <h3><?php esc_html_e('Please write a review if you appreciate the theme.', 'grocery-shopping-store'); ?></h3>
          </div>
          <a href="<?php echo esc_url( GROCERY_SHOPPING_STORE_REVIEW_FREE ); ?>" target="_blank"
            class="btn btn-secondary"><?php esc_html_e('Rank this topic.', 'grocery-shopping-store'); ?></a>
        </div>
      </div>
       <div class="grocery-shopping-store-panel">
        <div class="grocery-shopping-store-panel-content">
          <div class="theme-title">
            <h3><?php esc_html_e('Follow our lite theme documentation to set up our lite theme as seen in the screenshot.', 'grocery-shopping-store'); ?></h3>
          </div>
          <a href="<?php echo esc_url( GROCERY_SHOPPING_STORE_LITE_DOCS_PRO ); ?>" target="_blank"
            class="btn btn-secondary"><?php esc_html_e('Lite Documentation.', 'grocery-shopping-store'); ?></a>
        </div>
      </div>
    </div>
    <div class="das-col das-col-3">
      <div class="upgrade-div">
        <h4>
          <strong>
            <?php esc_html_e('Premium Features Include:', 'grocery-shopping-store'); ?>
          </strong>
        </h4>
        <ul>
          <li>
            <?php esc_html_e('One Click Demo Content Importer', 'grocery-shopping-store'); ?>
          </li>
          <li>
            <?php esc_html_e('Woocommerce Plugin Compatibility', 'grocery-shopping-store'); ?>
          </li>
          <li>
            <?php esc_html_e('Multiple Section for the templates', 'grocery-shopping-store'); ?>            
          </li>
          <li>
            <?php esc_html_e('For a better user experience, make the top of your menu sticky.', 'grocery-shopping-store'); ?>  
          </li>
        </ul>
        <div class="text-center">
          <a href="<?php echo esc_url( GROCERY_SHOPPING_STORE_BUY_NOW ); ?>" target="_blank"
            class="btn btn-success"><?php esc_html_e('Upgrade Pro $40', 'grocery-shopping-store'); ?></a>
        </div>
      </div>
      <div class="grocery-shopping-store-panel">
        <div class="grocery-shopping-store-panel-content">
          <div class="theme-title">
            <h3><?php esc_html_e('Kindly view the premium themes live demo.', 'grocery-shopping-store'); ?></h3>
          </div>
          <a class="btn btn-primary demo" href="<?php echo esc_url( GROCERY_SHOPPING_STORE_DEMO_PRO ); ?>" target="_blank"
            class="btn btn-secondary"><?php esc_html_e('Live Demo.', 'grocery-shopping-store'); ?></a>
        </div>
        <div class="grocery-shopping-store-panel-content pro-doc">
          <div class="theme-title">
            <h3><?php esc_html_e('Follow our pro theme documentation to set up our premium theme as seen in the screenshot.', 'grocery-shopping-store'); ?></h3>
          </div>
          <a href="<?php echo esc_url( GROCERY_SHOPPING_STORE_DOCS_PRO ); ?>" target="_blank"
            class="btn btn-primary demo"><?php esc_html_e('Pro Documentation.', 'grocery-shopping-store'); ?></a>
        </div>
      </div>
    </div>
  </div>
</div>
<?php
}