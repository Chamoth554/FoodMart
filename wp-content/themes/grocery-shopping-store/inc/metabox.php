<?php
/**
* Sidebar Metabox.
*
* @package Grocery Shopping Store
*/

$grocery_shopping_store_post_sidebar_fields = array(
    'global-sidebar' => array(
        'id'        => 'post-global-sidebar',
        'value' => 'global-sidebar',
        'label' => esc_html__( 'Global sidebar', 'grocery-shopping-store' ),
    ),
    'right-sidebar' => array(
        'id'        => 'post-left-sidebar',
        'value' => 'right-sidebar',
        'label' => esc_html__( 'Right sidebar', 'grocery-shopping-store' ),
    ),
    'left-sidebar' => array(
        'id'        => 'post-right-sidebar',
        'value'     => 'left-sidebar',
        'label'     => esc_html__( 'Left sidebar', 'grocery-shopping-store' ),
    ),
    'no-sidebar' => array(
        'id'        => 'post-no-sidebar',
        'value'     => 'no-sidebar',
        'label'     => esc_html__( 'No sidebar', 'grocery-shopping-store' ),
    ),
);

function grocery_shopping_store_category_add_form_fields_callback() {
    $image_id = null; ?>
    <div id="category_custom_image"></div>
    <input type="hidden" id="category_custom_image_url" name="category_custom_image_url">
    <div style="margin-bottom: 20px;">
        <span><?php esc_html_e('Category Image','grocery-shopping-store'); ?></span>
        <a href="#" class="button custom-button-upload" id="custom-button-upload"><?php esc_html_e('Upload Image','grocery-shopping-store'); ?></a>
        <a href="#" class="button custom-button-remove" id="custom-button-remove" style="display: none"><?php esc_html_e('Remove Image','grocery-shopping-store'); ?></a>
    </div>
    <?php 
}
add_action( 'category_add_form_fields', 'grocery_shopping_store_category_add_form_fields_callback' );

function grocery_shopping_store_custom_create_term_callback($term_id) {
    // add term meta data
    add_term_meta(
        $term_id,
        'term_image',
        esc_url($_REQUEST['category_custom_image_url'])
    );
}
add_action( 'create_term', 'grocery_shopping_store_custom_create_term_callback' );

function grocery_shopping_store_category_edit_form_fields_callback($ttObj, $taxonomy) {
    $term_id = $ttObj->term_id;
    $grocery_shopping_store_image = '';
    $grocery_shopping_store_image = get_term_meta( $term_id, 'term_image', true ); ?>
    <tr class="form-field term-image-wrap">
        <th scope="row"><label for="image"><?php esc_html_e('Image','grocery-shopping-store'); ?></label></th>
        <td>
        <?php if ( $grocery_shopping_store_image ): ?>
            <span id="category_custom_image">
               <img src="<?php echo $grocery_shopping_store_image; ?>" style="width: 100%"/>
            </span>
            <input type="hidden" id="category_custom_image_url" name="category_custom_image_url">
            <span>
                <a href="#" class="button custom-button-upload" id="custom-button-upload" style="display: none"><?php esc_html_e('Upload Image','grocery-shopping-store'); ?></a>
                <a href="#" class="button custom-button-remove"><?php esc_html_e('Remove Image','grocery-shopping-store'); ?></a>                    
            </span>
        <?php else: ?>
            <span id="category_custom_image"></span>
            <input type="hidden" id="category_custom_image_url" name="category_custom_image_url">
            <span>
               <a href="#" class="button custom-button-upload" id="custom-button-upload"><?php esc_html_e('Upload Image','grocery-shopping-store'); ?></a>
               <a href="#" class="button custom-button-remove" style="display: none"><?php esc_html_e('Remove Image','grocery-shopping-store'); ?></a>
            </span>
            <?php endif; ?>
        </td>
    </tr>
    <?php
}
add_action ( 'category_edit_form_fields', 'grocery_shopping_store_category_edit_form_fields_callback', 10, 2 );

function grocery_shopping_store_edit_term_callback($term_id) {
    $grocery_shopping_store_image = '';
    $grocery_shopping_store_image = get_term_meta( $term_id, 'term_image' );
    if ( $grocery_shopping_store_image )
    update_term_meta( 
        $term_id, 
        'term_image', 
        esc_url( $_POST['category_custom_image_url']) );
    else
    add_term_meta( 
        $term_id, 
        'term_image', 
        esc_url( $_POST['category_custom_image_url']) );
}
add_action( 'edit_term', 'grocery_shopping_store_edit_term_callback' );