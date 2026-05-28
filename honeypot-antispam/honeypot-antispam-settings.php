<?php
/*
Anti-spam settings code
used WordPress Settings API - http://codex.wordpress.org/Settings_API
*/

if (! defined('ABSPATH')) { // Avoid direct calls to this file and prevent full path disclosure
    exit;
}

function antispam_menu() // add menu item
{
    add_options_page('Honeypot Antispam', 'Honeypot Antispam', 'manage_options', 'honeypot-antispam', 'antispam_settings');
}

add_action('admin_menu', 'antispam_menu');

function antispam_admin_init()
{
    register_setting('antispam_settings_group', 'antispam_settings', [
        'type' => 'array',
        'sanitize_callback' => 'antispam_settings_validate',
        'default' => antispam_default_settings(),
    ]);

    add_settings_section('antispam_settings_automatic_section', '', 'antispam_section_callback', 'antispam_automatic_page');

    add_settings_field('save_spam_comments', __('Save spam comments', 'honeypot-antispam'), 'antispam_field_save_spam_comments_callback', 'antispam_automatic_page', 'antispam_settings_automatic_section');
}

add_action('admin_init', 'antispam_admin_init');

function antispam_settings_validate($input)
{
    $output = antispam_default_settings();

    // checkbox
    $output['save_spam_comments'] = (isset($input['save_spam_comments']) && $input['save_spam_comments']) ? 1 : 0;

    return $output;
}

function antispam_section_callback() // Anti-spam settings description
{
    echo '';
}

function antispam_field_save_spam_comments_callback()
{
    $settings = antispam_get_settings();
    echo '<label><input type="checkbox" name="antispam_settings[save_spam_comments]" '.checked(1, $settings['save_spam_comments'], false).' value="1" />';
    echo esc_html__('Save spam comments into spam section', 'honeypot-antispam').'</label>';
    echo '<p class="description">'.esc_html__('Useful for testing how the plugin works', 'honeypot-antispam').'. <a href="'.esc_url(admin_url('edit-comments.php?comment_status=spam')).'">'.esc_html__('View spam section', 'honeypot-antispam').'</a>.</p>';
}

function antispam_settings()
{
    $blocked_total = 0; // show 0 by default
    $antispam_stats = get_option('antispam_stats', []);
    if (isset($antispam_stats['blocked_total'])) {
        $blocked_total = $antispam_stats['blocked_total'];
    }
    ?>
    <div class="wrap">

        <h2><span class="dashicons dashicons-admin-generic"></span> Honeypot Antispam</h2>

        <div class="antispam-panel-info">
            <p style="margin: 0;">
                <span class="dashicons dashicons-chart-bar"></span>
                <strong><?php echo esc_html((int) $blocked_total); ?></strong> <?php echo esc_html__('spam comments were blocked by', 'honeypot-antispam'); ?>
                <a href="https://wordpress.org/plugins/honeypot-antispam/" target="_blank">Honeypot
                    Antispam</a> <?php echo esc_html__('plugin so far', 'honeypot-antispam'); ?>.
            </p>
        </div>

        <form method="post" action="options.php">
			<?php settings_fields('antispam_settings_group'); ?>
            <div class="antispam-group-automatic">
				<?php do_settings_sections('antispam_automatic_page'); ?>
            </div>
			<?php submit_button(); ?>
        </form>

    </div>
	<?php
}
