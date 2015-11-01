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

add_action( 'init', 'create_post_type' );
