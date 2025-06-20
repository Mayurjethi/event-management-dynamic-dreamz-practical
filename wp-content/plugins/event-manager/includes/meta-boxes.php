<?php
if (! defined('ABSPATH')) exit;

// Register meta boxes
function em_add_event_meta_boxes()
{
     add_meta_box(
          'em_event_details',
          'Event Details',
          'em_event_details_meta_box_callback',
          'event',
          'normal',
          'default'
     );
}
add_action('add_meta_boxes', 'em_add_event_meta_boxes');

// Meta box HTML
function em_event_details_meta_box_callback($post)
{
     // Nonce for security
     wp_nonce_field(basename(__FILE__), 'em_event_nonce');

     // Get existing values
     $duration_start = get_post_meta($post->ID, '_em_duration_start', true);
     $duration_end = get_post_meta($post->ID, '_em_duration_end', true);
     $organizer = get_post_meta($post->ID, '_em_organizer', true);
     $organizer_contact = get_post_meta($post->ID, '_em_organizer_contact', true);
     $location = get_post_meta($post->ID, '_em_location', true);
     $event_type = get_post_meta($post->ID, '_em_event_type', true);
     $conference_website = get_post_meta($post->ID, '_em_conference_website', true);

?>
     <p>
          <label for="em_duration_start"><strong>Event Start:</strong></label><br>
          <input type="datetime-local" id="em_duration_start" name="em_duration_start" value="<?php echo esc_attr($duration_start); ?>" />
     </p>
     <p>
          <label for="em_duration_end"><strong>Event End:</strong></label><br>
          <input type="datetime-local" id="em_duration_end" name="em_duration_end" value="<?php echo esc_attr($duration_end); ?>" />
     </p>
     <hr>
     <p>
          <label for="em_organizer"><strong>Organizer Name:</strong></label><br>
          <input type="text" id="em_organizer" name="em_organizer" value="<?php echo esc_attr($organizer); ?>" style="width: 100%;" />
     </p>
     <p>
          <label for="em_organizer_contact"><strong>Organizer Contact:</strong></label><br>
          <input type="text" id="em_organizer_contact" name="em_organizer_contact" value="<?php echo esc_attr($organizer_contact); ?>" style="width: 100%;" />
     </p>
     <hr>
     <p>
          <label for="em_location"><strong>Event Location:</strong></label><br>
          <input type="text" id="em_location" name="em_location" value="<?php echo esc_attr($location); ?>" style="width: 100%;" />
     </p>
     <p
          <label for="em_conference_website_field">Website URL:</label>
          <input type="url" style="width:100%;" id="em_conference_website_field" name="em_conference_website_field" value="<?php echo esc_attr($conference_website); ?>" placeholder="https://example.com" />
          <p class="description">Only fill this if the event type is Conference.</p>
     <p>
          <label for="em_event_type"><strong>Event Type:</strong></label><br>
          <select name="em_event_type" id="em_event_type">
               <option value="">Select Type</option>
               <option value="conference" <?php selected($event_type, 'conference'); ?>>Conference</option>
               <option value="meetup" <?php selected($event_type, 'meetup'); ?>>Meetup</option>
               <option value="workshop" <?php selected($event_type, 'workshop'); ?>>Workshop</option>
          </select>
     </p>
<?php
}

// Save meta box data
function em_save_event_meta_boxes($post_id)
{
     if (! isset($_POST['em_event_nonce']) || ! wp_verify_nonce($_POST['em_event_nonce'], basename(__FILE__))) {
          return $post_id;
     }
     if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $post_id;
     if ('event' !== $_POST['post_type']) return $post_id;

     // Save fields
     update_post_meta($post_id, '_em_duration_start', sanitize_text_field($_POST['em_duration_start']));
     update_post_meta($post_id, '_em_duration_end', sanitize_text_field($_POST['em_duration_end']));
     update_post_meta($post_id, '_em_organizer', sanitize_text_field($_POST['em_organizer']));
     update_post_meta($post_id, '_em_organizer_contact', sanitize_text_field($_POST['em_organizer_contact']));
     update_post_meta($post_id, '_em_location', sanitize_text_field($_POST['em_location']));

     if (isset($_POST['em_conference_website_field'])) {
          update_post_meta($post_id, '_em_conference_website', esc_url_raw($_POST['em_conference_website_field']));
     }
     if (isset($_POST['em_event_type'])) {
          update_post_meta($post_id, '_em_event_type', sanitize_text_field($_POST['em_event_type']));
     }
}
add_action('save_post', 'em_save_event_meta_boxes');
