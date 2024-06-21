<?php
/*
Plugin Name: Recent Posts API
Description: Exposes a REST API endpoint to retrieve the most recent posts.
Version: 1.0
Author: Patrick Sullivan
*/

add_action('rest_api_init', function () {
    register_rest_route('recent-posts-api/v1', '/posts/', array(
        'methods' => 'GET',
        'callback' => 'get_recent_posts',
    ));
});

function get_recent_posts($data) {
    $args = array(
        'numberposts' => 10,
        'orderby' => 'post_date',
        'order' => 'DESC',
        'post_type' => 'post',
        'post_status' => 'publish',
    );

    $posts = get_posts($args);

    $response = array();

    foreach ($posts as $post) {
        $post_data = array(
            'id' => $post->ID,
            'title' => $post->post_title,
            'content' => $post->post_content,
            'excerpt' => $post->post_excerpt,
            'date' => $post->post_date,
            'author' => get_the_author_meta('display_name', $post->post_author),
            'permalink' => get_permalink($post->ID),
        );
        $response[] = $post_data;
    }

    return rest_ensure_response($response);
}