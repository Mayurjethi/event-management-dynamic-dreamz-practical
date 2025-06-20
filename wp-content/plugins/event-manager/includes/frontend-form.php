<?php
if (! defined('ABSPATH')) exit;

// Enqueue frontend scripts and styles
function em_enqueue_frontend_assets()
{
    wp_enqueue_style('em-frontend', plugins_url('../assets/css/em-frontend.css', __FILE__));
    wp_enqueue_script('em-frontend', plugins_url('../assets/js/em-frontend.js', __FILE__), array('jquery'), null, true);
    wp_localize_script('em-frontend', 'em_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('em_event_form')
    ));
}
add_action('wp_enqueue_scripts', 'em_enqueue_frontend_assets');

// Shortcode for event form
function em_event_form_shortcode()
{
    ob_start();
?>
    <form id="em-event-form" method="post" enctype="multipart/form-data" novalidate>

        <?php wp_nonce_field('em_event_form', 'em_event_form_nonce'); ?>
        <p>
            <label>Event Title*</label><br>
            <input type="text" name="em_title">
        </p>
        <p>
            <label>Event Description*</label><br>
            <textarea name="em_content"></textarea>
        </p>
        <p>
            <label>Event Type*</label><br>
            <select name="em_event_type" id="em_event_type">
                <option value="">Select Type</option>
                <option value="conference">Conference</option>
                <option value="meetup">Meetup</option>
                <option value="workshop">Workshop</option>
            </select>
        </p>
        <div class="em-conditional-fields" id="em-type-conference" style="display:none;">
            <p><label>Website</label><br>
                <input type="url" name="em_conference_website" placeholder="https://example.com">
            </p>
        </div>
        <div class="em-conditional-fields" id="em-type-workshop" style="display:none;">
            <p><label>Workshop Level</label><br>
                <select name="em_workshop_level">
                    <option value="">Select</option>
                    <option value="beginner">Beginner</option>
                    <option value="intermediate">Intermediate</option>
                    <option value="advanced">Advanced</option>
                </select>
            </p>
        </div>
        <p>
            <label>City*</label><br>
            <?php
            $terms = get_terms([
                'taxonomy' => 'city',
                'hide_empty' => false,
            ]);
            if (!empty($terms) && !is_wp_error($terms)) {
                wp_dropdown_categories([
                    'taxonomy' => 'city',
                    'name' => 'em_city',
                    'orderby' => 'name',
                    'hide_empty' => false,
                    'hierarchical' => true,
                    'show_option_none' => 'Select City',
                    'value_field' => 'term_id',
                    'selected' => '',
                ]);
            } else {
                echo '<input type="text" name="em_city_text" placeholder="Enter City">';
            }
            ?>
        </p>
        <p>
            <label>Start Date/Time*</label><br>
            <input type="datetime-local" name="em_duration_start" id="em_duration_start">
        </p>
        <p>
            <label>End Date/Time*</label><br>
            <input type="datetime-local" name="em_duration_end" id="em_duration_end">
        </p>
        <p>
            <label>Organizer Name*</label><br>
            <input type="text" name="em_organizer">
        </p>
        <p>
            <label>Organizer Contact*</label><br>
            <input type="tel" name="em_organizer_contact" inputmode="tel" placeholder="e.g. +1234567890">

        </p>
        <p>
            <label>Event Location*</label><br>
            <input type="text" name="em_location">
        </p>
        <p>
            <label>Event Photo</label><br>
            <input type="file" name="em_photo" accept="image/*">
        </p>
        <p>
            <button type="submit">Submit Event</button>
        </p>
        <div id="em-form-result"></div>
    </form>
<?php
    return ob_get_clean();
}
add_shortcode('em_event_form', 'em_event_form_shortcode');
