<?php

/**
 * Single Event Template with Icons, Organized Meta, and Sidebar
 * Place in your child theme as single-event.php
 * Make sure Font Awesome is available, or leave the CDN include in place.
 */

get_header();

if (have_posts()) : while (have_posts()) : the_post();

          // Meta fields
          $start = get_post_meta(get_the_ID(), '_em_duration_start', true);
          $end = get_post_meta(get_the_ID(), '_em_duration_end', true);
          $organizer = get_post_meta(get_the_ID(), '_em_organizer', true);
          $contact = get_post_meta(get_the_ID(), '_em_organizer_contact', true);
          $location = get_post_meta(get_the_ID(), '_em_location', true);
          $event_type = get_post_meta(get_the_ID(), '_em_event_type', true);
          $conference_website = get_post_meta(get_the_ID(), '_em_conference_website', true);
          $workshop_level = get_post_meta(get_the_ID(), '_em_workshop_level', true);
          $photo = get_post_meta(get_the_ID(), '_em_photo', true);
          $cities = wp_get_post_terms(get_the_ID(), 'city', ['fields' => 'names']);

          // Format dates
          $start_fmt = $start ? date_i18n('M d, Y H:i', strtotime($start)) : '';
          $end_fmt = $end ? date_i18n('M d, Y H:i', strtotime($end)) : '';
?>

          <!-- Font Awesome CDN for icons (remove if you enqueue globally) -->
          <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

          <style>
               .single-event-wrap {
                    display: flex;
                    flex-direction: row;
                    gap: 2.5rem;
                    max-width: 1200px;
                    margin: 3rem auto;
                    padding: 0 1rem;
               }

               .single-event-main {
                    flex: 1 1 0;
                    background: #fff;
                    border-radius: 16px;
                    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.09);
                    padding: 2.5rem 2rem 2rem 2rem;
                    display: flex;
                    flex-direction: column;
                    gap: 1.5rem;
               }

               .single-event-sidebar {
                    width: 340px;
                    max-width: 100%;
               }

               .single-event-img {
                    width: 100%;
                    max-width: 410px;
                    border-radius: 12px;
                    margin-bottom: 1.2rem;
                    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
                    object-fit: cover;
                    max-height: 260px;
               }

               .single-event-title {
                    font-size: 2rem;
                    font-weight: 700;
                    color: #18223a;
                    margin-bottom: .2em;
               }

               .single-event-meta {
                    font-size: 1.1rem;
                    color: #3a3a3a;
                    background: #f7fafc;
                    border-radius: 8px;
                    padding: 1.1rem 1.5rem 1.1rem 1.2rem;
                    display: flex;
                    flex-wrap: wrap;
                    gap: 1.8rem 2.8rem;
               }

               .single-event-meta span {
                    display: flex;
                    align-items: center;
                    margin-bottom: .2em;
                    color: #333;
                    gap: 0.7em;
               }

               .single-event-meta .fa-solid,
               .single-event-meta .fa-regular {
                    color: #3B82F6;
                    min-width: 1.1em;
                    font-size: 1.15em;
               }

               .single-event-content {
                    font-size: 1.13rem;
                    color: #222;
                    line-height: 1.7;
               }

               .single-event-extra {
                    background: #f2f4f7;
                    border-radius: 8px;
                    padding: 1.2rem 1.5rem;
                    font-size: 1.01rem;
                    color: #2a2e33;
                    margin-top: 1.2rem;
                    display: flex;
                    flex-direction: column;
                    gap: 0.8em;
               }

               .single-event-extra span,
               .single-event-extra div {
                    display: flex;
                    align-items: center;
                    gap: 0.6em;
               }

               .single-event-extra .fa-solid,
               .single-event-extra .fa-regular {
                    color: #6366F1;
                    min-width: 1.1em;
               }

               @media (max-width: 900px) {
                    .single-event-wrap {
                         flex-direction: column;
                    }

                    .single-event-sidebar {
                         width: 100%;
                         padding: 0;
                    }

                    .single-event-main {
                         padding: 1.2rem;
                    }
               }

               @media (max-width: 600px) {
                    .single-event-title {
                         font-size: 1.3rem;
                    }

                    .single-event-meta {
                         padding: .7rem 1rem;
                    }

                    .single-event-main {
                         padding: 1rem;
                    }
               }
          </style>

          <div class="single-event-wrap">

               <main class="single-event-main">
                    <?php if ($photo): ?>
                         <img src="<?php echo esc_url($photo); ?>" class="single-event-img" alt="<?php the_title_attribute(); ?>">
                    <?php endif; ?>

                    <div class="single-event-title"><?php the_title(); ?></div>

                    <div class="single-event-meta">
                         <?php if (!empty($cities)): ?>
                              <span><i class="fa-solid fa-location-dot"></i> <strong>City:</strong> <?php echo esc_html(implode(', ', $cities)); ?></span>
                         <?php endif; ?>
                         <?php if ($location): ?>
                              <span><i class="fa-solid fa-map-pin"></i> <strong>Venue:</strong> <?php echo esc_html($location); ?></span>
                         <?php endif; ?>
                         <?php if ($event_type): ?>
                              <span>
                                   <i class="fa-solid fa-tag"></i>
                                   <strong>Type:</strong> <?php echo esc_html(ucfirst($event_type)); ?>
                                   <?php if ($event_type === 'workshop' && $workshop_level): ?>
                                        (<?php echo esc_html(ucfirst($workshop_level)); ?>)
                                   <?php endif; ?>
                              </span>
                         <?php endif; ?>
                         <?php if ($start_fmt): ?>
                              <span><i class="fa-regular fa-calendar"></i> <strong>Start:</strong> <?php echo esc_html($start_fmt); ?></span>
                         <?php endif; ?>
                         <?php if ($end_fmt): ?>
                              <span><i class="fa-regular fa-calendar-check"></i> <strong>End:</strong> <?php echo esc_html($end_fmt); ?></span>
                         <?php endif; ?>
                    </div>

                    <div class="single-event-content"><?php the_content(); ?></div>

                    <div class="single-event-extra">
                         <?php if ($organizer): ?>
                              <span><i class="fa-solid fa-user"></i> <strong>Organizer:</strong> <?php echo esc_html($organizer); ?></span>
                         <?php endif; ?>
                         <?php if ($contact): ?>
                              <span><i class="fa-solid fa-envelope"></i> <strong>Contact:</strong> <?php echo esc_html($contact); ?></span>
                         <?php endif; ?>
                         <?php if ($event_type === 'conference' && $conference_website): ?>
                              <div>
                                   <i class="fa-solid fa-globe"></i>
                                   <strong>Website:</strong>
                                   <a href="<?php echo esc_url($conference_website); ?>" target="_blank" rel="noopener">
                                        <?php echo esc_html($conference_website); ?>
                                   </a>
                              </div>
                         <?php endif; ?>
                    </div>
               </main>

               <aside class="single-event-sidebar">
                    <?php
                    // Sidebar content (from your code)
                    if (is_active_sidebar('main-sidebar')) {
                         dynamic_sidebar('main-sidebar');
                    }
                    ?>
               </aside>
          </div>

<?php
     endwhile;
endif;
get_footer();
?>