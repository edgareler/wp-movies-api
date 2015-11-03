<?php
/**
 * Initialization of the plugin. Calls the function to create the custom post type.
 */
function wma_init(){
    wma_create_post_type();
}

/**
 * Creates the custom post type wma_movie.
 */
function wma_create_post_type() {
    register_post_type('wma_movie', array(
        'labels' => array(
            'name' => __('Movies'),
            'singular_name' => __('Movie'),
            'add_new' => __('Add New Movie'),
            'add_new_item' => __('Add New Movie'),
            'edit_item' => __('Edit Movie'),
            'new_item' => __('Add New Movie'),
            'view_item' => __('View Movie'),
            'search_items' => __('Search Movie'),
            'not_found' => __('No movies found'),
            'not_found_in_trash' => __('No movies found in trash')
        ),
        'public' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-editor-video',
        'rewrite' => array('slug' => 'movies'),
        'register_meta_box_cb' => 'wma_add_movies_metaboxes'
    ));
}

/**
 * Registers the Custom Post Type wma_movies to the main query.
 */
function wma_type_to_query( $query ) {
    if ( is_home() && $query->is_main_query() ){
        $query->set( 'post_type', array( 'post', 'wma_movie' ) );
    }

    return $query;
}

/**
 * Creates the meta_box for the additional data for a Movie.
 */
function wma_add_movies_metaboxes() {
    add_meta_box(
            'wma_movie_details',
            __('Movie Details', 'wma_movie_details'),
            'wma_movie_details_render',
            'wma_movie',
            'normal',
            'default'
    );
}

/**
 * Renders the meta_box created at wma_add_movies_metaboxes()
 */
function wma_movie_details_render() {
    global $post;

    wp_nonce_field('wma_movie_details_save', 'wma_movie_details_nonce');

    $poster_url = sanitize_text_field(
            get_post_meta($post->ID, '_wma_poster_url', true));
    $rating = absint(get_post_meta($post->ID, '_wma_rating', true));
    $year = absint(get_post_meta($post->ID, '_wma_year', true));
    $description = html_entity_decode(get_post_meta($post->ID,
            '_wma_description', true));

    echo '<p><label for="wma_poster_url">';
    _e('Poster URL', 'wma_poster_url');
    echo '</label></p>';

    echo '<p><input type="text" id="wma_poster_url" name="wma_poster_url"'
    . ' value="' . esc_attr($poster_url) . '" class="widefat" /></p>';

    echo '<p><label for="wma_rating">';
    _e('Rating', 'wma_rating');
    echo '</label></p>';

    echo '<p><label for="wma_rating_1">1</label> '
    . '<input type="radio" id="wma_rating_1" name="wma_rating"'
    . ' value="1" ' . ($rating === 1 ? 'checked' : '') . ' /> ';

    echo '<label for="wma_rating_2">2</label> '
    . '<input type="radio" id="wma_rating_2" name="wma_rating"'
    . ' value="2" ' . ($rating === 2 ? 'checked' : '') . ' /> ';

    echo '<label for="wma_rating_3">3</label> '
    . '<input type="radio" id="wma_rating_3" name="wma_rating"'
    . ' value="3" ' . ($rating === 3 ? 'checked' : '') . ' /> ';

    echo '<label for="wma_rating_4">4</label> '
    . '<input type="radio" id="wma_rating_4" name="wma_rating"'
    . ' value="4" ' . ($rating === 4 ? 'checked' : '') . ' /> ';

    echo '<label for="wma_rating_5">5</label> '
    . '<input type="radio" id="wma_rating_5" name="wma_rating"'
    . ' value="5" ' . ($rating === 5 ? 'checked' : '') . ' /></p>';

    echo '<p><label for="wma_year">';
    _e('Year', 'wma_year');
    echo '</label></p>';

    echo '<p><input type="text" id="wma_year" name="wma_year"'
    . ' value="' . esc_attr($year) . '" class="widefat" /></p>';

    echo '<p><label for="wma_description">';
    _e('Short Description', 'wma_description');
    echo '</label></p>';

    wp_editor($description, 'wma_description');
}

/**
 * Saves data from the meta_box created at wma_add_movies_metaboxes()
 */
function wma_movie_details_save($post_id) {
    if (!isset($_POST['wma_movie_details_nonce'])) {
        return;
    }

    if (!wp_verify_nonce($_POST['wma_movie_details_nonce'],
            'wma_movie_details_save')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (!isset($_POST['wma_poster_url'])
            || !isset($_POST['wma_rating'])
            || !isset($_POST['wma_year'])
            || !isset($_POST['wma_description'])) {
        return;
    }

    $poster_url = sanitize_text_field($_POST['wma_poster_url']);
    $rating = absint($_POST['wma_rating']);
    $year = absint($_POST['wma_year']);
    $description = wp_kses_post($_POST['wma_description']);

    update_post_meta($post_id, '_wma_poster_url', $poster_url);
    update_post_meta($post_id, '_wma_rating', $rating);
    update_post_meta($post_id, '_wma_year', $year);
    update_post_meta($post_id, '_wma_description', $description);
}

/**
 * Creates the rewrite rule to provide the JSON Object at "/movies-api" action
 */
function wma_create_rewrite_rules($rules){
    $newRule1 = array('movies-api' => 'index.php?movies-api=1');

    $newRules = $newRule1 + $rules;

    return $newRules;
}

/**
 * Add the "movies-api" string to the main query vars
 */
function wma_add_query_vars($qvars) {
    $qvars[] = 'movies-api';
    return $qvars;
}

/**
 * Remove and recreates the rewrite rules
 */
function wma_flush_rewrite_rules() {
    global $wp_rewrite;
    $wp_rewrite->flush_rules();
}

/**
 * Includes the custom templates for "/" (root) and "/movies-api"
 */
function wma_template_include($default_template) {
    global $wp, $wp_query;

    if ($wp->request == "") {
        return wma_home_movies();
    } elseif ($wp_query->get('movies-api')) {
        return wma_list_movies();
    }

    return $default_template;
}

/**
 * Generate the JSON Object for the movies
 */
function wma_list_movies(){
    $json_obj = array();

    $movies = array();

    $args = array( 'post_type' => 'wma_movie' );

    $results = new WP_Query( $args );

    $i = 0;

    while($results->have_posts()) {
        $results->the_post();

        $post_id = $results->post->ID;

        $post_title = get_the_title($post_id);
        $poster_url = sanitize_text_field(
                get_post_meta($post_id, '_wma_poster_url', true));
        $rating = absint(get_post_meta($post_id, '_wma_rating', true));
        $year = absint(get_post_meta($post_id, '_wma_year', true));
        $description = html_entity_decode(get_post_meta($post_id,
                '_wma_description', true));

        $movie = array(
            "id" => $post_id,
            "title" => $post_title,
            "poster_url" => $poster_url,
            "rating" => $rating,
            "year" => $year,
            "short_description" => $description
        );

        $movies[$i] = $movie;

        $i++;
    }

    $json_obj["data"] = $movies;

    header('Content-Type: application/json');
    echo json_encode($json_obj);
}

/**
 * Get the template for the Front Page
 */
function wma_home_movies(){
    require_once plugin_dir_path(dirname(__FILE__)) . 'wma-page.php';

    exit;
}

/**
 * Enqueue the scripts and styles used at the front-end
 */
function wma_scripts() {
    wp_enqueue_style('slick-css', plugin_dir_url(dirname(__FILE__)) . 'vendors/slick/slick.css', array());

    wp_enqueue_style('slick-theme-css', plugin_dir_url(dirname(__FILE__)) . 'vendors/slick/slick-theme.css', array('slick'));

    wp_enqueue_script('jquery');

    wp_enqueue_script('angular', plugin_dir_url(dirname(__FILE__)) . 'vendors/angular/angular.min.js', array(), '', true);

    wp_enqueue_script('slick', plugin_dir_url(dirname(__FILE__)) . 'vendors/slick/slick.min.js', array( 'jquery' ), '', true);

    wp_enqueue_script('app', plugin_dir_url(dirname(__FILE__)) . 'assets/scripts/app.js', array( 'angular', 'slick' ), '', true);
}
