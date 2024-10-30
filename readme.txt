=== Core Settings ===
Contributors: shra
Donate link: https://www.paypal.me/YuriyKing
Tags: settings, remove metas, remove emoji, remove rest api, needless links
Requires at least: 2.5
Tested up to: 5.4.1
Stable tag: 1.01
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Fights against unnecessary WP core settings, removes needless metas and links from header html section.

== Description ==

Create settings page in WP admin which allows to disable/remove such things like Emoji, RESTful API, needless LINKs and METAs.

There is the current settings list. So plugin allows:

* remove wp_generator tag
* remove wlwmanifest link
* remove wp_shortlink
* remove adjacent_posts_rel_link
* remove feed links
* remove RSD link
* disable REST API
* disable XML RPC
* disable EMOJI

== Installation ==

To install this plugin:

1. Download plugin
2. Extract and copy plugins files to /wp-content/plugins/core-settings directory
3. Activate it (enter to /wp_admin, then choose plugins page, press activate plugin)
4. Go to /wp-admin/options-general.php?page=core-settings%2Fcore-settings.php, check settings.
5. Enjoy !

== Screenshots ==

1. Core settings admin page.

== Changelog ==

= 1.01 = New option has been added: Disable XML RPC.

= 1.0 = Initial version.

== Upgrade Notice ==

No special notes is here for upgrade. Install and enjoy.
