<?php
if (!defined('ABSPATH')) exit;

// Helper for event type counts
function em_get_event_type_count($type)
{
     global $wpdb;
     $post_type = 'event';
     $meta_key = '_em_event_type';
     $sql = $wpdb->prepare(
          "SELECT COUNT(*) FROM $wpdb->postmeta m
         JOIN $wpdb->posts p ON p.ID = m.post_id
         WHERE m.meta_key = %s AND m.meta_value = %s
         AND p.post_type = %s AND p.post_status = 'publish'",
          $meta_key,
          $type,
          $post_type
     );
     return (int)$wpdb->get_var($sql);
}

add_action('restrict_manage_posts', function () {
     $post_type = isset($_GET['post_type']) ? $_GET['post_type'] : '';
     if ($post_type !== 'event') return;

     // Event Types with counts
     $event_types = [
          'conference' => __('Conference', 'event-manager'),
          'meetup'     => __('Meetup', 'event-manager'),
          'workshop'   => __('Workshop', 'event-manager'),
     ];
     $selected_type = isset($_GET['em_event_type_filter']) ? $_GET['em_event_type_filter'] : '';

     echo '<select name="em_event_type_filter">';
     echo '<option value="">' . esc_html__('All Event Types', 'event-manager') . '</option>';
     foreach ($event_types as $type_key => $type_label) {
          $count = em_get_event_type_count($type_key);
          printf(
               '<option value="%s"%s>%s (%d)</option>',
               esc_attr($type_key),
               selected($selected_type, $type_key, false),
               esc_html($type_label),
               $count
          );
     }
     echo '</select>';

     // Event Start Date
     $start_date = isset($_GET['em_event_start_date']) ? $_GET['em_event_start_date'] : '';
     echo '<input type="date" name="em_event_start_date" value="' . esc_attr($start_date) . '" placeholder="Start Date" />';

     // City taxonomy dropdown
     $taxonomy = 'city';
     $selected_city = isset($_GET[$taxonomy]) ? $_GET[$taxonomy] : '';
     $info_taxonomy = get_taxonomy($taxonomy);
     wp_dropdown_categories([
          'show_option_all' => "All {$info_taxonomy->label}",
          'taxonomy'        => $taxonomy,
          'name'            => $taxonomy,
          'orderby'         => 'name',
          'selected'        => $selected_city,
          'show_count'      => true,
          'hide_empty'      => false,
          'hierarchical'    => true,
     ]);
}, 10);

add_action('pre_get_posts', function ($query) {
     if (!is_admin() || !$query->is_main_query()) return;
     $post_type = $query->get('post_type');
     if ($post_type !== 'event') return;

     // Fix post_status for admin "All"
     $status = $query->get('post_status');
     if ($status === '' || $status === 'all') {
          $query->set('post_status', array('publish', 'future', 'draft', 'pending', 'private'));
     }

     $meta_query = [];
     $tax_query = [];

     // Add meta filters
     if (!empty($_GET['em_event_type_filter'])) {
          $meta_query[] = [
               'key' => '_em_event_type',
               'value' => sanitize_text_field($_GET['em_event_type_filter']),
               'compare' => '='
          ];
     }
     if (!empty($_GET['em_event_start_date'])) {
          $meta_query[] = [
               'key' => '_em_duration_start',
               'value' => sanitize_text_field($_GET['em_event_start_date']),
               'compare' => '>=',
               'type' => 'DATE'
          ];
     }

     // City taxonomy filter
     if (!empty($_GET['city']) && $_GET['city'] != 0) {
          $selected_city = intval($_GET['city']);
          $descendants = get_term_children($selected_city, 'city');
          $city_terms = array_merge([$selected_city], $descendants);

          $tax_query[] = [
               'taxonomy' => 'city',
               'field'    => 'term_id',
               'terms'    => $city_terms,
               'include_children' => false,
          ];
          $query->set('city', '');
     }

     if (!empty($meta_query)) $query->set('meta_query', $meta_query);
     if (!empty($tax_query)) $query->set('tax_query', $tax_query);

     // error_log(print_r($query->query_vars, true));
}, 999);

// Add a custom column for Event Type in the Events admin list
add_filter('manage_event_posts_columns', function ($columns) {
     $columns['em_event_type'] = 'Event Type';
     return $columns;
});

// Populate the custom column with the value from post meta
add_action('manage_event_posts_custom_column', function ($column, $post_id) {
     if ($column === 'em_event_type') {
          echo esc_html(get_post_meta($post_id, '_em_event_type', true));
     }
}, 10, 2);
