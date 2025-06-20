<?php
/*
Plugin Name: Event Manager
Description: Custom event management plugin for hierarchical events, cities, meta boxes, AJAX forms, widgets, roles, and REST API.
Version: 1.0
Author: Mayur Jethi
*/

if (! defined('ABSPATH')) exit;

// Define plugin path
define('EM_PLUGIN_DIR', plugin_dir_path(__FILE__));

// Includes
require_once EM_PLUGIN_DIR . 'includes/post-types.php';
require_once EM_PLUGIN_DIR . 'includes/taxonomies.php';
require_once EM_PLUGIN_DIR . 'includes/meta-boxes.php';
require_once EM_PLUGIN_DIR . 'includes/frontend-form.php';
require_once EM_PLUGIN_DIR . 'includes/admin-filters.php';
require_once EM_PLUGIN_DIR . 'includes/ajax-event-submit.php';
require_once EM_PLUGIN_DIR . 'includes/widget-random-event.php';
require_once EM_PLUGIN_DIR . 'includes/event-capabilities.php';
require_once EM_PLUGIN_DIR . 'includes/event-rest-api.php';
require_once EM_PLUGIN_DIR . 'includes/event-shortcode.php';
require_once EM_PLUGIN_DIR . 'includes/event-list-settings.php';
require_once EM_PLUGIN_DIR . 'includes/event-status.php';

// Activation hook
function em_activate_plugin()
{
     em_register_event_post_type();
     em_register_city_taxonomy();
     flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'em_activate_plugin');

// Deactivation hook
function em_deactivate_plugin()
{
     flush_rewrite_rules();
}
register_deactivation_hook(__FILE__, 'em_deactivate_plugin');
