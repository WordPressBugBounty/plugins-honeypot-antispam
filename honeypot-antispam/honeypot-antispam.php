<?php
/*
    * Plugin Name:    Honeypot Anti-Spam
    * Plugin URI:     http://wordpress.org/plugins/honeypot-antispam/
    * Description:    No spam in comments. No captcha.
    * Version:        1.0.5
    * Author:         Raiola Networks
    * Author URI:     https://raiolanetworks.com/
    * Text Domain:    honeypot-antispam
    * Domain Path:    /languages
*/

// Avoid direct calls to this file and prevent full path disclosure
if (! defined('ABSPATH')) {
    exit;
}

define('ANTISPAM_PLUGIN_VERSION', '1.0.5');

include 'honeypot-antispam-functions.php';
include 'honeypot-antispam-settings.php';
include 'honeypot-antispam-info.php';

function antispam_enqueue_script()
{
    // WP flag to show comments on all pages
    global $withcomments;
    // load script only for pages with comments form
    if ((is_singular() || $withcomments) && comments_open()) {
        wp_enqueue_script('honeypot-antispam-script', plugins_url('/js/honeypot-antispam.js', __FILE__), [], ANTISPAM_PLUGIN_VERSION, true);
    }
}

add_action('wp_enqueue_scripts', 'antispam_enqueue_script');

add_action('init', 'antispam_load_textdomain');
function antispam_load_textdomain()
{
    load_plugin_textdomain('honeypot-antispam', false, dirname(plugin_basename(__FILE__)).'/languages');
}

function antispam_form_part()
{
    $rn = "\r\n"; // .chr(13).chr(10)

    // add honeypot-antispam fields only for not logged in users
    if (! is_user_logged_in()) {
        echo $rn.'<!-- Honeypot Antispam plugin v.'.ANTISPAM_PLUGIN_VERSION.' wordpress.org/plugins/honeypot-antispam/ -->'.$rn;
        // question (hidden with js)
        echo '		<p class="antispam-group antispam-group-q" style="clear: both;">
			<label>Current ye@r <span class="required">*</span></label>
			<input type="hidden" name="antspm-a" class="antispam-control antispam-control-a" value="'.date('Y').'" />
			<input type="text" name="antspm-q" class="antispam-control antispam-control-q" value="'.ANTISPAM_PLUGIN_VERSION.'" autocomplete="off" />
		</p>'.$rn;
        // empty field (hidden with css); trap for spammers because many bots will try to put email or url here
        echo '		<p class="antispam-group antispam-group-e" style="display: none;">
			<label>Leave this field empty</label>
			<input type="text" name="antspm-e-email-url-website" class="antispam-control antispam-control-e" value="" autocomplete="off" />
		</p>'.$rn;
    }
}

// add anti-spam inputs to the comment form
add_action('comment_form', 'antispam_form_part');

function antispam_check_comment($commentdata)
{
    $antispam_settings = antispam_get_settings();

    extract($commentdata);

    // logged in user is not a spammer
    if (! is_user_logged_in() && $comment_type !== 'pingback' && $comment_type !== 'trackback' && $comment_type !== 'webmention') {
        if (antispam_check_for_spam()) {
            if ($antispam_settings['save_spam_comments']) {
                antispam_store_comment($commentdata);
            }
            antispam_counter_stats();
            // die - do not send comment and show error message
            wp_die('Comment is a spam.');
        }
    }

    if ($comment_type == 'trackback') {
        if ($antispam_settings['save_spam_comments']) {
            antispam_store_comment($commentdata);
        }
        antispam_counter_stats();
        // die - do not send trackback and show error message
        wp_die('Trackbacks are disabled.');
    }

    // if comment does not looks like spam
    return $commentdata;
}

// without this check it is not possible to add comment in admin section
if (! is_admin()) {
    add_filter('preprocess_comment', 'antispam_check_comment', 1);
}

// add some links to plugin meta row
function antispam_plugin_meta($links, $file)
{
    if ($file == plugin_basename(__FILE__)) {
        $row_meta = [
            'support' => '<a href="https://raiolanetworks.es/anti-spam/" target="_blank">'.__('Honeypot Antispam - Support', 'honeypot-antispam').'</a>',
        ];
        $links = array_merge($links, $row_meta);
    }

    return (array) $links;
}

add_filter('plugin_row_meta', 'antispam_plugin_meta', 10, 2);
