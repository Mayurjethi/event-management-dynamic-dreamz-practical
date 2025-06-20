# Event Manager Practical Submission

This repository contains the **Event Manager** WordPress plugin and theme customizations as part of the practical
assessment.
It is designed for robust event management, REST API integration, frontend and backend UX, and code extensibility.

---

## What We Have Done

- **Built a custom plugin (`event-manager`)** for event management:
- Custom post type: Event (hierarchical)
- Hierarchical City taxonomy
- Custom meta fields (date, organizer, location, type, etc.)
- AJAX-powered frontend submission form with real-time JS validation and file upload
- REST API endpoint for events with filters (city, date, limit)
- Shortcode `[event_list]` that fetches events from the REST API and displays as grid/cards
- Admin event filters for event type, city, and date
- Settings page to configure shortcode defaults and generate copy-paste shortcode
- Widget for showing random events
- Capability and status management for events

- **Hello Elementor theme integration:**
- Registered and styled a custom sidebar
- Updated `single.php` and created a new `single-event.php` for modern, responsive event display with sidebar
- Ensured all plugin features are compatible with the theme

---

## Folder Structure

```
event-manager/
│
├── assets/
│ ├── css/
│ └── js/
│
├── includes/
│ ├── admin-filters.php
│ ├── ajax-event-submit.php
│ ├── event-capabilities.php
│ ├── event-list-settings.php
│ ├── event-rest-api.php
│ ├── event-shortcode.php
│ ├── event-status.php
│ ├── frontend-form.php
│ ├── meta-boxes.php
│ ├── post-types.php
│ ├── taxonomies.php
│ └── widget-random-event.php
│
├── event-manager.php # Main plugin file
├── readme.txt # Plugin documentation (see for detailed instructions)
```

---

## Setup & Instructions

### 1. **Plugin Installation**

- Upload the `event-manager` folder to your `wp-content/plugins/` directory.
- Activate the plugin from the WordPress admin dashboard.

### 2. **Theme Integration**

- The site uses the **Hello Elementor** theme.
- Place the provided `single.php` (and optionally `single-event.php`) in your theme folder to enable the modern layout
and sidebar.
- Register the "main-sidebar" in your theme's `functions.php` if not already present.

### 3. **Event List Shortcode**

- Use `[event_list]` on any page to display events.
- Configure default filters and generate a shortcode on the admin "Shortcode Settings" page under the Events menu.

### 4. **Frontend Event Submission**

- Use `[em_event_form]` on any page to provide a user-friendly event submission form.
- Form supports required fields, conditional fields, AJAX validation, and image upload.

### 5. **REST API Usage**

- Fetch events at:
`https://your-site.com/wp-json/myplugin/v1/events?city=paris&start_date=2025-01-01`
- Supports filters: `city`, `start_date`, `end_date`, `limit`

---

## Live Demo

A test site will be provisioned at [https://tastewp.com/](https://tastewp.com/) and shared here for preview and testing.

Live Demo:
https://flippantaccount.s2-tastewp.com

Admin Access:
 Username: admin
 Password: JSIB3KnOidU

Event Manager Role Access:
 Username: Event Manager
 Email: developer.testing07+event@gmail.com
 Password: admin@123

---

## Provided Files & Database

- The full plugin folder with all source files as above
- Theme files as needed (`single.php`, sidebar registration)
- Database export (for sample data and taxonomy terms)

---

## Credits

Developed as a practical demonstration of custom WordPress development, RESTful integration, and modern admin/frontend
UX.

---

For detailed feature descriptions and usage, see `event-manager/readme.txt`.
