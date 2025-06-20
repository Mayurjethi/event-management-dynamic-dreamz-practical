<?php

class EM_Random_Event_Widget extends WP_Widget
{
     public function __construct()
     {
          parent::__construct(
               'em_random_event_widget',
               __('Random Events', 'event-manager'),
               ['description' => __('Display 3 random events.', 'event-manager')]
          );
     }

     public function widget($args, $instance)
     {
          $query = new WP_Query([
               'post_type'      => 'event',
               'posts_per_page' => 3,
               'orderby'        => 'rand',
               'post_status'    => 'publish',
          ]);
          if ($query->have_posts()) {
               echo $args['before_widget'];
               echo "<div class='em-widget-event-list'>";
               while ($query->have_posts()) {
                    $query->the_post();
                    $cities = get_the_terms(get_the_ID(), 'city');
                    $date = get_post_meta(get_the_ID(), '_em_duration_start', true);
                    $date_fmt = $date ? date_i18n('M d, Y', strtotime($date)) : '';
                    $photo = get_post_meta(get_the_ID(), '_em_photo', true);

                    echo "<div class='em-widget-event-card'>";
                    if ($photo) {
                         echo "<a href='" . esc_url(get_permalink()) . "'><img src='" . esc_url($photo) . "' class='em-widget-event-thumb' alt='" . esc_attr(get_the_title()) . "'></a>";
                    }
                    echo "<div class='em-widget-event-info'>";
                    echo "<h4 class='em-widget-event-title'><a href='" . esc_url(get_permalink()) . "'>" . esc_html(get_the_title()) . "</a></h4>";
                    if ($date_fmt) {
                         echo "<div class='em-widget-meta'><i class='fa-regular fa-calendar'></i> " . esc_html($date_fmt) . "</div>";
                    }
                    if ($cities && !is_wp_error($cities)) {
                         echo "<div class='em-widget-meta'><i class='fa-solid fa-location-dot'></i> " . esc_html($cities[0]->name) . "</div>";
                    }
                    echo "<div class='em-widget-excerpt'>" . esc_html(wp_trim_words(get_the_content(), 14)) . "</div>";
                    echo "</div>"; // em-widget-event-info
                    echo "</div>"; // em-widget-event-card
               }
               echo "</div>"; // em-widget-event-list

               // Inline CSS (only once per page)
               static $em_random_event_css = false;
               if (!$em_random_event_css) {
?>
                    <style>
                         .em-widget-event-list {
                              display: flex;
                              flex-direction: column;
                              gap: 1.3rem;
                              margin: 0;
                         }

                         .em-widget-event-card {
                              display: flex;
                              gap: 1rem;
                              background: #fff;
                              border-radius: 10px;
                              box-shadow: 0 2px 14px rgba(0, 0, 0, 0.07);
                              padding: 1rem;
                              align-items: flex-start;
                              transition: box-shadow .2s;
                         }

                         .em-widget-event-card:hover {
                              box-shadow: 0 6px 24px rgba(0, 0, 0, 0.15);
                         }

                         .em-widget-event-thumb {
                              width: 80px;
                              height: 80px;
                              object-fit: cover;
                              border-radius: 8px;
                              flex-shrink: 0;
                              box-shadow: 0 1px 6px rgba(0, 0, 0, 0.10);
                         }

                         .em-widget-event-info {
                              flex: 1 1 0;
                         }

                         .em-widget-event-title {
                              font-size: 1.08rem;
                              margin: 0 0 .4em 0;
                              font-weight: 600;
                              color: #222;
                         }

                         .em-widget-meta {
                              font-size: .97rem;
                              color: #3B82F6;
                              margin-bottom: .2em;
                              display: flex;
                              align-items: center;
                              gap: .45em;
                         }

                         .em-widget-meta .fa-solid,
                         .em-widget-meta .fa-regular {
                              color: #3B82F6;
                         }

                         .em-widget-excerpt {
                              font-size: .98rem;
                              color: #444;
                              margin-top: .23em;
                         }

                         @media (max-width: 600px) {
                              .em-widget-event-card {
                                   flex-direction: column;
                                   align-items: stretch;
                              }

                              .em-widget-event-thumb {
                                   width: 100%;
                                   height: 110px;
                                   margin-bottom: .6em;
                              }
                         }
                    </style>
                    <!-- Font Awesome (remove if already loaded elsewhere) -->
                    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<?php
                    $em_random_event_css = true;
               }
               echo $args['after_widget'];
               wp_reset_postdata();
          }
     }
}

function em_register_random_event_widget()
{
     register_widget('EM_Random_Event_Widget');
}
add_action('widgets_init', 'em_register_random_event_widget');
