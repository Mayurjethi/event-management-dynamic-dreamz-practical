<?php

function em_handle_event_submission()
{
     // Check nonce
     if (! isset($_POST['nonce']) || ! wp_verify_nonce($_POST['nonce'], 'em_event_form')) {
          wp_send_json_error(['global' => 'Security check failed.']);
     }

     $errors = [];
     // Required fields
     $required = [
          'em_title' => 'Title is required.',
          'em_content' => 'Description is required.',
          'em_event_type' => 'Event type is required.',
          'em_duration_start' => 'Start date/time is required.',
          'em_duration_end' => 'End date/time is required.',
          'em_organizer' => 'Organizer is required.',
          'em_organizer_contact' => 'Contact info is required.',
          'em_location' => 'Location is required.',
     ];
     foreach ($required as $field => $msg) {
          if (empty($_POST[$field])) {
               $errors[$field] = $msg;
          }
     }

     // City required: dropdown or text
     if (
          (!isset($_POST['em_city']) || $_POST['em_city'] === '' || $_POST['em_city'] === '0')
          && empty($_POST['em_city_text'])
     ) {
          $errors['em_city'] = 'Please select or enter a city.';
     }

     // Date validation
     if (!isset($errors['em_duration_start']) && !isset($errors['em_duration_end'])) {
          $start = strtotime($_POST['em_duration_start']);
          $end = strtotime($_POST['em_duration_end']);
          if ($start < strtotime('-1 minute')) {
               $errors['em_duration_start'] = 'Start date/time cannot be in the past.';
          }
          if ($end <= $start) {
               $errors['em_duration_end'] = 'End date/time must be after start.';
          }
     }

     // Event type specific validation:
     if (isset($_POST['em_event_type'])) {
          if ($_POST['em_event_type'] === 'conference') {
               if (empty($_POST['em_conference_website'])) {
                    $errors['em_conference_website'] = 'Conference website is required.';
               } else {
                    // Simple URL validation
                    if (!filter_var($_POST['em_conference_website'], FILTER_VALIDATE_URL)) {
                         $errors['em_conference_website'] = 'Please enter a valid website URL.';
                    }
               }
          }
          if ($_POST['em_event_type'] === 'workshop') {
               if (empty($_POST['em_workshop_level'])) {
                    $errors['em_workshop_level'] = 'Workshop level is required.';
               }
          }
     }

     // Phone validation (basic)
     if (!empty($_POST['em_organizer_contact'])) {
          if (!preg_match('/^[\d\s\-\+\(\)]+$/', $_POST['em_organizer_contact'])) {
               $errors['em_organizer_contact'] = 'Please enter a valid phone number (digits, spaces, +, -, () allowed).';
          }
     }

     // City validation/assignment
     $city_term_id = null;
     if (!empty($_POST['em_city'])) {
          $city_term_id = intval($_POST['em_city']);
     } elseif (!empty($_POST['em_city_text'])) {
          $city_name = sanitize_text_field($_POST['em_city_text']);
          $existing_term = get_term_by('name', $city_name, 'city');
          if ($existing_term) {
               $city_term_id = $existing_term->term_id;
          } else {
               $new_term = wp_insert_term($city_name, 'city');
               if (! is_wp_error($new_term) && isset($new_term['term_id'])) {
                    $city_term_id = $new_term['term_id'];
               }
          }
     }
     if (!$city_term_id) {
          $errors['em_city'] = 'Please select or enter a city.';
     }

     // File validation
     if (!empty($_FILES['em_photo']['name'])) {
          $file = $_FILES['em_photo'];
          $allowed = ['image/jpeg', 'image/png', 'image/gif'];
          if (!in_array($file['type'], $allowed)) {
               $errors['em_photo'] = 'Photo must be a JPG, PNG, or GIF image.';
          }
          if ($file['size'] > 2 * 1024 * 1024) {
               $errors['em_photo'] = 'Photo must be less than 2MB.';
          }
     }

     if (!empty($errors)) {
          wp_send_json_error($errors);
     }

     // Insert Event
     $event_id = wp_insert_post([
          'post_type'   => 'event',
          'post_title'  => sanitize_text_field($_POST['em_title']),
          'post_content' => wp_kses_post($_POST['em_content']),
          'post_status' => 'pending_review',
     ]);
     if (!$event_id || is_wp_error($event_id)) {
          wp_send_json_error(['global' => 'Failed to save event.']);
     }

     // Set city taxonomy
     if ($city_term_id) {
          wp_set_object_terms($event_id, intval($city_term_id), 'city');
     }

     // Save meta
     update_post_meta($event_id, '_em_duration_start', sanitize_text_field($_POST['em_duration_start']));
     update_post_meta($event_id, '_em_duration_end', sanitize_text_field($_POST['em_duration_end']));
     update_post_meta($event_id, '_em_organizer', sanitize_text_field($_POST['em_organizer']));
     update_post_meta($event_id, '_em_organizer_contact', sanitize_text_field($_POST['em_organizer_contact']));
     update_post_meta($event_id, '_em_location', sanitize_text_field($_POST['em_location']));
     update_post_meta($event_id, '_em_event_type', sanitize_text_field($_POST['em_event_type']));
     if (!empty($_POST['em_conference_website'])) {
          update_post_meta($event_id, '_em_conference_website', esc_url_raw($_POST['em_conference_website']));
     }
     if (!empty($_POST['em_workshop_level'])) {
          update_post_meta($event_id, '_em_workshop_level', sanitize_text_field($_POST['em_workshop_level']));
     }

     // Handle file upload
     if (!empty($_FILES['em_photo']['name'])) {
          require_once(ABSPATH . 'wp-admin/includes/file.php');
          $upload = wp_handle_upload($_FILES['em_photo'], ['test_form' => false]);
          if (!isset($upload['error']) && isset($upload['url'])) {
               update_post_meta($event_id, '_em_photo', esc_url_raw($upload['url']));
          }
     }

     wp_send_json_success(['message' => 'Event submitted successfully and is pending review!']);
}

add_action('wp_ajax_em_submit_event', 'em_handle_event_submission');
add_action('wp_ajax_nopriv_em_submit_event', 'em_handle_event_submission');
