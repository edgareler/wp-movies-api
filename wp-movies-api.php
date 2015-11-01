<?php
/*
Plugin Name: WP Movies API
Description: JSON API for Movies with Home Page movie list
Version:     0.1
Author:      Edgar Eler
Author URI:  http://edgar.systems
License:     GPLv3
License URI: https://www.gnu.org/licenses/gpl.html
*/

require_once(dirname( __FILE__ ) . '/includes/functions.php');

add_action( 'init', 'wma_create_post_type' );

add_action( 'pre_get_posts', 'wma_type_to_query' );

add_action( 'add_meta_boxes', 'wma_add_movies_metaboxes' );

add_action( 'save_post', 'wma_movie_details_save' );

add_filter('rewrite_rules_array', 'wma_create_rewrite_rules');

add_filter('query_vars', 'wma_add_query_vars');

add_filter('admin_init', 'wma_flush_rewrite_rules');

add_action( 'template_redirect', 'wma_template_redirect_intercept' );