=== Simple Crumbs Redux ===
Contributors: scriptrunner
Donate link: http://www.dougsparling.org/wordpress-plugins/simple-crumbs-redux/
Tags: breadcrumbs, navigation
Requires at least: 2.7
Tested up to: 4.1.1
Stable tag: 1.1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html


Summary: Generates navigation bread crumbs in WordPress. Requires permalinks.

== Description ==

Simple Crumbs Redux is a resurrection of the Simple Crumbs plugin by Can Koluman (http://wordpress.org/plugins/simple-crumbs/) 0.2.5 codebase. Version 1.0.0 of Simple Crumbs Redux fixes a fatal error generated when using PHP 5.4 and greater (and a deprecation warning with PHP 5.3). I'm keeping the original short code (though you can also use [simple_crumbs_redux]) so this plugin will work as a drop in replacement for Simple Crumbs.

Simple Crumbs Redux - Generates a breadcrumb trail for pages and blog entries. Requires use of permalinks and PHP >= 4.1.0. Tested up to WordPress 3.6 and PHP 5.5.

**Notes:** link/crumb information from $query_string, page/post information from $post, using permalink info for making links, using permalink structure for bootstrapping unrolled recursions (deepest to topmost).
Author URI: http://www.dougsparling.org



**Usage Examples:**
---------------------------------- 
*  Usage: `<?php echo do_shortcode('[simple_crumbs root="Home" /]') ?>`
*  Usage: `[simple_crumbs root="Some Root" /]`
*  Usage: `[simple_crumbs /]`


**Sample Output:** (with Home as 'root')
----------------------------------
1. Home > Section > Subsection
1. Home > Blog > 2013 > 08 > 23 > Blog Title
1. Home > Search Results
1. Home > Tag > Tag Name


== Installation ==

A. Configuration Options
----------------------------------
1. Document Root Crumb name passed to function.
1. Following css class may be defined externally: navCrumb.lnk if needed.
1. Separator may be chosen.


B. Installation
----------------------------------
1. Copy to plugins (`/wp-content/plugins/`) folder
1. Usage:
* from php: `<?php echo do_shortcode('[simple_crumbs root="Home" /]') ?>`
* from html with document root: `[simple_crumbs root="Some Root" /]`
* from html without document root: `[simple_crumbs /]`


== Frequently Asked Questions ==

= Why another breadcrumbs plugin? =

A client of mine was using the Simple Crumbs and it wouldn't work once PHP was updated on his server. As Simple Crumbs hadn't been updated since 2009 and I needed to get my client going, I just created Simple Crumbs Redux and got him going. I made a few minor improvements over the original plugin and will work as a drop in replacement. Whether I add any other features depends on my needs and if I get any requests from users.


== Screenshots ==

1. No UI. No screenshots available.


== Changelog ==

= 1.1.0 =
* Changed titles array to class property.

= 1.0.2 =
* Changed contributers name to my WordPress.org username.

= 1.0.1 =
* Upated readme to pass validation.

= 1.0.0 =
* Initial Release.
* Fixed 'PHP Fatal error: Call-time pass-by-reference has been removed' with PHP 5.4 and greater.
* Converted plugin from functional style to Object Oriented.
* Repaced sc_unpack_query_string function with WordPress core function wp_parse_args().

== Upgrade Notice ==

= 1.1.0 =
* All crumbs between root page and current page now display page title instead of permalink.
