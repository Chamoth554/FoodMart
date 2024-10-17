<tr valign="top">
    <th scope="row" class="titledesc">
        <label for="<?php echo $option->get_id_attribute(); ?>"><?php echo $option->get_label(); ?></label>
    </th>
    <td class="forminp forminp-text">
        <?php 
        $class =  $option->get_arg('class') ?? '';
        ?>
        <button class="<?php echo esc_attr( $class );?>">
            <?php
            if ( $option->get_arg('icon') ) {
                echo Depicter\Utility\Sanitize::html( $option->get_arg('icon'), null, 'depicter/output' );
            }

            echo esc_html(  $option->get_arg('button_text') );
            ?>
        </button>
    </td>
</tr>