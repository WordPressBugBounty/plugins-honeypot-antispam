<?php

if (! defined('ABSPATH')) { // Avoid direct calls to this file and prevent full path disclosure
    exit;
}

function antispam_admin_notice()
{
    global $pagenow;
    if ($pagenow == 'edit-comments.php') {
        $user_id = get_current_user_id();
        $antispam_info_visibility = get_user_meta($user_id, 'antispam_info_visibility', true);
        if ($antispam_info_visibility == 1 or $antispam_info_visibility == '') {
            $blocked_total = 0; // show 0 by default
            $antispam_stats = get_option('antispam_stats', []);
            if (isset($antispam_stats['blocked_total'])) {
                $blocked_total = $antispam_stats['blocked_total'];
            }

            $message = sprintf(
                /* translators: 1: number of blocked spam comments, 2: plugin link */
                esc_html__('%1$s spam comments were blocked by %2$s plugin so far.', 'honeypot-antispam'),
                '<strong>'.esc_html((int) $blocked_total).'</strong>',
                '<a href="https://wordpress.org/plugins/honeypot-antispam/">Honeypot Antispam</a>'
            );

            wp_admin_notice($message, [
                'type' => 'info',
                'additional_classes' => ['antispam-panel-info'],
                'dismissible' => false,
            ]);
        } // end of if($antispam_info_visibility)
    } // end of if($pagenow == 'edit-comments.php')
}

add_action('admin_notices', 'antispam_admin_notice');

function antispam_display_screen_option()
{
    global $pagenow;
    if ($pagenow == 'edit-comments.php') {
        $user_id = get_current_user_id();
        $antispam_info_visibility = get_user_meta($user_id, 'antispam_info_visibility', true);

        if ($antispam_info_visibility == 1 or $antispam_info_visibility == '') {
            $checked = 'checked="checked"';
        } else {
            $checked = '';
        }

        ?>
        <script>
            jQuery(function ($) {
                $('.antispam_screen_options_group').insertAfter('#screen-options-wrap #adv-settings');
            });
        </script>
        <form method="post" class="antispam_screen_options_group" style="padding: 20px 0 5px 0;">
			<?php wp_nonce_field('antispam_screen_option', 'antispam_screen_option_nonce'); ?>
            <input type="hidden" name="antispam_option_submit" value="1"/>
            <label>
                <input name="antispam_info_visibility" type="checkbox" value="1" <?php echo $checked; ?> />
				<?php esc_html_e('Anti-spam info', 'honeypot-antispam'); ?>
            </label>
            <input type="submit" class="button" value="<?php esc_attr_e('Apply', 'honeypot-antispam'); ?>"/>
        </form>
	<?php
    } // end of if($pagenow == 'edit-comments.php')
}

function antispam_register_screen_option()
{
    add_filter('screen_layout_columns', 'antispam_display_screen_option');
}

add_action('admin_head', 'antispam_register_screen_option');

function antispam_update_screen_option()
{
    if (! isset($_POST['antispam_option_submit'])) {
        return;
    }

    // verify CSRF nonce and capability before persisting user preference
    if (! isset($_POST['antispam_screen_option_nonce']) || ! wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['antispam_screen_option_nonce'])), 'antispam_screen_option')) {
        return;
    }

    if (! current_user_can('edit_posts')) {
        return;
    }

    $user_id = get_current_user_id();
    $visibility = (isset($_POST['antispam_info_visibility']) && absint($_POST['antispam_info_visibility']) === 1) ? 1 : 0;
    update_user_meta($user_id, 'antispam_info_visibility', $visibility);
}

add_action('admin_init', 'antispam_update_screen_option');
