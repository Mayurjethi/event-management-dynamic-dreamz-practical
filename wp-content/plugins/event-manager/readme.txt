Event Manager Plugin
====================

A custom event management plugin for WordPress to help you easily create, manage, display, and expose events with modern UI/UX and RESTful integration.

---------------------
## Features

- **Custom Event Post Type**  
  Manage events as a hierarchical custom post type (`event`), supporting statuses, meta fields, and admin columns.

- **Hierarchical City Taxonomy**  
  Assign events to cities (with parent/child support), display and filter with hierarchical dropdowns in admin and frontend.

- **Meta Boxes & Event Fields**  
  Additional fields for event date, organizer, location, event type (conference, meetup, workshop), city, and more.

- **Frontend Event Submission**  
  Modern, validated AJAX-powered frontend form for event submission with conditional fields, file upload, and real-time validation.

- **Sidebar Widget**  
  Displays random events (configurable number), with modern UI cards, event images, city & date info.

- **REST API Endpoint**  
  `/wp-json/myplugin/v1/events`  
  Fetches event data (with filters: city, date range, limit), ready for headless or JS usage.

- **Shortcode for Event Listing**  
  `[event_list]`  
  Fetches events from REST API, displays responsive event grid/cards. Settings page helps generate shortcode with default filters.

- **Admin Filters**  
  Enhanced admin event list with event type, date, and hierarchical city dropdown filters.

- **Settings Page**  
  Configure default event list filters (city, date, limit) and copy generated shortcode.

- **Role & Capability Management**  
  Separate file for custom capabilities for future user role extension.

---------------------
## Folder Structure

```
event-manager/
│
├── assets/
│   ├── css/
│   └── js/
│
├── includes/
│   ├── admin-filters.php         # Admin event filters (type, city, date)
│   ├── ajax-event-submit.php     # AJAX event submission handler (validation, save)
│   ├── event-capabilities.php    # (Optional) Custom roles/capabilities for events
│   ├── event-list-settings.php   # Admin settings page for event list/shortcode
│   ├── event-rest-api.php        # REST API: /wp-json/myplugin/v1/events
│   ├── event-shortcode.php       # [event_list] shortcode (lists events from REST API)
│   ├── event-status.php          # Custom statuses for event post type
│   ├── frontend-form.php         # Frontend form shortcode ([em_event_form])
│   ├── meta-boxes.php            # Custom meta boxes for event details
│   ├── post-types.php            # Registers 'event' post type
│   ├── taxonomies.php            # Registers 'city' taxonomy
│   └── widget-random-event.php   # Widget: Random events with modern UI
│
├── event-manager.php             # Main plugin loader file
├── readme.txt                    # This file
```

---------------------
## How To Use

**Setup:**
1. Upload the `event-manager` folder to `wp-content/plugins/`
2. Activate the plugin from the WordPress admin Plugins menu.

**Admin:**
- Add/edit events under the "Events" menu.
- Filter events by type, date, city in the admin list.
- Go to Events → Shortcode Settings to configure defaults and copy the `[event_list]` shortcode.

**Frontend:**
- Use `[em_event_form]` to add a modern event submission form to any page.
- Use `[event_list]` (with or without attributes) to list events anywhere.
- The sidebar widget (Random Events) can be added via Appearance → Widgets.

**REST API:**
- Fetch events at `/wp-json/myplugin/v1/events?city=paris&start_date=2025-01-01`
  (supports city, start_date, end_date, limit).

---------------------
## Theme Integration

- Tested and styled with the **Hello Elementor** theme.
- Includes a modern sidebar and single event template (see `single-event.php` and adjustments to `single.php` in your theme), displaying widgets and event info with a responsive layout.

---------------------
## To Extend / Next Steps

- Add more custom fields or taxonomies as needed.
- Customize templates for archive, single, and widget.
- Extend REST API for submissions/updating events.
- Add email notifications for submissions/approvals.
- Integrate with calendar plugins or external APIs.

---------------------
## Credits

Developed by Mayur for a practical event management solution, with extensibility, UX, and REST support in mind.

---------------------
## License

GPLv2 or later
