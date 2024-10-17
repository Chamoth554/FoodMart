<?php 
namespace Depicter\Modules\Bricks;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Widget extends \Bricks\Element {

  // Element properties
  public $category     = 'general';

  public $name         = 'depicter';

  public $icon         = 'ti-layout-slider-alt';

  public $css_selector = '.depicter';

  public $scripts      = []; // Script(s) run when element is rendered on frontend or updated in builder

  // Return localised element label
  public function get_label() {
    return esc_html__( 'Depicter', 'depicter' );
  }

  // Set builder control groups
  public function set_control_groups() {

    $this->control_groups['sliders_list'] = [
      'title' => esc_html__( 'Sliders List', 'depicter' ),
      'tab' => 'content',
    ];
  }
 
  // Set builder controls
  public function set_controls() {
    
    $this->controls['slider_id'] = [
      'tab' => 'content',
      'group' => 'sliders_list',
      'label' => esc_html__( 'Select Slider', 'depicter' ),
      'type' => 'select',
      'options' => $this->getSlidersList(),
      'inline' => true,
      'clearable' => false,
      'pasteStyles' => false,
      'default' => '0',
      'rerender'       => true,
    ];
  }

  // Enqueue element styles and scripts
  public function enqueue_scripts() {
    
    $styles = \Depicter::front()->assets()->enqueueStyles();
    foreach ( $styles as $key => $style ) {
      wp_enqueue_style( $key );
    }

    if ( ( defined('REST_REQUEST') && REST_REQUEST ) || ( isset( $_GET['bricks'] ) && $_GET['bricks'] == 'run' ) ) {
      $scripts = \Depicter::front()->assets()->enqueueScripts(['player', 'iframe-resizer']);
    } else {  
      $scripts = \Depicter::front()->assets()->enqueueScripts();
    }

    foreach ( $scripts as $key => $script ) {
      wp_enqueue_script( $key );
    }
  }
  
  public function getSlidersList() {
    $list = [
        '0' => __( 'Select Slider', 'depicter' )
    ];
    $documents = \Depicter::documentRepository()->select( ['id', 'name'] )->orderBy('modified_at', 'DESC')->findAll()->get();
    $documents = $documents ? $documents->toArray() : [];
    foreach( $documents as $document ) {
        $list[ "#" . $document['id'] ] = "[#{$document['id']}]: " . $document['name'];
    }
    return $list;
}

  // Render element HTML
  public function render() {
    if ( $this->settings['slider_id'] ) {
      $sliderID = ltrim( $this->settings['slider_id'], '#' );
      if ( ( defined('REST_REQUEST') && REST_REQUEST ) || ( isset( $_GET['bricks'] ) && $_GET['bricks'] == 'run' ) ) {
        $iframeID = "sliderIframe-" . $sliderID;
        $iframeURL =  admin_url('admin-ajax.php') . '?action=depicter-document-preview&depicter-csrf=' . \Depicter::csrf()->getToken( \Depicter\Security\CSRF::EDITOR_ACTION ) . '&ID=' . $sliderID . '&status=draft|publish&gutenberg=true';
        echo '<iframe id="' . esc_attr( $iframeID ) . '" style="width: 1px;min-width: 100%;" src="' . esc_url( $iframeURL ) . '"></iframe>';
        echo "<script>iFrameResize({}, '#sliderIframe-" . $sliderID . "')</script>";
      } else {
        echo \Depicter::front()->render()->document( $sliderID );
      }
    } else {
        echo esc_html__('Please select a Depicter slider','depicter' );
    }
  }
}