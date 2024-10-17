<?php
//about theme info
add_action( 'admin_menu', 'continental_restaurant_gettingstarted' );
function continental_restaurant_gettingstarted() {
	add_theme_page( esc_html__('About Continental Restaurant', 'continental-restaurant'), esc_html__('About Continental Restaurant', 'continental-restaurant'), 'edit_theme_options', 'continental_restaurant_guide', 'continental_restaurant_mostrar_guide');   
}

// Add a Custom CSS file to WP Admin Area
function continental_restaurant_admin_theme_style() {
   wp_enqueue_style('continental-restaurant-custom-admin-style', esc_url( get_template_directory_uri() ) . '/getstarted/getstarted.css');
   wp_enqueue_script('continental-restaurant-tab', esc_url( get_template_directory_uri() ) . '/getstarted/js/tab.js');
}
add_action('admin_enqueue_scripts', 'continental_restaurant_admin_theme_style');

//guidline for about theme
function continental_restaurant_mostrar_guide() { 
	//custom function about theme customizer
	$return = add_query_arg( array()) ;
	$continental_restaurant_theme = wp_get_theme( 'continental-restaurant' );
?>
<?php $continental_restaurant_theme = wp_get_theme(); ?>

<div class="theme-about-wrap">
    <div class="about-header">
        <div class="about-header-column">
            <h1><?php esc_html_e('Continental Restaurant!', 'continental-restaurant'); ?></h1>
        </div>
        <div class="about-header-column">
           <a class="btn btn-default btn2" target="_blank" href="<?php echo esc_url(CONTINENTAL_RESTAURANT_BUY_NOW ); ?>"><?php esc_html_e('Get Continental Restaurant Pro', 'continental-restaurant'); ?></a>
            <a class="btn btn-default btn1" target="_blank" href="<?php echo esc_url(CONTINENTAL_RESTAURANT_LITE_DOC ); ?>"><?php esc_html_e('Documentation', 'continental-restaurant'); ?></a>
        </div>
    </div>
    <div class="tab-section">
          <h2><?php esc_html_e('More About Continental Restaurant', 'continental-restaurant'); ?></h2>
        <div class="theme-content-wrap">
        		<div class="col-md-7">
              <div class="tab-container">
                <div class="tabs">
                  <button class="tab active"><?php esc_html_e('Essential Links', 'continental-restaurant'); ?></button>
                  <button class="tab"> <?php esc_html_e('Lite Vs Premium', 'continental-restaurant'); ?></button>
                </div>
                <div class="tab-content">
                  <div class="gre-box">
			            <h3><?php esc_html_e('Editing Homepage', 'continental-restaurant'); ?></h3>
			            <p> <?php esc_html_e('Go to Apearance > Customizer > Continental Restaurant Theme Options edit our lite version', 'continental-restaurant'); ?>
			            </p>
			            <a class="btn btn-default" href="<?php echo esc_url( admin_url( 'customize.php' ) ) ?>"><?php esc_html_e('Go to Customizer', 'continental-restaurant'); ?></a>
			        </div>
                  <div class="gre-box">
                    <h3><?php esc_html_e('Demo for Continental Restaurant (Premium) ', 'continental-restaurant'); ?></h3>
                    <p><?php esc_html_e('Check out the amazing Continental Restaurant premium version demo.', 'continental-restaurant'); ?></p>
                    <a class="btn btn-default" target="_blank" href="<?php echo esc_url(CONTINENTAL_RESTAURANT_LIVE_DEMO ); ?>"><?php esc_html_e('View Demo', 'continental-restaurant'); ?></a>
                  </div>
                  <div class="gre-box">
                    <h3><?php esc_html_e('Our Lite Theme Documentation', 'continental-restaurant'); ?></h3>
                    <p><?php esc_html_e('Take a look at our guides and start customizing themes with ease. With the help of our documentation, which offers insightful information on every facet of theme functioning, you can confidently build a website that realizes your idea.', 'continental-restaurant'); ?></p>
                    <a class="btn btn-default" target="_blank" href="<?php echo esc_url(CONTINENTAL_RESTAURANT_LITE_DOC ); ?>"><?php esc_html_e('View Documentation', 'continental-restaurant'); ?></a>
                  </div>
                  <div class="gre-box">
                    <h3><?php esc_html_e('Support Ticket', 'continental-restaurant'); ?></h3>
                    <p><?php esc_html_e('Take a look at our guides and start customizing themes with ease. With the help of our documentation, which offers insightful information on every facet of theme functioning, you can confidently build a website that realizes your idea.', 'continental-restaurant'); ?></p>
                    <a class="btn btn-default" target="_blank" href="<?php echo esc_url(CONTINENTAL_RESTAURANT_FREE_SUPPORT ); ?>"><?php esc_html_e('Contact Support', 'continental-restaurant'); ?></a>
                  </div>
                </div>
                <div class="tab-content hidden">
                        <table>
                          <tr>
                            <th><?php esc_html_e('Features', 'continental-restaurant'); ?></th>
                            <th><?php esc_html_e('Free Version', 'continental-restaurant'); ?></th>
                            <th><?php esc_html_e('Premium Version', 'continental-restaurant'); ?></th>
                          </tr>
                           <tr>
                            <td><?php esc_html_e('Priority Support', 'continental-restaurant'); ?></td>
                            <td><span class="yes-tick no"><svg class="svg-icon" viewBox="0 0 20 20">
                            <path fill="none" d="M15.898,4.045c-0.271-0.272-0.713-0.272-0.986,0l-4.71,4.711L5.493,4.045c-0.272-0.272-0.714-0.272-0.986,0s-0.272,0.714,0,0.986l4.709,4.711l-4.71,4.711c-0.272,0.271-0.272,0.713,0,0.986c0.136,0.136,0.314,0.203,0.492,0.203c0.179,0,0.357-0.067,0.493-0.203l4.711-4.711l4.71,4.711c0.137,0.136,0.314,0.203,0.494,0.203c0.178,0,0.355-0.067,0.492-0.203c0.273-0.273,0.273-0.715,0-0.986l-4.711-4.711l4.711-4.711C16.172,4.759,16.172,4.317,15.898,4.045z"></path>
                        </svg></span></td>
                            <td><span class="yes-tick"><svg class="svg-icon" viewBox="0 0 20 20">
                            <path fill="none" d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z"></path>
                        </svg></span></td>
                          </tr>
                          <tr>
                            <td><?php esc_html_e('Responsive Design', 'continental-restaurant'); ?></td>
                            <td><span class="yes-tick"><svg class="svg-icon" viewBox="0 0 20 20">
                            <path fill="none" d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z"></path>
                        </svg></span></td>
                            <td><span class="yes-tick"><svg class="svg-icon" viewBox="0 0 20 20">
                            <path fill="none" d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z"></path>
                        </svg></span></td>
                          </tr>
                          <tr>
                            <td><?php esc_html_e('Easy Setup', 'continental-restaurant'); ?></td>
                            <td><span class="yes-tick"><svg class="svg-icon" viewBox="0 0 20 20">
                            <path fill="none" d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z"></path>
                        </svg></span></td>
                            <td><span class="yes-tick"><svg class="svg-icon" viewBox="0 0 20 20">
                            <path fill="none" d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z"></path>
                        </svg></span></td>
                          </tr>
                          <tr>
                            <td><?php esc_html_e('One Click Demo Import', 'continental-restaurant'); ?></td>
                            <td><span class="yes-tick no"><svg class="svg-icon" viewBox="0 0 20 20">
                            <path fill="none" d="M15.898,4.045c-0.271-0.272-0.713-0.272-0.986,0l-4.71,4.711L5.493,4.045c-0.272-0.272-0.714-0.272-0.986,0s-0.272,0.714,0,0.986l4.709,4.711l-4.71,4.711c-0.272,0.271-0.272,0.713,0,0.986c0.136,0.136,0.314,0.203,0.492,0.203c0.179,0,0.357-0.067,0.493-0.203l4.711-4.711l4.71,4.711c0.137,0.136,0.314,0.203,0.494,0.203c0.178,0,0.355-0.067,0.492-0.203c0.273-0.273,0.273-0.715,0-0.986l-4.711-4.711l4.711-4.711C16.172,4.759,16.172,4.317,15.898,4.045z"></path>
                        </svg></span></td>
                            <td><span class="yes-tick"><svg class="svg-icon" viewBox="0 0 20 20">
                            <path fill="none" d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z"></path>
                        </svg></span></td>
                          </tr>
                          <tr>
                            <td><?php esc_html_e('SEO Optimized', 'continental-restaurant'); ?></td>
                            <td><span class="yes-tick"><svg class="svg-icon" viewBox="0 0 20 20">
                            <path fill="none" d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z"></path>
                        </svg></span></td>
                            <td><span class="yes-tick"><svg class="svg-icon" viewBox="0 0 20 20">
                            <path fill="none" d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z"></path>
                        </svg></span></td>
                          </tr>
                          <tr>
                            <td><?php esc_html_e('Cross Browser Compatible', 'continental-restaurant'); ?></td>
                            <td><span class="yes-tick"><svg class="svg-icon" viewBox="0 0 20 20">
                            <path fill="none" d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z"></path>
                        </svg></span></td>
                            <td><span class="yes-tick"><svg class="svg-icon" viewBox="0 0 20 20">
                            <path fill="none" d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z"></path>
                        </svg></span></td>
                          </tr>
                          <tr>
                            <td><?php esc_html_e('Translation Ready', 'continental-restaurant'); ?></td>
                            <td><span class="yes-tick"><svg class="svg-icon" viewBox="0 0 20 20">
                            <path fill="none" d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z"></path>
                        </svg></span></td>
                            <td><span class="yes-tick"><svg class="svg-icon" viewBox="0 0 20 20">
                            <path fill="none" d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z"></path>
                        </svg></span></td>
                          </tr>
                          <tr>
                            <td><?php esc_html_e('Change Footer Copyright', 'continental-restaurant'); ?></td>
                            <td><span class="yes-tick no"><svg class="svg-icon" viewBox="0 0 20 20">
                            <path fill="none" d="M15.898,4.045c-0.271-0.272-0.713-0.272-0.986,0l-4.71,4.711L5.493,4.045c-0.272-0.272-0.714-0.272-0.986,0s-0.272,0.714,0,0.986l4.709,4.711l-4.71,4.711c-0.272,0.271-0.272,0.713,0,0.986c0.136,0.136,0.314,0.203,0.492,0.203c0.179,0,0.357-0.067,0.493-0.203l4.711-4.711l4.71,4.711c0.137,0.136,0.314,0.203,0.494,0.203c0.178,0,0.355-0.067,0.492-0.203c0.273-0.273,0.273-0.715,0-0.986l-4.711-4.711l4.711-4.711C16.172,4.759,16.172,4.317,15.898,4.045z"></path>
                        </svg></span></td>
                            <td><span class="yes-tick"><svg class="svg-icon" viewBox="0 0 20 20">
                            <path fill="none" d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z"></path>
                        </svg></span></td>
                          </tr>
                          <tr>
                            <td><?php esc_html_e('Better Homepage Design', 'continental-restaurant'); ?></td>
                            <td><span class="yes-tick no"><svg class="svg-icon" viewBox="0 0 20 20">
                            <path fill="none" d="M15.898,4.045c-0.271-0.272-0.713-0.272-0.986,0l-4.71,4.711L5.493,4.045c-0.272-0.272-0.714-0.272-0.986,0s-0.272,0.714,0,0.986l4.709,4.711l-4.71,4.711c-0.272,0.271-0.272,0.713,0,0.986c0.136,0.136,0.314,0.203,0.492,0.203c0.179,0,0.357-0.067,0.493-0.203l4.711-4.711l4.71,4.711c0.137,0.136,0.314,0.203,0.494,0.203c0.178,0,0.355-0.067,0.492-0.203c0.273-0.273,0.273-0.715,0-0.986l-4.711-4.711l4.711-4.711C16.172,4.759,16.172,4.317,15.898,4.045z"></path>
                        </svg></span></td>
                            <td><span class="yes-tick"><svg class="svg-icon" viewBox="0 0 20 20">
                            <path fill="none" d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z"></path>
                        </svg></span></td>
                          </tr>
                          <tr>
                            <td><?php esc_html_e('Banner Image Slider', 'continental-restaurant'); ?></td>
                            <td><span class="yes-tick no"><svg class="svg-icon" viewBox="0 0 20 20">
                            <path fill="none" d="M15.898,4.045c-0.271-0.272-0.713-0.272-0.986,0l-4.71,4.711L5.493,4.045c-0.272-0.272-0.714-0.272-0.986,0s-0.272,0.714,0,0.986l4.709,4.711l-4.71,4.711c-0.272,0.271-0.272,0.713,0,0.986c0.136,0.136,0.314,0.203,0.492,0.203c0.179,0,0.357-0.067,0.493-0.203l4.711-4.711l4.71,4.711c0.137,0.136,0.314,0.203,0.494,0.203c0.178,0,0.355-0.067,0.492-0.203c0.273-0.273,0.273-0.715,0-0.986l-4.711-4.711l4.711-4.711C16.172,4.759,16.172,4.317,15.898,4.045z"></path>
                        </svg></span></td>
                            <td><span class="yes-tick"><svg class="svg-icon" viewBox="0 0 20 20">
                            <path fill="none" d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z"></path>
                        </svg></span></td>
                          </tr>
                        </table>
                </div>
                <div class="tab-content hidden"></div>
              </div>
            </div>
        		<div class="col-md-5">
    					<div class="gre-box side">
 							<h3><?php esc_html_e('Checkout Continental Restaurant Premium Version', 'continental-restaurant'); ?></h3>
                    <p><?php esc_html_e('Continental Restaurant Premium version comes with lot more features and better designs. Also We provide priority support to Premium version users.', 'continental-restaurant'); ?></p>
                    <a class="btn btn-default" target="_blank" href="<?php echo esc_url(CONTINENTAL_RESTAURANT_BUY_NOW ); ?>"><?php esc_html_e('Get Continental Restaurant Pro', 'continental-restaurant'); ?></a>
                  </div>
                  <div class="gre-box side">
                    <h3><?php esc_html_e('Admire the Continental Restaurant Theme?', 'continental-restaurant'); ?></h3>
                    <p><?php esc_html_e('Give us your support by leaving a 5-star review; it only takes a few minutes. It will be of great assistance to us.', 'continental-restaurant'); ?></p>
                    <a class="btn btn-default btn1" target="_blank" href="<?php echo esc_url(CONTINENTAL_RESTAURANT_REVIEW ); ?>"><?php esc_html_e('Add a Review', 'continental-restaurant'); ?></a>
                 </div>
            </div>
        </div>
    </div>
</div>
<?php } ?>
