<?php
add_action('rest_api_init', function () {
     register_rest_route('myplugin/v1', '/events', [
          'methods'  => 'GET',
          'callback' => 'myplugin_get_events',
          'permission_callback' => '__return_true',
     ]);
});

function myplugin_get_events($request)
{
     $args = [
          'post_type'      => 'event',
          'post_status'    => 'publish',
          'posts_per_page' => !empty($request['limit']) ? intval($request['limit']) : -1,
          'orderby'        => 'date',
          'order'          => 'DESC',
     ];
     // Filter by cities
     if (!empty($request['city'])) {
          $cities = array_map('sanitize_text_field', explode(',', $request['city']));
          $args['tax_query'] = [
               [
                    'taxonomy' => 'city',
                    'field'    => 'slug',
                    'terms'    => $cities,
               ]
          ];
     }
     // Filter by date range
     if (!empty($request['start_date']) || !empty($request['end_date'])) {
          $meta_query = [];
          if (!empty($request['start_date'])) {
               $meta_query[] = [
                    'key' => 'event_date',
                    'value' => $request['start_date'],
                    'compare' => '>=',
                    'type' => 'DATE'
               ];
          }
          if (!empty($request['end_date'])) {
               $meta_query[] = [
                    'key' => 'event_date',
                    'value' => $request['end_date'],
                    'compare' => '<=',
                    'type' => 'DATE'
               ];
          }
          if ($meta_query) $args['meta_query'] = $meta_query;
     }

     $events = get_posts($args);
     $data = [];
     foreach ($events as $event) {
          $cities = wp_get_post_terms($event->ID, 'city', ['fields' => 'names']);
          $event_date = get_post_meta($event->ID, 'event_date', true);
          $data[] = [
               'id'      => $event->ID,
               'title'   => get_the_title($event->ID),
               'content' => apply_filters('the_content', $event->post_content),
               'link'    => get_permalink($event->ID),
               'cities'  => $cities,
               'date'    => $event_date ?: get_the_date('', $event->ID),
          ];
     }
     return rest_ensure_response($data);
}
