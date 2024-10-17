<?php
/**
 * Continental Restaurant Theme Customizer
 *
 * @package Continental Restaurant
 */

function Continental_Restaurant_Customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial(
			'blogname',
			array(
				'selector'        => '.site-title a',
				'render_callback' => 'Continental_Restaurant_Customize_partial_blogname',
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'blogdescription',
			array(
				'selector'        => '.site-description',
				'render_callback' => 'Continental_Restaurant_Customize_partial_blogdescription',
			)
		);
	}

	/*
    * Theme Options Panel
    */
	$wp_customize->add_panel('continental_restaurant_panel', array(
		'priority' => 25,
		'capability' => 'edit_theme_options',
		'title' => __('Restaurant Theme Options', 'continental-restaurant'),
	));

	/*
	* Customizer main header section
	*/

	$wp_customize->add_setting(
		'continental_restaurant_site_title_text',
		array(
			'capability'        => 'edit_theme_options',
			'transport'         => 'refresh',
			'default'           => 1,
			'sanitize_callback' => 'continental_restaurant_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'continental_restaurant_site_title_text',
		array(
			'label'       => __('Enable Title', 'continental-restaurant'),
			'description' => __('Enable or Disable Title from the site', 'continental-restaurant'),
			'section'     => 'title_tagline',
			'type'        => 'checkbox',
		)
	);

	$wp_customize->add_setting(
		'continental_restaurant_site_tagline_text',
		array(
			'capability'        => 'edit_theme_options',
			'transport'         => 'refresh',
			'default'           => 0,
			'sanitize_callback' => 'continental_restaurant_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'continental_restaurant_site_tagline_text',
		array(
			'label'       => __('Enable Tagline', 'continental-restaurant'),
			'description' => __('Enable or Disable Tagline from the site', 'continental-restaurant'),
			'section'     => 'title_tagline',
			'type'        => 'checkbox',
		)
	);

		$wp_customize->add_setting(
		'continental_restaurant_logo_width',
		array(
			'capability'        => 'edit_theme_options',
			'transport'         => 'refresh',
			'default'           => '150',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'continental_restaurant_logo_width',
		array(
			'label'       => __('Logo Width in PX', 'continental-restaurant'),
			'section'     => 'title_tagline',
			'type'        => 'number',
			'input_attrs' => array(
	            'min' => 100,
	             'max' => 300,
	             'step' => 1,
	         ),
		)
	);

	/*Additional Options*/
	$wp_customize->add_section('continental_restaurant_additional_section', array(
		'priority'       => 5,
		'capability'     => 'edit_theme_options',
		'theme_supports' => '',
		'title'          => __('Additional Options', 'continental-restaurant'),
		'panel'       => 'continental_restaurant_panel',
	));

	/*Main Slider Enable Option*/
	$wp_customize->add_setting(
		'continental_restaurant_enable_preloader',
		array(
			'capability'        => 'edit_theme_options',
			'transport'         => 'refresh',
			'default'           => 0,
			'sanitize_callback' => 'continental_restaurant_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'continental_restaurant_enable_preloader',
		array(
			'label'       => __('Enable Preloader', 'continental-restaurant'),
			'description' => __('Checked to show preloader', 'continental-restaurant'),
			'section'     => 'continental_restaurant_additional_section',
			'type'        => 'checkbox',
		)
	);
	
	/*Main Header Options*/
	$wp_customize->add_section('continental_restaurant_header_section', array(
		'priority'       => 5,
		'capability'     => 'edit_theme_options',
		'theme_supports' => '',
		'title'          => __('Main Header Options', 'continental-restaurant'),
		'panel'       => 'continental_restaurant_panel',
	));

	/*
	* Customizer top header section
	*/

	/*
	* Customizer main header section
	*/

	/*Main Header Options*/
	$wp_customize->add_section('continental_restaurant_header_section', array(
		'priority'       => 5,
		'capability'     => 'edit_theme_options',
		'theme_supports' => '',
		'title'          => __('Main Header Options', 'continental-restaurant'),
		'panel'       => 'continental_restaurant_panel',
	));

	/*Main Header Phone Text*/
	$wp_customize->add_setting(
		'continental_restaurant_header_info_email',
		array(
			'capability'        => 'edit_theme_options',
			'transport'         => 'refresh',
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'continental_restaurant_header_info_email',
		array(
			'label'       => __('Edit Email Address ', 'continental-restaurant'),
			'section'     => 'continental_restaurant_header_section',
			'type'        => 'text',
		)
	);

	/*Main Header Phone Text*/
	$wp_customize->add_setting(
		'continental_restaurant_header_info_phone',
		array(
			'capability'        => 'edit_theme_options',
			'transport'         => 'refresh',
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'continental_restaurant_header_info_phone',
		array(
			'label'       => __('Edit Phone Number ', 'continental-restaurant'),
			'section'     => 'continental_restaurant_header_section',
			'type'        => 'text',
		)
	);

	/*Facebook Link*/
	$wp_customize->add_setting(
		'continental_restaurant_facebook_link_option',
		array(
			'capability'        => 'edit_theme_options',
			'transport'         => 'refresh',
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		)
	);

	$wp_customize->add_control(
		'continental_restaurant_facebook_link_option',
		array(
			'label'       => __('Edit Facebook Link', 'continental-restaurant'),
			'section'     => 'continental_restaurant_header_section',
			'type'        => 'url',
		)
	);

	/*Twitter Link*/
	$wp_customize->add_setting(
		'continental_restaurant_twitter_link_option',
		array(
			'capability'        => 'edit_theme_options',
			'transport'         => 'refresh',
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		)
	);

	$wp_customize->add_control(
		'continental_restaurant_twitter_link_option',
		array(
			'label'       => __('Edit Twitter Link', 'continental-restaurant'),
			'section'     => 'continental_restaurant_header_section',
			'type'        => 'url',
		)
	);

	/*Youtube Link*/
	$wp_customize->add_setting(
		'continental_restaurant_google_plus_link_option',
		array(
			'capability'        => 'edit_theme_options',
			'transport'         => 'refresh',
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		'continental_restaurant_google_plus_link_option',
		array(
			'label'       => __('Edit Google Plus Link', 'continental-restaurant'),
			'section'     => 'continental_restaurant_header_section',
			'type'        => 'url',
		)
	);

	/*Instagram Link*/
	$wp_customize->add_setting(
		'continental_restaurant_instagram_link_option',
		array(
			'capability'        => 'edit_theme_options',
			'transport'         => 'refresh',
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		)
	);

	$wp_customize->add_control(
		'continental_restaurant_instagram_link_option',
		array(
			'label'       => __('Edit Instagram Link', 'continental-restaurant'),
			'section'     => 'continental_restaurant_header_section',
			'type'        => 'url',
		)
	);

	/*
	* Customizer main slider section
	*/
	/*Main Slider Options*/
	$wp_customize->add_section('continental_restaurant_slider_section', array(
		'priority'       => 5,
		'capability'     => 'edit_theme_options',
		'theme_supports' => '',
		'title'          => __('Main Slider Options', 'continental-restaurant'),
		'panel'       => 'continental_restaurant_panel',
	));

	/*Main Slider Enable Option*/
	$wp_customize->add_setting(
		'continental_restaurant_enable_slider',
		array(
			'capability'        => 'edit_theme_options',
			'transport'         => 'refresh',
			'default'           => 1,
			'sanitize_callback' => 'continental_restaurant_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'continental_restaurant_enable_slider',
		array(
			'label'       => __('Enable Main Slider', 'continental-restaurant'),
			'description' => __('Checked to show the main slider', 'continental-restaurant'),
			'section'     => 'continental_restaurant_slider_section',
			'type'        => 'checkbox',
		)
	);

	for ($i=1; $i <= 3; $i++) { 

		/*Main Slider Image*/
		$wp_customize->add_setting(
			'continental_restaurant_slider_image'.$i,
			array(
				'capability'    => 'edit_theme_options',
		        'default'       => '',
		        'transport'     => 'postMessage',
		        'sanitize_callback' => 'esc_url_raw',
	    	)
	    );

		$wp_customize->add_control( 
			new WP_Customize_Image_Control( $wp_customize, 
				'continental_restaurant_slider_image'.$i, 
				array(
			        'label' => __('Edit Slider Image ', 'continental-restaurant') .$i,
			        'description' => __('Edit the slider image.', 'continental-restaurant'),
			        'section' => 'continental_restaurant_slider_section',
				)
			)
		);

		/*Main Slider Heading*/
		$wp_customize->add_setting(
			'continental_restaurant_slider_top_text'.$i,
			array(
				'capability'        => 'edit_theme_options',
				'transport'         => 'refresh',
				'default'           => '',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			'continental_restaurant_slider_top_text'.$i,
			array(
				'label'       => __('Edit Slider Top Text ', 'continental-restaurant') .$i,
				'description' => __('Edit the slider Top text.', 'continental-restaurant'),
				'section'     => 'continental_restaurant_slider_section',
				'type'        => 'text',
			)
		);

		/*Main Slider Heading*/
		$wp_customize->add_setting(
			'continental_restaurant_slider_heading'.$i,
			array(
				'capability'        => 'edit_theme_options',
				'transport'         => 'refresh',
				'default'           => '',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			'continental_restaurant_slider_heading'.$i,
			array(
				'label'       => __('Edit Heading Text ', 'continental-restaurant') .$i,
				'description' => __('Edit the slider heading text.', 'continental-restaurant'),
				'section'     => 'continental_restaurant_slider_section',
				'type'        => 'text',
			)
		);

		/*Main Slider Content*/
		$wp_customize->add_setting(
			'continental_restaurant_slider_text'.$i,
			array(
				'capability'        => 'edit_theme_options',
				'transport'         => 'refresh',
				'default'           => '',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			'continental_restaurant_slider_text'.$i,
			array(
				'label'       => __('Edit Content Text ', 'continental-restaurant') .$i,
				'description' => __('Edit the slider content text.', 'continental-restaurant'),
				'section'     => 'continental_restaurant_slider_section',
				'type'        => 'text',
			)
		);

		/*Main Slider Button1 Text*/
		$wp_customize->add_setting(
			'continental_restaurant_slider_button1_text'.$i,
			array(
				'capability'        => 'edit_theme_options',
				'transport'         => 'refresh',
				'default'           => '',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			'continental_restaurant_slider_button1_text'.$i,
			array(
				'label'       => __('Edit Button #1 Text ', 'continental-restaurant') .$i,
				'description' => __('Edit the slider button text.', 'continental-restaurant'),
				'section'     => 'continental_restaurant_slider_section',
				'type'        => 'text',
			)
		);

		/*Main Slider Button1 URL*/
		$wp_customize->add_setting(
			'continental_restaurant_slider_button1_link'.$i,
			array(
				'capability'        => 'edit_theme_options',
				'transport'         => 'refresh',
				'default'           => '',
				'sanitize_callback' => 'esc_url_raw',
			)
		);

		$wp_customize->add_control(
			'continental_restaurant_slider_button1_link'.$i,
			array(
				'label'       => __('Edit Button #1 URL ', 'continental-restaurant') .$i,
				'description' => __('Edit the slider button url.', 'continental-restaurant'),
				'section'     => 'continental_restaurant_slider_section',
				'type'        => 'url',
			)
		);
	}

	/*
	* Customizer Our Special Products section
	*/
	/*New Arrivals Options*/
	$wp_customize->add_section('continental_restaurant_arrivals_section', array(
		'priority'       => 5,
		'capability'     => 'edit_theme_options',
		'theme_supports' => '',
		'title'          => __('Our Products Option', 'continental-restaurant'),
		'panel'       => 'continental_restaurant_panel',
	));

	/*New Arrivals Enable Option*/
	$wp_customize->add_setting(
		'continental_restaurant_enable_new_arrivals',
		array(
			'capability'        => 'edit_theme_options',
			'transport'         => 'refresh',
			'default'           => 0,
			'sanitize_callback' => 'continental_restaurant_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'continental_restaurant_enable_new_arrivals',
		array(
			'label'       => __('Enable Our Special Products Section', 'continental-restaurant'),
			'description' => __('Checked to show the category', 'continental-restaurant'),
			'section'     => 'continental_restaurant_arrivals_section',
			'type'        => 'checkbox',
		)
	);

	/*Our Special Products Heading 2*/
	$wp_customize->add_setting(
		'continental_restaurant_new_arrivals_top_heading',
		array(
			'capability'        => 'edit_theme_options',
			'transport'         => 'refresh',
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'continental_restaurant_new_arrivals_top_heading',
		array(
			'label'       => __('Edit Section Top Heading', 'continental-restaurant'),
			'description' => __('Edit section top heading', 'continental-restaurant'),
			'section'     => 'continental_restaurant_arrivals_section',
			'type'        => 'text',
		)
	);

	/*Our Special Products Heading*/
	$wp_customize->add_setting(
		'continental_restaurant_new_arrivals_heading',
		array(
			'capability'        => 'edit_theme_options',
			'transport'         => 'refresh',
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'continental_restaurant_new_arrivals_heading',
		array(
			'label'       => __('Edit Section Heading', 'continental-restaurant'),
			'description' => __('Edit section main heading', 'continental-restaurant'),
			'section'     => 'continental_restaurant_arrivals_section',
			'type'        => 'text',
		)
	);

	/*Our Special Products Products*/
	$args = array(
       'type'      => 'product',
        'taxonomy' => 'product_cat'
    );
	$categories = get_categories($args);
		$cat_posts = array();
			$i = 0;
			$cat_posts[]='Select';
		foreach($categories as $category){
			if($i==0){
			$default = $category->slug;
			$i++;
		}
		$cat_posts[$category->slug] = $category->name;
	}

	$wp_customize->add_setting('continental_restaurant_product_category',array(
		'default'	=> 'select',
		'sanitize_callback' => 'continental_restaurant_sanitize_choices',
	));
	$wp_customize->add_control('continental_restaurant_product_category',array(
		'type'    => 'select',
		'choices' => $cat_posts,
		'label' => __('Select Product Category','continental-restaurant'),
		'section' => 'continental_restaurant_arrivals_section',
	));

	/*
	* Customizer Footer Section
	*/
	/*Footer Options*/
	$wp_customize->add_section('continental_restaurant_footer_section', array(
		'priority'       => 5,
		'capability'     => 'edit_theme_options',
		'theme_supports' => '',
		'title'          => __('Footer Options', 'continental-restaurant'),
		'panel'       => 'continental_restaurant_panel',
	));


	/*Footer Social Menu Option*/
	$wp_customize->add_setting(
		'continental_restaurant_footer_social_menu',
		array(
			'capability'        => 'edit_theme_options',
			'transport'         => 'refresh',
			'default'           => 1,
			'sanitize_callback' => 'continental_restaurant_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'continental_restaurant_footer_social_menu',
		array(
			'label'       => __('Enable Footer Social Menu', 'continental-restaurant'),
			'description' => __('Checked to show the footer social menu. Go to Dashboard >> Appearance >> Menus >> Create New Menu >> Add Custom Link >> Add Social Menu >> Checked Social Menu >> Save Menu.', 'continental-restaurant'),
			'section'     => 'continental_restaurant_footer_section',
			'type'        => 'checkbox',
		)
	);	

	/*Go To Top Option*/
	$wp_customize->add_setting(
		'continental_restaurant_enable_go_to_top_option',
		array(
			'capability'        => 'edit_theme_options',
			'transport'         => 'refresh',
			'default'           => 1,
			'sanitize_callback' => 'continental_restaurant_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'continental_restaurant_enable_go_to_top_option',
		array(
			'label'       => __('Enable Go To Top', 'continental-restaurant'),
			'description' => __('Checked to enable Go To Top option.', 'continental-restaurant'),
			'section'     => 'continental_restaurant_footer_section',
			'type'        => 'checkbox',
		)
	);

	/*Footer Copyright Text Enable*/
	$wp_customize->add_setting(
		'continental_restaurant_copyright_option',
		array(
			'capability'        => 'edit_theme_options',
			'transport'         => 'refresh',
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'continental_restaurant_copyright_option',
		array(
			'label'       => __('Edit Copyright Text', 'continental-restaurant'),
			'description' => __('Edit the Footer Copyright Section.', 'continental-restaurant'),
			'section'     => 'continental_restaurant_footer_section',
			'type'        => 'text',
		)
	);
}
add_action( 'customize_register', 'Continental_Restaurant_Customize_register' );

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function Continental_Restaurant_Customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function Continental_Restaurant_Customize_partial_blogdescription() {
	bloginfo( 'description' );
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function Continental_Restaurant_Customize_preview_js() {
	wp_enqueue_script( 'continental-restaurant-customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), CONTINENTAL_RESTAURANT_VERSION, true );
}
add_action( 'customize_preview_init', 'Continental_Restaurant_Customize_preview_js' );

/**
 * Singleton class for handling the theme's customizer integration.
 *
 * @since  1.0.0
 * @access public
 */
final class Continental_Restaurant_Customize {

	/**
	 * Returns the instance.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return object
	 */
	public static function get_instance() {

		static $instance = null;

		if ( is_null( $instance ) ) {
			$instance = new self;
			$instance->setup_actions();
		}

		return $instance;
	}

	/**
	 * Constructor method.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
	private function __construct() {}

	/**
	 * Sets up initial actions.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
	private function setup_actions() {

		// Register panels, sections, settings, controls, and partials.
		add_action( 'customize_register', array( $this, 'sections' ) );

		// Register scripts and styles for the controls.
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'enqueue_control_scripts' ), 0 );
	}

	/**
	 * Sets up the customizer sections.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  object  $manager
	 * @return void
	*/
	public function sections( $manager ) {

		// Load custom sections.
		load_template( trailingslashit( get_template_directory() ) . '/revolution/inc/section-pro.php' );

		// Register custom section types.
		$manager->register_section_type( 'Continental_Restaurant_Customize_Section_Pro' );

		// Register sections.
		$manager->add_section( new Continental_Restaurant_Customize_Section_Pro( $manager,'continental_restaurant_go_pro', array(
			'priority'   => 1,
			'title'    => esc_html__( 'Restaurant', 'continental-restaurant' ),
			'pro_text' => esc_html__( 'Buy Pro', 'continental-restaurant' ),
			'pro_url'  => esc_url('https://www.revolutionwp.com/wp-themes/continental-restaurant-wordpress-theme/'),
		) )	);		
	}

	/**
	 * Loads theme customizer CSS.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function enqueue_control_scripts() {

		wp_enqueue_script( 'continental-restaurant-customize-controls', trailingslashit( get_template_directory_uri() ) . '/revolution/assets/js/customize-controls.js', array( 'customize-controls' ) );

		wp_enqueue_style( 'continental-restaurant-customize-controls', trailingslashit( get_template_directory_uri() ) . '/revolution/assets/css/customize-controls.css' );
	}
}

// Doing this customizer thang!
Continental_Restaurant_Customize::get_instance();