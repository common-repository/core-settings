<?php
/*
Plugin Name: Core Settings
Plugin URI: https://wordpress.org/plugins/core-settings/
Author: Korol Yuriy aka Shra <to@shra.ru>
Author URI: http://shra.ru
Version: 1.01
Requires at least: 2.5
Description: Fights against unnecessary WP core settings, removes needless metas and links from header html section.
Donate link: https://www.paypal.me/YuriyKing
Tags: settings, remove metas, remove emoji, remove rest api, remove needless meta, remove needless link, WP generator
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/
if (!class_exists('CoreSettingsClass')) {
class CoreSettingsClass
{
	//Actions
  public function __construct() {
		add_action('admin_menu', array($this, '_add_menu'));
		add_action('init', array($this, 'init_hook'), 10);
	}

	/* admin_menu hook */
	public function _add_menu() {
		add_options_page('Core settings', 'Core settings', 8, __FILE__, array($this, '_options_page'));
	}

  /* Options admin page */
  public function _options_page() {

		switch ($_POST['action']) {
		case 'save_cache_settings':
			//check & store new values
			$acs = $_POST;
			$acceptable_items = $this->default_settings();

			//sanitize flag list and values
			foreach ($acs as $k => $v) {
				if (!isset($acceptable_items[$k])) {
					unset($acs[$k]);
					continue;
				}

				if ($v) {
					$acs[$k] = 1;
				} else {
					unset($acs[$k]);
				}
			}

			update_option('core_settings_plugin', $acs);
			echo '<div class="updated"><p>' . __("Setting are updated.") . '</p></div>';
			break;
		}
		$acs = get_option('core_settings_plugin');
?>
<div class="wrap">
	<h2><?=__('Core settings');?></h2>
	<form method="post">
	<input type="hidden" name="action" value="save_cache_settings" />
	<fieldset class="options">
		<legend></legend>
		<table border=0 cellspacing=0 cellpadding=0 width=700>

		<?php
			print $this->template_render_option(array(
				'name'	 => 'remove_wp_generator',
				'values' => $acs,
				'title'  => 'Remove META - WP generator.',
				'description' => 'Wipes out meta tag declaring WP core version number in header section.'
			));

			print $this->template_render_option(array(
				'name'	 => 'remove_wlwmanifest_link',
				'values' => $acs,
				'title'  => 'Remove WLW manifest LINK.',
				'description' => 'Wipes out link tag declaring WLW manifest in header section.'
			));

			print $this->template_render_option(array(
				'name'	 => 'remove_wp_shortlink',
				'values' => $acs,
				'title'  => 'Remove WP Shortlink.',
				'description' => 'Wipes out link tag declaring WP short URL for current page.'
			));

			print $this->template_render_option(array(
				'name'	 => 'remove_adjacent_posts_rel_link',
				'values' => $acs,
				'title'  => 'Remove Adjacent posts rel link.',
				'description' => 'Wipes out link tag declaring the relational links for the posts adjacent to the current post.'
			));

			print $this->template_render_option(array(
				'name'	 => 'remove_feeds',
				'values' => $acs,
				'title'  => 'Remove feed links.',
				'description' => 'Wipes out link tag declaring links to the general feeds.'
			));

			print $this->template_render_option(array(
				'name'	 => 'remove_rsd',
				'values' => $acs,
				'title'  => 'Remove RSD link.',
				'description' => 'Wipes out link tag declaring the link to the Really Simple Discovery service endpoint (rel="EditURI").'
			));

			print $this->template_render_option(array(
				'name'	 => 'remove_rest_api',
				'values' => $acs,
				'title'  => 'Disable RESTful API.',
				'description' => 'Option disables all filters, events, metas connected with REST API.'
			));

      print $this->template_render_option(array(
        'name'   => 'remove_xmlrpc',
        'values' => $acs,
        'title'  => 'Disable XML RPC.',
        'description' => 'Option disables the XML-RPC API on a WordPress site running 3.5 or above.'
      ));   

			print $this->template_render_option(array(
				'name'	 => 'remove_emoji',
				'values' => $acs,
				'title'  => 'Disable Emoji.',
				'description' => 'Disable all filters connected with Emoji, stops Emoji processing.'
			));

		?>

		<tr><td colspan=3><h2><?=__('Deprecated settings')?></h2></td></tr>

		<?php

			print $this->template_render_option(array(
				'name'	 => 'remove_index_rel_link',
				'values' => $acs,
				'title'  => 'Remove Index rel LINK.',
				'description' => 'Wipes out link tag declaring WP site index relational link (rel="index"). Depreacted after 3.3.0.'
			));

			print $this->template_render_option(array(
				'name'	 => 'remove_parent_post_rel_link',
				'values' => $acs,
				'title'  => 'Remove Parent post rel LINK.',
				'description' => 'Wipes out link tag declaring WP site parent post relational link (rel="up"). Depreacted after 3.3.0.'
			));

			print $this->template_render_option(array(
				'name'	 => 'remove_start_post_rel_link',
				'values' => $acs,
				'title'  => 'Remove Start post rel LINK.',
				'description' => 'Wipes out link tag declaring WP relational link for the first post. Depreacted after 3.3.0.'
			));

		?>
		<tr>
			<td colspan="3">
			<input type="submit" class="button-primary" name="sbm" value="<?=__('Save changes')?>" />
			</td>
		</tr>
		</table>
	</fieldset>
	</form>
</div>
<?php
    }

	/* install actions (when activate first time) */
  static function install() {
		//set defaults
		add_option('core_settings_plugin', CoreSettingsClass::default_settings() );
	}

	/* Init hook */
	public function init_hook() {
		$acs = get_option('core_settings_plugin');

		if (!empty($acs['remove_wp_generator'])) {
			remove_action( 'wp_head', 'wp_generator' );
		}
		if (!empty($acs['remove_wlwmanifest_link'])) {
			remove_action( 'wp_head', 'wlwmanifest_link' );
		}
		if (!empty($acs['remove_wp_shortlink'])) {
			remove_action( 'wp_head', 'wp_shortlink_wp_head' );
		}
		if (!empty($acs['remove_adjacent_posts_rel_link'])) {
			remove_action( 'wp_head', 'adjacent_posts_rel_link', 10, 0 );
		}
		if (!empty($acs['remove_feeds'])) {
			remove_action( 'wp_head', 'feed_links_extra', 3 );
			remove_action( 'wp_head', 'feed_links', 2 );
		}
		if (!empty($acs['remove_rsd'])) {
			remove_action( 'wp_head', 'rsd_link' );
		}

    if (!empty($acs['remove_xmlrpc'])) {
      add_filter( 'xmlrpc_enabled', '__return_false' );
    }

		if (!empty($acs['remove_emoji'])) {
			remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
			remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
			remove_action( 'wp_print_styles', 'print_emoji_styles' );
			remove_action( 'admin_print_styles', 'print_emoji_styles' );
			remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
			remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
			remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
			add_filter( 'tiny_mce_plugins', array($this, 'disable_emojis_tinymce') );
		}

		if (!empty($acs['remove_rest_api'])) {
			// desable REST API
			add_filter('rest_enabled', '__return_false');

			// disable REST API filters
			remove_action( 'xmlrpc_rsd_apis', 'rest_output_rsd' );
			remove_action( 'wp_head', 'rest_output_link_wp_head', 10, 0 );
			remove_action( 'template_redirect', 'rest_output_link_header', 11, 0 );
			remove_action( 'auth_cookie_malformed', 'rest_cookie_collect_status' );
			remove_action( 'auth_cookie_expired', 'rest_cookie_collect_status' );
			remove_action( 'auth_cookie_bad_username', 'rest_cookie_collect_status' );
			remove_action( 'auth_cookie_bad_hash', 'rest_cookie_collect_status' );
			remove_action( 'auth_cookie_valid', 'rest_cookie_collect_status' );
			remove_filter( 'rest_authentication_errors', 'rest_cookie_check_errors', 100 );

			// disable REST API events
			remove_action( 'init', 'rest_api_init' );
			remove_action( 'rest_api_init', 'rest_api_default_filters', 10, 1 );
			remove_action( 'parse_request', 'rest_api_loaded' );

			// disable REST API other connected things
			remove_action( 'rest_api_init', 'wp_oembed_register_route');
			remove_filter( 'rest_pre_serve_request', '_oembed_rest_pre_serve_request', 10, 4 );
			remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
		}

		// dep 3.3.0
		if (!empty($acs['remove_start_post_rel_link'])) {
			remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );
		}
		if (!empty($acs['remove_parent_post_rel_link'])) {
			remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );
		}
		if (!empty($acs['remove_index_rel_link'])) {
			remove_action( 'wp_head', 'index_rel_link' );
		}
	}

	/**
	 * Filter function used to remove the tinymce emoji plugin.
	 *
	 * @param    array  $plugins
	 * @return   array             Difference betwen the two arrays
	 */
	public function disable_emojis_tinymce($plugins) {
		if ( is_array( $plugins ) ) {
			return array_diff( $plugins, array( 'wpemoji' ) );
		} else {
			return array();
		}
	}

	static function default_settings() {
		return array(
			'remove_wp_generator' => 1,
			'remove_wlwmanifest_link' => 1,
			'remove_wp_shortlink' => 1,
			'remove_adjacent_posts_rel_link' => 1,
			'remove_feeds' => 1,
			'remove_rsd' => 1,
			'remove_rest_api' => 1,
      'remove_xmlrpc' => 0,
			'remove_emoji' => 0,

			//deprecated
			'remove_start_post_rel_link' => 0,
			'remove_parent_post_rel_link' => 0,
			'remove_index_rel_link' => 0
		);
	}

	/* Render option as checkbox */
	static function template_render_option($vars) {
		return '<tr>
			<td colspan=3>
				<label>
					<input name="' . $vars['name'] . '" type="checkbox" value="1" '
						. (empty($vars['values'][$vars['name']]) ? '' : 'checked')
						. "/> "
						. __($vars['title'])
						. '
   			</label><br />
   			<small>' . __($vars['description']) . '</small>
			</td>
		</tr>
		<tr><td>&nbsp;</td></tr>';
	}

	/* uninstall hook */
   static function uninstall() {
		global $wpdb;
		delete_option('core_settings_plugin');
	}

}
}

register_uninstall_hook( __FILE__, array('CoreSettingsClass', 'uninstall'));
register_activation_hook( __FILE__, array('CoreSettingsClass', 'install') );

if (class_exists("CoreSettingsClass")) {
	$core_settings_obj = new CoreSettingsClass();
}
