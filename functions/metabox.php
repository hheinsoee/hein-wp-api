<?php
function package() {
     add_meta_box(
          'package',
          'Package Option',
          'packageInput', //callback function
          'post',//audios, post, page
          'side',
          'high');
} 
add_action('admin_menu', 'package');


function packageInput() {
    global $post;
    wp_nonce_field( 'package_info', 'package_nonce' );
    $meta = get_post_meta($post->ID, 'package_info', true);
    $duration = @isset($meta['duration']) ? @$meta['duration'] : '';
    $price = @isset($meta['price']) ? @$meta['price'] : '';
    ?>
    duration
    <input class="widefat" name="package_info[duration]" type="text" value="<?php echo esc_html($duration); ?>"/>
    price
    <input class="widefat" name="package_info[price]" type="number" value="<?php echo esc_html($price); ?>"/>
    <?php
}

add_action('save_post', 'package_save');
function package_save($post_id) {
    global $custom_meta_fields;
    if ( ! isset( $_POST['package_nonce'] ) || ! wp_verify_nonce( $_POST['package_nonce'], 'package_info' ) )
        return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return;
    if (!current_user_can('edit_post', $post_id))
        return;
    update_post_meta($post_id,'package_info',$_POST['package_info']);
}

// add_action('bulk_edit_custom_box',  'add_seo', 10, 2);
