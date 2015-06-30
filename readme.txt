=== Plugin Name ===
Contributors: marclarr
Tags: fast, page, pages, post, posts, switch, admin, edit, easy, quick
Requires at least: 3.1
Tested up to: 4.2.2
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Save time switching between posts & pages in admin.

== Description ==

This plugin adds a metabox to the edit screen for posts and pages. The metabox lets you quickly switch between all available posts and pages using the Select2 jQuery plugin. No need to visit "All Pages" first.

If this plugin saves you time, please consider returning the favor with a good rating. Thanks.

== Screenshots ==

1. A quick preview of the fast page switch metabox and it's functionality.

== Changelog ==

= 1.3.0 =
* Fixed a bug were select2.js wasn't not enqueued on post edit screens.

= 1.2.9 =
* Added posts to the page switch dropdown.
* Included a filter called "fps_get_posts_by_post_status" to change the post_status argument for get_posts().

= 1.1.9 =
* Added Select2 version 3 backwards compatibility.
* Changed Select2 script handles to a generic name to prevent clashes with other plugins using Select2.

= 1.1.7 =
* Addressed a bug where Select2 was getting stuck on the new value when a page change was prevented due to unsaved changes.
* Fixed a bug where select2.js wasn't being loaded for "add new" pages.
* Included more post_status pages: private, draft, future and pending are now available via the dropdown. This also includes password protected pages.
* Included a filter called "fps_get_pages_by_post_status" to change the post_status argument for get_pages().

= 1.1.3 =
* Included the [Select2](https://github.com/select2/select2) version 4 jQuery plugin.
* Lowered the minimum required WP version from 4 to the accurate version.
* Changed the metabox title to the plugin's name.

= 1.0.1 =
* Added a little bit of code documentation.
* Updated the readme.

= 1.0.0 =
* Initial Release