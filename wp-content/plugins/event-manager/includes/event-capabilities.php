<?php

if (!defined('ABSPATH')) exit;

function em_event_caps_list()
{
     return [
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
}

function em_add_event_manager_role()
{

     $event_tax_caps = [
          'manage_cities',
          'edit_cities',
          'delete_cities',
          'assign_cities',
     ];
     $role = get_role('event_manager');
     if ($role) {
          foreach ($event_tax_caps as $cap) {
               $role->add_cap($cap);
          }
     }
     $admin = get_role('administrator');
     if ($admin) {
          foreach ($event_tax_caps as $cap) {
               $admin->add_cap($cap);
          }
     }
}
add_action('init', 'em_add_event_manager_role');

function em_limit_admin_menu()
{
     if (current_user_can('event_manager') && !current_user_can('administrator')) {
          global $menu;
          // List of allowed menu slugs for Event Manager
          $allowed = ['edit.php?post_type=event', 'index.php', 'profile.php'];
          foreach ($menu as $k => $item) {
               if (!in_array($item[2], $allowed)) {
                    unset($menu[$k]);
               }
          }
     }
}
add_action('admin_menu', 'em_limit_admin_menu', 999);

// 4. (Optional/Best Practice) Clean up on plugin deactivation
function em_remove_event_manager_role()
{
     // Remove role (uncomment if you want to fully remove on deactivate)
     // remove_role('event_manager');
     // Remove event caps from admin (optional)
     // $admin = get_role('administrator');
     // if ($admin) {
     //     foreach (em_event_caps_list() as $cap) {
     //         $admin->remove_cap($cap);
     //     }
     // }
}
// register_deactivation_hook(__FILE__, 'em_remove_event_manager_role');