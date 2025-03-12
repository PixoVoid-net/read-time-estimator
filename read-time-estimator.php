<?php
/**
 * Plugin Name: Read Time Estimator
 * Plugin URI: https://pixovoid.net/
 * Description: A lightweight and optimized WordPress plugin that estimates and displays the reading time of posts.
 * Version: 1.0.1
 * Author: PixoVoid.net
 * Author URI: https://pixovoid.net/
 * License: MIT
 * License URI: https://opensource.org/licenses/MIT
 * Text Domain: read-time-estimator.
 */
if (!defined('ABSPATH')) {
    exit; // Prevent direct access.
}

/**
 * Calculate estimated reading time.
 *
 * @param string $content Post content.
 *
 * @return int Estimated reading time in minutes.
 */
function pixovoid_calculate_read_time($content)
{
    $word_count = str_word_count(wp_strip_all_tags($content));
    $reading_speed = 200; // Average reading speed in words per minute.

    return max(1, (int) ceil($word_count / $reading_speed));
}

/**
 * Update estimated read time on post save.
 *
 * @param int $post_id Post ID.
 */
function pixovoid_update_read_time_meta($post_id)
{
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE || wp_is_post_revision($post_id) || !current_user_can('edit_post', $post_id)) {
        return;
    }

    if (get_post_type($post_id) !== 'post') {
        return;
    }

    $post = get_post($post_id);
    $read_time = $post ? pixovoid_calculate_read_time($post->post_content) : 1;

    update_post_meta($post_id, '_pixovoid_read_time', absint($read_time));
}
add_action('save_post', 'pixovoid_update_read_time_meta');

/**
 * Get estimated read time from cache or meta.
 *
 * @param int|null $post_id Post ID.
 *
 * @return int Estimated read time in minutes.
 */
function pixovoid_get_read_time($post_id = null)
{
    $post_id = $post_id ?: get_the_ID();
    if (!$post_id) {
        return 1;
    }

    // Check object cache first
    $cached_time = wp_cache_get($post_id, 'pixovoid_read_time');
    if (false !== $cached_time) {
        return $cached_time;
    }

    $read_time = get_post_meta($post_id, '_pixovoid_read_time', true);
    if (empty($read_time)) {
        $post = get_post($post_id);
        $read_time = $post ? pixovoid_calculate_read_time($post->post_content) : 1;
    }

    // Store in cache
    wp_cache_set($post_id, absint($read_time), 'pixovoid_read_time');

    return absint($read_time);
}

/**
 * Display estimated read time above post content.
 *
 * @param string $content Post content.
 *
 * @return string Modified content.
 */
function pixovoid_insert_read_time_in_content($content)
{
    if (is_single() && in_the_loop() && is_main_query()) {
        $read_time = pixovoid_get_read_time();
        $time_text = sprintf(
            /* translators: %d is the number of minutes */
            esc_html__('Estimated reading time: %d minute%s', 'read-time-estimator'),
            $read_time,
             $read_time > 1 ? 's' : ''
        );

        $content = '<div class="pxd-read-time" style="font-size: 0.85em; color: #777; margin-bottom: 0.5em;">'.esc_html($time_text).'</div>'.$content;
    }

    return $content;
}
add_filter('the_content', 'pixovoid_insert_read_time_in_content');

/**
 * Shortcode to display estimated read time.
 *
 * Usage: [read_time]
 *
 * @param array       $atts    Shortcode attributes.
 * @param string|null $content Optional content for custom calculation.
 *
 * @return string Read time HTML.
 */
function pixovoid_read_time_shortcode($atts, $content = null)
{
    $read_time = is_null($content) ? pixovoid_get_read_time() : pixovoid_calculate_read_time($content);

    return sprintf(
        '<span class="pxd-read-time-shortcode" style="font-size: 0.85em; color: #777;">%s</span>',
        esc_html(sprintf(
            /* translators: %d is the number of minutes */
            __('Estimated reading time: %d minute%s', 'read-time-estimator'),
            $read_time,
             $read_time > 1 ? 's' : ''
        ))
    );
}
add_shortcode('read_time', 'pixovoid_read_time_shortcode');

/**
 * Display read time in post meta.
 */
function pixovoid_display_read_time_in_meta()
{
    if (is_single() && in_the_loop() && is_main_query()) {
        $read_time = pixovoid_get_read_time();
        echo sprintf(
            '<span class="pxd-read-time-meta" style="font-size: 0.85em; color: #777; margin-right: 1em;">%s</span>',
            esc_html(sprintf(
                /* translators: %d is the number of minutes */
                __('Estimated reading time: %d minute%s', 'read-time-estimator'),
                $read_time,
                 $read_time > 1 ? 's' : ''
            ))
        );
    }
}

/**
 * Append read time to post title.
 *
 * @param string   $title   Post title.
 * @param int|null $post_id Post ID.
 *
 * @return string Modified title.
 */
function pixovoid_append_read_time_to_title($title, $post_id = null)
{
    if (is_admin() || !is_single() || !in_the_loop() || !is_main_query()) {
        return $title;
    }

    $read_time = pixovoid_get_read_time($post_id ?: get_the_ID());

    return sprintf(
        '%s <div class="pxd-read-time-title" style="font-size: 0.8em; color: #777; margin-top: 0.2em;">%s</div>',
        $title,
        esc_html(sprintf(
            /* translators: %d is the number of minutes */
            __('Estimated reading time: %d minute%s', 'read-time-estimator'),
            $read_time,
             $read_time > 1 ? 's' : ''
        ))
    );
}
// Uncomment the following line to enable this feature:
// add_filter( 'the_title', 'pixovoid_append_read_time_to_title', 10, 2 );
