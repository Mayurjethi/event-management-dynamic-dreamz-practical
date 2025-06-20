<?php
if ( ! defined( 'ABSPATH' ) ) exit;

function em_register_city_taxonomy() {
    $labels = array(
        'name'              => 'Cities',
        'singular_name'     => 'City',
        'search_items'      => 'Search Cities',
        'all_items'         => 'All Cities',
        'parent_item'       => 'Parent City',
        'parent_item_colon' => 'Parent City:',
        'edit_item'         => 'Edit City',
        'update_item'       => 'Update City',
        'add_new_item'      => 'Add New City',
        'new_item_name'     => 'New City Name',
        'menu_name'         => 'City',
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'public'            => true,
        'rewrite'           => array( 'slug' => 'city' ),
        'show_in_rest'      => true,
        'capabilities' => array(
            'manage_terms'  => 'manage_cities',
            'edit_terms'    => 'edit_cities',
            'delete_terms'  => 'delete_cities',
            'assign_terms'  => 'assign_cities',
        ),
    );
    register_taxonomy( 'city', array( 'event' ), $args );
}
add_action( 'init', 'em_register_city_taxonomy' );