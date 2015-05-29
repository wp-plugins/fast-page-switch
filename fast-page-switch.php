<?php

/*
Plugin Name: Fast Page Switch
Plugin URI: http://gravitysupport.com
Description: Lets you quickly switch pages in admin edit view.
Version: 1.1.9
Author: Marc Wiest
Author URI: http://gravitysupport.com
*/

if ( ! defined( 'WPINC' ) ) 
    wp_die( 'Please don\'t load this file directly.' );

define( 'FPS_I18N', 'fast-page-switch' );
define( 'FPS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'FPS_PLUGIN_URL', plugins_url( '/', __FILE__ ) );

/**
 * Admin Scripts & Styles
 */
add_action( 'admin_enqueue_scripts', 'fps_admin_scripts' );
function fps_admin_scripts() 
{
    $screen = get_current_screen();

    $post_type = $screen->post_type;
    $action = $screen->action;
    $action = empty($action) && isset($_GET['action']) ? $_GET['action'] : $action;

    if ( 'page' == $post_type && ('add' == $action || 'edit' == $action) ) : 
        wp_enqueue_style( 'select2', FPS_PLUGIN_URL.'assets/css/select2.css', array(), '4.0.0' );
        wp_enqueue_script( 'select2', FPS_PLUGIN_URL.'assets/js/select2.min.js', array('jquery'), '4.0.0' );
    endif;
}

/**
 * Metabox Content
 */
function fps_metabox_markup() 
{
    $args = array(
        'depth' => 0,
        'selected' => isset($_GET['post']) ? $_GET['post'] : 0,
    );

    $pages = get_pages( array( 
        'post_type' => 'page', 
        'post_status' => apply_filters( 'fps_get_pages_by_post_status', 'publish,private,draft,future,pending' ),
    ) );

    if ( ! empty($pages) ) : 

        $jquery = "<script>
            
            jQuery(document).ready(function($) { 
                'use strict';

                var fps = $('#fast-page-switch');
    
                fps.select2({
                    theme: 'classic'
                });

                fps.on( 'change', function (event) { 
                    event.preventDefault;

                    if ( fps.val() !== '".$args['selected']."' ) {

                        location_change( $(this).val() );

                        // - Select2 was getting stuck on the new value when a 
                        //   page change was prevented due to unsaved changes. 
                        // - To be backwards compatible with Select2 version 3,
                        //   the old method for reseting a value is also used.
                        fps.select2('val','".$args['selected']."'); // Select2 v3
                        fps.val('".$args['selected']."').trigger('change'); // Select2 v4
                    }
                });

                function location_change( post_id ) {
                    // var id = $(this).val();
                    var admin_url = '".trailingslashit(admin_url())."';
                    window.location.href = admin_url + 'post.php?post=' + post_id + '&action=edit';
                }

            });

        </script>";

        $html = '<select id="fast-page-switch" style="width:100%;">';
        $html .= walk_page_dropdown_tree( $pages, $args['depth'], $args );
        $html .= '</select>';

    endif;

    echo $jquery.$html;
}

/**
 * Add Metabox
 */
add_action( 'add_meta_boxes', 'fps_add_metabox' );
function fps_add_metabox() 
{
    add_meta_box( 
        'fps-metabox', 
        __( 'Fast Page Switch', FPS_I18N ), 
        'fps_metabox_markup', 
        'page', 
        'side', 
        'high', 
        null 
    );
}



