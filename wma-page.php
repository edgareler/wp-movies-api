<?php
/**
 * Template Name: Movies Page
 *
 * @package wp-movies-api
 * @since WP Movies API 0.1
 */
get_header();
?>
    <div id="primary" class="content-area" ng-app="wpMoviesApp">
        <main id="main" class="site-main" role="main">
            <article class="post-1 post type-post status-publish format-standard hentry category-uncategorized">
                <div class="entry-content" ng-controller="MoviesCtrl">
                    <div class="movies-container">
                        <div ng-repeat="movie in movies" repeat-completed>
                            <p ng-bind-html-unsafe=="movie.title"><h1>{{ movie.title }}</h1></p>
                            <p><img src="{{ movie.poster_url }}" alt="Poster"></p>
                            <p>Rating: {{ movie.rating }}</p>
                            <p>Year: {{ movie.year }}</p>
                            <p ng-bind-html="movie.short_description">Description: {{ movie.short_description }}</p>
                        </div>
                    </div>
                    <div ng-show="!movies.length">No movies found.</div>
                </div>
            </article>
        </main><!-- .site-main -->
    </div><!-- .content-area -->
<?php
get_footer();
?>
