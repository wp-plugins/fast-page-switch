<?php

/*
Plugin Name: Fast Page Switch
Plugin URI: http://gravitysupport.com
Description: Lets you quickly switch pages in admin edit view.
Version: 1.3.1
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

    if ( ( 'page' == $post_type || 'post' == $post_type ) && ('add' == $action || 'edit' == $action) ) : 
        wp_enqueue_style( 'select2', FPS_PLUGIN_URL.'assets/css/select2.css', array(), '4.0.0' );
        wp_enqueue_script( 'select2', FPS_PLUGIN_URL.'assets/js/select2.min.js', array('jquery'), '4.0.0' );
    endif;
}

/**
 * Metabox Content
 */
function fps_metabox_markup() 
{
    $pages = get_pages( array( 
        'post_type' => 'page', 
        'post_status' => apply_filters( 'fps_get_pages_by_post_status', 'publish,private,draft,auto-draft,future,pending' ),
    ) );

    $posts = get_posts( array(
        'post_type' => 'post',
        'post_status' => apply_filters( 'fps_get_posts_by_post_status', array('private','draft','auto-draft','future','pending','publish') ),
        'posts_per_page' => -1,
    ) );

    $selected = '';
    $page_selected = 0;
    $post_selected = 0;
    foreach( $pages as $page ) : 

        if ( isset($_GET['post']) ) {

            $selected = $_GET['post'];
        
            if ( $page->ID == $selected ) {
                $page_selected = $selected;
            } else {
                $post_selected = $selected;
            }

        }

    endforeach;

    $args = array(
        'depth' => 0,
        'selected' => $page_selected, // isset($_GET['post']) ? $_GET['post'] : 0,
    );

    if ( !empty($pages) || !empty($posts) ) : 

        $jquery = "<script>
            
            jQuery(document).ready(function($) { 
                'use strict';

                var fps = $('#fast-page-switch');
    
                if ( typeof fps.select2() == 'object' ) {
                    fps.select2({
                        theme: 'classic',
                        placeholder: 'Switch'
                    });
                }

                fps.on( 'change', function (event) { 
                    event.preventDefault;

                    if ( fps.val() !== '".$selected."' ) {

                        location_change( $(this).val() );

                        // - Select2 was getting stuck on the new value when a 
                        //   page change was prevented due to unsaved changes. 
                        // - To be backwards compatible with Select2 version 3,
                        //   the old method for reseting a value is also used.
                        fps.select2('val','".$selected."'); // Select2 v3
                        fps.val('".$selected."').trigger('change'); // Select2 v4
                    }
                });

                function location_change( post_id ) {
                    var admin_url = '".trailingslashit(admin_url())."';
                    window.location.href = admin_url + 'post.php?post=' + post_id + '&action=edit';
                }

            });

        </script>";

        $html = '<select id="fast-page-switch" style="width:100%;">';
        $html .= '<option></option>';
        $html .= '<optgroup label="'.__('Pages',FPS_I18N).'">';
        $html .= walk_page_dropdown_tree( $pages, $args['depth'], $args );
        $html .= '</optgroup>';
        $html .= '<optgroup label="'.__('Posts',FPS_I18N).'">';
        foreach( $posts as $post ) : 
            $s = $post_selected == $post->ID ? ' selected="selected"' : '';
            $html .= '<option'.$s.' value="'.$post->ID.'">'.$post->post_title.'</option>';
        endforeach;
        $html .= '</optgroup>';
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
    if ( ! current_user_can('moderate_comments') )
        return; // exit if not admin or editor

    add_meta_box( 
        'fps-metabox-page', 
        __( 'Fast Page Switch', FPS_I18N ), 
        'fps_metabox_markup', 
        'page', 
        'side', 
        'high', 
        null 
    );

    add_meta_box( 
        'fps-metabox-post', 
        __( 'Fast Page Switch', FPS_I18N ), 
        'fps_metabox_markup', 
        'post', 
        'side', 
        'high', 
        null 
    );
}



