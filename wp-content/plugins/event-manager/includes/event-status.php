<?php
// Register custom post status for events

add_action('init', function () {
    register_post_status('pending_review', [
        'label'                     => _x('Pending Review', 'post'),
        'public'                    => false,
        'exclude_from_search'       => true,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'label_count'               => _n_noop('Pending Review <span class="count">(%s)</span>', 'Pending Review <span class="count">(%s)</span>'),
    ]);
});

// Show the status in the admin post list filter dropdown
add_filter('display_post_states', function ($states, $post) {
     if (get_post_status($post->ID) === 'pending_review') {
          $states[] = __('Pending Review');
     }
     return $states;
}, 10, 2);

// Add to status dropdown in quick/bulk edit (for 'event' post type)
add_action('post_submitbox_misc_actions', function () {
     global $post;
     if ($post->post_type == 'event') {
          $status = $post->post_status;
?>
          <script>
               jQuery(document).ready(function($) {
                    var select = $("select#post_status");
                    select.append('<option value="pending_review" <?php selected($status, 'pending_review'); ?>>Pending Review</option>');
               });
          </script>
<?php
     }
});
