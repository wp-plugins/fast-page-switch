<?php

/*
Plugin Name: Fast Page Switch
Plugin URI: http://gravitysupport.com
Description: Lets you quickly switch pages in admin edit view.
Version: 1.0.0
Author: Marc Wiest
Author URI: http://gravitysupport.com
*/

if ( ! defined( 'WPINC' ) ) 
    wp_die( 'Please don\'t load this file directly.' );

define( 'WPHX_I18N', 'gsfps' );

function gsfps_metabox_markup() 
{
    $args = array(
        'depth' => 0, 
        'selected' => 0, 
        'post_type' => 'page',
        'selected' => $_GET['post'], 
    );

    $pages = get_pages( $args );

    if ( ! empty($pages) ) : 

        $jquery = '<script> jQuery(document).ready(function($) { "use strict";';
        $jquery .= '$("#gsfps").change( function(event) {
                event.preventDefault;
                var id = $(this).val();
                var admin_url = "'.trailingslashit(admin_url()).'";
                window.location.href = admin_url + "post.php?post=" + id + "&action=edit";
            })';
        $jquery .= '}); </script>';

        $html = '<select name="page_id" id="gsfps">';
        $html .= walk_page_dropdown_tree( $pages, $args['depth'], $args );
        $html .= '</select>';

    endif;

    // @todo add note after continued use:
    // If this plugin saves you time, please return the favor with a good rating. 
    // Thanks

    echo $jquery.$html;
}
 
add_action( 'add_meta_boxes', 'gsfps_add_metabox' );
function gsfps_add_metabox() 
{
    add_meta_box( 
        'gsfps-metabox', 
        __( 'Change Page', WPHX_I18N ), 
        'gsfps_metabox_markup', 
        'page', 
        'side', 
        'high', 
        null 
    );
}



