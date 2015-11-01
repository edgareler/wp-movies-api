<?php

function create_post_type() {
    register_post_type('wma_movie', array(
        'labels' => array(
            'name' => __('Movies'),
            'singular_name' => __('Movie')
        ),
        'public' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-editor-video',
        'rewrite' => array('slug' => 'movies')
    ));
}
