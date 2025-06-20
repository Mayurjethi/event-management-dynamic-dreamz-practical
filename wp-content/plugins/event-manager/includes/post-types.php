<?php
if (! defined('ABSPATH')) exit;

function em_register_event_post_type()
{
    $labels = array(
        'name'               => 'Events',
        'singular_name'      => 'Event',
        'add_new'            => 'Add New',
        'add_new_item'       => 'Add New Event',
        'edit_item'          => 'Edit Event',
        'new_item'           => 'New Event',
        'all_items'          => 'All Events',
        'view_item'          => 'View Event',
        'search_items'       => 'Search Events',
        'not_found'          => 'No events found',
        'not_found_in_trash' => 'No events found in Trash',
        'menu_name'          => 'Events'
    );
    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'hierarchical'       => true,
        'supports'           => array('title', 'editor', 'thumbnail', 'page-attributes'),
        'has_archive'        => true,
        'rewrite'            => array('slug' => 'events'),
        'show_in_rest'       => true,
        'capability_type'    => 'event',
        'map_meta_cap'       => true,
        'capabilities'       => [
            'edit_post'              => 'edit_event',
            'read_post'              => 'read_event',
            'delete_post'            => 'delete_event',
            'edit_posts'             => 'edit_events',
            'edit_others_posts'      => 'edit_others_events',
            'publish_posts'          => 'publish_events',
            'read_private_posts'     => 'read_private_events',
            'delete_posts'           => 'delete_events',
            'delete_others_posts'    => 'delete_others_events',
            'delete_private_posts'   => 'delete_private_events',
            'delete_published_posts' => 'delete_published_events',
            'edit_private_posts'     => 'edit_private_events',
            'edit_published_posts'   => 'edit_published_events',
            'create_posts'           => 'create_events',
        ],
    );
    register_post_type('event', $args);
}
add_action('init', 'em_register_event_post_type');
