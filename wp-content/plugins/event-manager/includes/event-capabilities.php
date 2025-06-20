<?php
if (!defined('ABSPATH')) exit;

/**
 * Add the custom "Event Manager" role.
 */
function em_add_custom_roles()
{
     add_role(
          'event_manager',
          'Event Manager',
          [
               'read' => true,
               'edit_events' => true,
               'edit_others_events' => true,
               'publish_events' => true,
               'read_private_events' => true,
               'delete_events' => true,
               'delete_others_events' => true,
               'delete_private_events' => true,
               'delete_published_events' => true,
               'edit_private_events' => true,
               'edit_published_events' => true,
               'create_events' => true,
               'manage_event_settings' => true, // << new!
          ]
     );
}

/**
 * Remove the custom "Event Manager" role.
 */
function em_remove_custom_roles()
{
     remove_role('event_manager');
}

/**
 * Add event and city taxonomy capabilities, plus settings capability.
 */
function em_add_event_caps()
{
     $roles = ['administrator', 'editor', 'event_manager'];
     $post_caps = [
          'edit_event',
          'read_event',
          'delete_event',
          'edit_events',
          'edit_others_events',
          'publish_events',
          'read_private_events',
          'delete_events',
          'delete_others_events',
          'delete_private_events',
          'delete_published_events',
          'edit_private_events',
          'edit_published_events',
          'create_events',
     ];
     // City taxonomy caps (using custom taxonomy caps, see taxonomies.php)
     $tax_caps = [
          'manage_event_cities',
          'edit_event_cities',
          'delete_event_cities',
          'assign_event_cities',
     ];
     $settings_caps = [
          'manage_event_settings',
     ];
     foreach ($roles as $role_name) {
          $role = get_role($role_name);
          if ($role) {
               foreach ($post_caps as $cap) {
                    $role->add_cap($cap);
               }
               foreach ($tax_caps as $cap) {
                    $role->add_cap($cap);
               }
               foreach ($settings_caps as $cap) {
                    $role->add_cap($cap);
               }
          }
     }
}

/**
 * Remove event and city taxonomy capabilities.
 */
function em_remove_event_caps()
{
     $roles = ['administrator', 'editor', 'event_manager'];
     $post_caps = [
          'edit_event',
          'read_event',
          'delete_event',
          'edit_events',
          'edit_others_events',
          'publish_events',
          'read_private_events',
          'delete_events',
          'delete_others_events',
          'delete_private_events',
          'delete_published_events',
          'edit_private_events',
          'edit_published_events',
          'create_events',
     ];
     $tax_caps = [
          'manage_event_cities',
          'edit_event_cities',
          'delete_event_cities',
          'assign_event_cities',
     ];
     $settings_caps = [
          'manage_event_settings',
     ];
     foreach ($roles as $role_name) {
          $role = get_role($role_name);
          if ($role) {
               foreach ($post_caps as $cap) {
                    $role->remove_cap($cap);
               }
               foreach ($tax_caps as $cap) {
                    $role->remove_cap($cap);
               }
               foreach ($settings_caps as $cap) {
                    $role->remove_cap($cap);
               }
          }
     }
}

// Hook role and caps creation/removal to plugin activation/deactivation
register_activation_hook(EM_PLUGIN_DIR . 'event-manager.php', function () {
     em_add_custom_roles();
     em_add_event_caps();
});
register_deactivation_hook(EM_PLUGIN_DIR . 'event-manager.php', function () {
     em_remove_event_caps();
     em_remove_custom_roles();
});

// Also ensure capabilities are always present (safety)
add_action('admin_init', 'em_add_event_caps');
