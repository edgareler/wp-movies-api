<?php
/*
Plugin Name: WP Movies API
Description: JSON API for Movies with Home Page movie list. To get started: 1) Click the "Activate" link to the left of this description, 2) Go to the menu Movies -> Add new Movie, 3) Fill the Movie Details fields and publish the movie, 4) The movies will appear at the Front Page of your website.
Version:     0.1
Author:      Edgar Eler
Author URI:  http://edgar.systems
License:     GPLv3
License URI: https://www.gnu.org/licenses/gpl.html
*/

require_once(dirname( __FILE__ ) . '/includes/functions.php');

add_action( 'init', 'wma_init' );

add_action( 'pre_get_posts', 'wma_type_to_query' );

add_action( 'add_meta_boxes', 'wma_add_movies_metaboxes' );

add_action( 'save_post', 'wma_movie_details_save' );

add_filter('rewrite_rules_array', 'wma_create_rewrite_rules');

add_filter('query_vars', 'wma_add_query_vars');

add_filter('admin_init', 'wma_flush_rewrite_rules');

add_action( 'wp_enqueue_scripts', 'wma_scripts' );

add_filter( 'template_include', 'wma_template_include');
