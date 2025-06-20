<?php
add_action('admin_menu', function () {
     add_submenu_page(
          'edit.php?post_type=event',
          'Event List Shortcode Settings',
          'Shortcode Settings',
          'manage_options',
          'event-list-settings',
          'myplugin_event_list_settings_page'
     );
});

add_action('admin_init', function () {
     register_setting('event_list_settings_group', 'event_list_defaults');
});

function myplugin_event_list_settings_page()
{
     $opts = get_option('event_list_defaults', []);
     $cities = get_terms(['taxonomy' => 'city', 'hide_empty' => false]);
?>
     <div class="wrap">
          <h1>Event List Shortcode Defaults</h1>
          <form method="post" action="options.php">
               <?php settings_fields('event_list_settings_group'); ?>
               <table class="form-table">
                    <tr>
                         <th scope="row">Default Cities</th>
                         <td>
                              <select name="event_list_defaults[city][]" multiple size="5">
                                   <?php foreach ($cities as $city): ?>
                                        <option value="<?php echo esc_attr($city->slug); ?>" <?php
                                                                                               if (!empty($opts['city']) && in_array($city->slug, (array)$opts['city'])) echo 'selected';
                                                                                               ?>><?php echo esc_html($city->name); ?></option>
                                   <?php endforeach; ?>
                              </select>
                              <br><small>Hold Ctrl (Cmd on Mac) to select multiple cities.</small>
                         </td>
                    </tr>
                    <tr>
                         <th scope="row">Default Start Date</th>
                         <td>
                              <input type="date" name="event_list_defaults[start_date]" value="<?php echo esc_attr($opts['start_date'] ?? ''); ?>">
                         </td>
                    </tr>
                    <tr>
                         <th scope="row">Default End Date</th>
                         <td>
                              <input type="date" name="event_list_defaults[end_date]" value="<?php echo esc_attr($opts['end_date'] ?? ''); ?>">
                         </td>
                    </tr>
                    <tr>
                         <th scope="row">Default Limit</th>
                         <td>
                              <input type="number" name="event_list_defaults[limit]" value="<?php echo esc_attr($opts['limit'] ?? 5); ?>" min="1" max="100">
                         </td>
                    </tr>
               </table>
               <?php submit_button(); ?>
          </form>
          <?php

          $cities = !empty($opts['city']) ? implode(',', (array)$opts['city']) : '';
          $start_date = !empty($opts['start_date']) ? $opts['start_date'] : '';
          $end_date   = !empty($opts['end_date']) ? $opts['end_date'] : '';
          $limit      = !empty($opts['limit']) ? $opts['limit'] : '';

          $shortcode = '[event_list';
          if ($cities) $shortcode .= ' city="' . esc_attr($cities) . '"';
          if ($start_date) $shortcode .= ' start_date="' . esc_attr($start_date) . '"';
          if ($end_date) $shortcode .= ' end_date="' . esc_attr($end_date) . '"';
          if ($limit) $shortcode .= ' limit="' . esc_attr($limit) . '"';
          $shortcode .= ']';
          ?>

          <div style="background: #f8f8f8; border: 1px solid #ddd; padding: 10px; margin-top: 20px;">
               <strong>Generated Shortcode:</strong>
               <code style="font-size:16px;"><?php echo $shortcode; ?></code>
               <br>
               <small>Copy this shortcode to use these settings on any page or post.</small>
          </div>
     </div>
<?php
}
