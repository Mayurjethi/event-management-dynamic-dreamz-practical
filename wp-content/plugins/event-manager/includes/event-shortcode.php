<?php
function myplugin_event_list_shortcode($atts)
{
     // Get defaults from settings page
     $defaults = get_option('event_list_defaults', []);
     $atts = shortcode_atts([
          'city' => !empty($defaults['city']) ? implode(',', (array)$defaults['city']) : '',
          'start_date' => $defaults['start_date'] ?? '',
          'end_date'   => $defaults['end_date'] ?? '',
          'limit'      => $defaults['limit'] ?? 10,
     ], $atts);

     $api_url = home_url('/wp-json/myplugin/v1/events');
     $args = [];
     if (!empty($atts['city'])) $args['city'] = $atts['city'];
     if (!empty($atts['start_date'])) $args['start_date'] = $atts['start_date'];
     if (!empty($atts['end_date'])) $args['end_date'] = $atts['end_date'];
     if (!empty($atts['limit'])) $args['limit'] = (int)$atts['limit'];
     if ($args) $api_url = add_query_arg($args, $api_url);

     $response = wp_remote_get($api_url);
     if (is_wp_error($response)) return '<p>Could not retrieve events.</p>';
     $events = json_decode(wp_remote_retrieve_body($response), true);
     if (!$events || !is_array($events)) return '<p>No events found.</p>';

     // Enqueue CSS only once per page
     static $em_ui_css_loaded = false;
     if (!$em_ui_css_loaded) {
          add_action('wp_footer', function () {
?>
               <style id="myplugin-event-list-css">
                    .myplugin-event-grid {
                         display: grid;
                         grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                         gap: 2rem;
                         margin: 2rem 0;
                    }

                    .myplugin-event-card {
                         background: #fff;
                         border-radius: 12px;
                         box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
                         padding: 1.5rem;
                         display: flex;
                         flex-direction: column;
                         transition: box-shadow .2s;
                    }

                    .myplugin-event-card:hover {
                         box-shadow: 0 6px 24px rgba(0, 0, 0, 0.14);
                    }

                    .myplugin-event-title {
                         font-size: 1.25rem;
                         font-weight: 600;
                         margin-bottom: .5rem;
                         color: #1c1e21;
                    }

                    .myplugin-event-meta {
                         font-size: 0.97rem;
                         color: #65676b;
                         margin-bottom: .75rem;
                    }

                    .myplugin-event-content {
                         font-size: 1rem;
                         margin-bottom: 1rem;
                         color: #222;
                    }

                    .myplugin-event-readmore {
                         margin-top: auto;
                         text-align: right;
                    }

                    .myplugin-event-readmore a {
                         background: #3B82F6;
                         color: #fff;
                         border: none;
                         border-radius: 6px;
                         padding: 0.5em 1em;
                         text-decoration: none;
                         font-size: 0.98rem;
                         transition: background .2s;
                    }

                    .myplugin-event-readmore a:hover {
                         background: #1d4ed8;
                    }

                    @media (max-width: 600px) {
                         .myplugin-event-card {
                              padding: 1rem;
                         }

                         .myplugin-event-title {
                              font-size: 1rem;
                         }
                    }
               </style>
<?php
          });
          $em_ui_css_loaded = true;
     }

     ob_start();
     echo '<div class="myplugin-event-grid">';
     foreach ($events as $event) {
          echo '<div class="myplugin-event-card">';
          echo '<div class="myplugin-event-title"><a href="' . esc_url($event['link']) . '">' . esc_html($event['title']) . '</a></div>';
          echo '<div class="myplugin-event-meta">';
          if (!empty($event['cities'])) {
               echo '<span><strong>City:</strong> ' . esc_html(implode(', ', $event['cities'])) . '</span><br>';
          }
          echo '<span><strong>Date:</strong> ' . esc_html($event['date']) . '</span>';
          echo '</div>';
          echo '<div class="myplugin-event-content">' . wp_kses_post(wp_trim_words($event['content'], 28, '...')) . '</div>';
          echo '<div class="myplugin-event-readmore"><a href="' . esc_url($event['link']) . '">View Event</a></div>';
          echo '</div>';
     }
     echo '</div>';
     return ob_get_clean();
}
add_shortcode('event_list', 'myplugin_event_list_shortcode');
