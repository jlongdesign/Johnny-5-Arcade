<?php
/**
 * Theme Name: Johnny 5 Arcade
 * Description: A retro 90's arcade gaming theme
 * Author: Johnny 5 Arcade
 * Version: 1.1
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Theme setup
function arcade_hub_setup() {
    // Add theme support
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo');
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ));

    // Register navigation menus
    register_nav_menus(array(
        'primary' => esc_html__('Primary Menu', 'arcade-hub'),
        'footer' => esc_html__('Footer Menu', 'arcade-hub'),
    ));

    // Add image sizes
    add_image_size('game-thumbnail', 300, 200, true);
    add_image_size('game-screenshot', 800, 600, true);
}
add_action('after_setup_theme', 'arcade_hub_setup');


// Enqueue scripts and styles
function arcade_hub_scripts() {
    // Bootstrap first
    // wp_enqueue_style('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css');
    // Your custom styles AFTER Bootstrap (higher priority)
    wp_enqueue_style('arcade-hub-style', get_stylesheet_uri(), array(), '1.0'); // Main stylesheet (empty but needed for theme)
    wp_enqueue_style('arcade-hub-main', get_template_directory_uri() . '/css/main.css', array('arcade-hub-style'), '1.0'); // Main site styles
    wp_enqueue_style('arcade-hub-responsive', get_template_directory_uri() . '/responsive.css', array('arcade-hub-main'), '1.0'); // Responsive styles
    wp_enqueue_style('arcade-hub-utilities', get_template_directory_uri() . '/css/utilities.min.css', array('arcade-hub-main'), '1.0'); // Utility styles
    wp_enqueue_style('arcade-hub-bootstrap-overrides', get_template_directory_uri() . '/css/bootstrap-overrides.css', array('arcade-hub-responsive'), '1.0'); // Bootstrap fixes
    
    // Load utility modules first (ADD THIS SECTION)
    wp_enqueue_script('arcade-hub-ajax-handlers', get_template_directory_uri() . '/js/utils/ajax-handlers.js', array('jquery'), '1.0', true);
    wp_enqueue_script('arcade-hub-console-utils', get_template_directory_uri() . '/js/utils/console-utils.js', array('jquery'), '1.0', true);
    wp_enqueue_script('arcade-hub-ui-helpers', get_template_directory_uri() . '/js/utils/ui-helpers.js', array('jquery'), '1.0', true);
    
    // Load feature modules (existing modules)
    wp_enqueue_script('arcade-hub-navigation', get_template_directory_uri() . '/js/modules/navigation.js', array('jquery'), '1.0', true);
     wp_enqueue_script('arcade-hub-game-modal', get_template_directory_uri() . '/js/modules/game-modal.js', array('jquery'), '1.0', true);
     wp_enqueue_script('arcade-hub-video-player', get_template_directory_uri() . '/js/modules/video-player.js', array('jquery'), '1.0', true);
    wp_enqueue_script('arcade-hub-theme-effects', get_template_directory_uri() . '/js/modules/theme-effects.js', array('jquery', 'arcade-hub-ajax-handlers'), '1.1', true);
    wp_enqueue_script('arcade-hub-game-emulator', get_template_directory_uri() . '/js/modules/game-emulator.js', array('jquery', 'arcade-hub-console-utils'), '1.0', true);
    wp_enqueue_script('arcade-hub-tv-controls', get_template_directory_uri() . '/js/modules/tv-controls.js', array('jquery', 'arcade-hub-theme-effects'), '1.0', true);
    
    // Load main coordinator last (UPDATE DEPENDENCIES)
    wp_enqueue_script('arcade-hub-main', get_template_directory_uri() . '/js/main.js', array(
        'jquery',
        'arcade-hub-console-utils',
        'arcade-hub-ajax-handlers',  // ADD THIS DEPENDENCY
        'arcade-hub-navigation',
        'arcade-hub-theme-effects',
        'arcade-hub-game-emulator',
        'arcade-hub-tv-controls'
    ), '1.0', true);
    
    // Localize script for AJAX (IMPORTANT - ADD THIS)
    wp_localize_script('arcade-hub-ajax-handlers', 'arcade_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'theme_url' => get_template_directory_uri(),
        'nonce' => wp_create_nonce('arcade_nonce')
    ));
}
add_action('wp_enqueue_scripts', 'arcade_hub_scripts');



// Create "Resources" category if it doesn't exist
// Render resource card function
function render_resource_card($post, $is_featured = false) {
    $difficulty = get_post_meta($post->ID, '_resource_difficulty', true) ?: 'beginner';
    $category_tags = get_the_tags($post->ID);
    $category = '';
    if ($category_tags) {
        foreach ($category_tags as $tag) {
            if (in_array($tag->name, ['hardware', 'software', 'wiring', 'cabinet', 'troubleshooting', 'maintenance'])) {
                $category = $tag->name;
                break;
            }
        }
    }
    
    $difficulty_icons = array(
        'beginner' => '🟢',
        'intermediate' => '🟡', 
        'advanced' => '🔴'
    );
    
    $category_icons = array(
        'hardware' => '🔧',
        'software' => '💻',
        'wiring' => '⚡',
        'cabinet' => '🏗️',
        'troubleshooting' => '🔍',
        'maintenance' => '🛠️'
    );
    
    $border_color = $is_featured ? '#ffff00' : '#00ffff';
    $featured_badge = $is_featured ? '<div style="position: absolute; top: 10px; right: 10px; background: linear-gradient(45deg, #ffd700, #ffed4e); color: #000; padding: 5px 10px; border-radius: 15px; font-size: 0.8rem; font-weight: bold;">⭐ FEATURED</div>' : '';
    
    ob_start();
    ?>
    <article class="resource-card" 
             style="background: rgba(255,255,255,0.05); border: 2px solid <?php echo $border_color; ?>; border-radius: 10px; padding: 25px; transition: all 0.3s ease; position: relative; cursor: pointer;" 
             data-title="<?php echo esc_attr(get_the_title($post->ID)); ?>"
             data-excerpt="<?php echo esc_attr(wp_trim_words(get_the_excerpt($post->ID), 20)); ?>"
             data-difficulty="<?php echo esc_attr($difficulty); ?>"
             data-category="<?php echo esc_attr($category); ?>"
             data-date="<?php echo esc_attr(get_the_date('Y-m-d', $post->ID)); ?>"
             data-reading-time="<?php echo esc_attr(estimate_reading_time(get_post_field('post_content', $post->ID))); ?>"
             onmouseover="this.style.transform='translateY(-5px)'; this.style.borderColor='<?php echo $is_featured ? '#ff6b6b' : '#ffff00'; ?>';" 
             onmouseout="this.style.transform='translateY(0)'; this.style.borderColor='<?php echo $border_color; ?>';">
        
        <?php echo $featured_badge; ?>
        
        <?php if (has_post_thumbnail($post->ID)) : ?>
            <div style="margin-bottom: 20px; border-radius: 8px; overflow: hidden;">
                <a href="<?php echo get_permalink($post->ID); ?>">
                    <?php echo get_the_post_thumbnail($post->ID, 'medium', array('style' => 'width: 100%; height: 200px; object-fit: cover;')); ?>
                </a>
            </div>
        <?php endif; ?>
        
        <h3 style="color: #ffff00; margin-bottom: 15px; font-size: 1.3rem;">
            <a href="<?php echo get_permalink($post->ID); ?>" style="color: inherit; text-decoration: none;">
                <?php echo get_the_title($post->ID); ?>
            </a>
        </h3>
        
        <div style="color: #cccccc; margin-bottom: 15px; font-size: 0.9em; display: flex; align-items: center; gap: 15px; flex-wrap: wrap;">
            <span>📅 <?php echo get_the_date('M j, Y', $post->ID); ?></span>
            <span>⏱️ <?php echo estimate_reading_time(get_post_field('post_content', $post->ID)); ?> min</span>
            <?php if ($difficulty) : ?>
                <span><?php echo $difficulty_icons[$difficulty]; ?> <?php echo ucfirst($difficulty); ?></span>
            <?php endif; ?>
            <?php if ($category) : ?>
                <span><?php echo $category_icons[$category]; ?> <?php echo ucfirst($category); ?></span>
            <?php endif; ?>
        </div>
        
        <div style="color: #ffffff; margin-bottom: 20px; line-height: 1.6;">
            <?php echo wp_trim_words(get_the_excerpt($post->ID), 15, '...'); ?>
        </div>
        
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
            <a href="<?php echo get_permalink($post->ID); ?>" 
               class="retro-button" 
               style="background: linear-gradient(45deg, #00ffff, #0080ff); border: none; padding: 10px 20px; border-radius: 5px; color: #000; text-decoration: none; font-weight: bold; transition: all 0.3s ease;">
                Read Guide →
            </a>
            
            <?php 
            $tags = get_the_tags($post->ID);
            if ($tags && count($tags) > 0) : ?>
                <div style="display: flex; gap: 5px; flex-wrap: wrap;">
                    <?php foreach (array_slice($tags, 0, 2) as $tag) : ?>
                        <span style="background: rgba(255,255,0,0.2); color: #ffff00; padding: 3px 8px; border-radius: 12px; font-size: 0.7rem;">
                            <?php echo $tag->name; ?>
                        </span>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </article>
    <?php
    return ob_get_clean();
}

// Ensure "Resources" category exists on theme setup
function j5_create_resources_category() {
    if (!get_cat_ID('resources')) {
        wp_insert_term('Resources', 'category', array(
            'slug'        => 'resources',
            'description' => 'User guides, how-to articles, and tutorials',
        ));
    }
}
add_action('after_setup_theme', 'j5_create_resources_category');



// Allow ROM file uploads in WordPress
function arcade_hub_allow_rom_uploads($mimes) {
    // ROM file extensions with proper MIME types
    $mimes['nes'] = 'application/octet-stream';
    $mimes['smc'] = 'application/octet-stream';
    $mimes['sfc'] = 'application/octet-stream';
    $mimes['gb'] = 'application/octet-stream';
    $mimes['gbc'] = 'application/octet-stream';
    $mimes['gba'] = 'application/octet-stream';
    $mimes['bin'] = 'application/octet-stream';
    $mimes['md'] = 'application/octet-stream';
    $mimes['gen'] = 'application/octet-stream';
    $mimes['z64'] = 'application/octet-stream';
    $mimes['n64'] = 'application/octet-stream';
    $mimes['v64'] = 'application/octet-stream';
    $mimes['cue'] = 'text/plain';
    $mimes['iso'] = 'application/octet-stream';
    
    // Alternative MIME types that some servers might prefer
    $mimes['rom'] = 'application/octet-stream';
    $mimes['zip'] = 'application/zip'; // For compressed ROMs
    
    return $mimes;
}
add_filter('upload_mimes', 'arcade_hub_allow_rom_uploads');

// Additional security check for ROM uploads
function arcade_hub_check_rom_upload($file, $filename, $mimes) {
    // Get file extension
    $filetype = wp_check_filetype($filename, $mimes);
    
    // ROM file extensions we want to allow
    $rom_extensions = array('nes', 'smc', 'sfc', 'gb', 'gbc', 'gba', 'bin', 'md', 'gen', 'z64', 'n64', 'v64', 'cue', 'iso', 'rom');
    
    if (in_array($filetype['ext'], $rom_extensions)) {
        $file['ext'] = $filetype['ext'];
        $file['type'] = $filetype['type'];
    }
    
    return $file;
}
add_filter('wp_check_filetype_and_ext', 'arcade_hub_check_rom_upload', 10, 4);

// Override file type checking for ROM files
function arcade_hub_override_file_types($types, $file, $filename, $mimes) {
    if (false !== strpos($filename, '.')) {
        $exploded = explode('.', $filename);
        $ext = strtolower(array_pop($exploded));
        
        // ROM file extensions
        $rom_extensions = array(
            'nes' => 'application/octet-stream',
            'smc' => 'application/octet-stream',
            'sfc' => 'application/octet-stream',
            'gb' => 'application/octet-stream',
            'gbc' => 'application/octet-stream',
            'gba' => 'application/octet-stream',
            'bin' => 'application/octet-stream',
            'md' => 'application/octet-stream',
            'gen' => 'application/octet-stream',
            'z64' => 'application/octet-stream',
            'n64' => 'application/octet-stream',
            'v64' => 'application/octet-stream',
            'cue' => 'text/plain',
            'iso' => 'application/octet-stream',
            'rom' => 'application/octet-stream'
        );
        
        if (array_key_exists($ext, $rom_extensions)) {
            $types['ext'] = $ext;
            $types['type'] = $rom_extensions[$ext];
            $types['proper_filename'] = $filename;
        }
    }
    
    return $types;
}
add_filter('wp_check_filetype_and_ext', 'arcade_hub_override_file_types', 10, 4);

// Additional upload security bypass for ROM files
function arcade_hub_pre_upload_filter($file) {
    // Get file extension
    $filename = $file['name'];
    $filetype = wp_check_filetype($filename);
    
    // ROM extensions we support
    $rom_extensions = array('nes', 'smc', 'sfc', 'gb', 'gbc', 'gba', 'bin', 'md', 'gen', 'z64', 'n64', 'v64', 'cue', 'iso', 'rom');
    
    if (in_array($filetype['ext'], $rom_extensions)) {
        // Override the type to ensure it passes WordPress security
        $file['type'] = 'application/octet-stream';
        
        // Remove any existing error
        if (isset($file['error'])) {
            unset($file['error']);
        }
    }
    
    return $file;
}
add_filter('wp_handle_upload_prefilter', 'arcade_hub_pre_upload_filter');

// Custom error messages for failed uploads
function arcade_hub_upload_error_messages($messages) {
    $messages['rom_upload_help'] = 'Having trouble uploading ROM files? Try renaming the file extension to .txt temporarily, upload it, then change it back in the media library.';
    return $messages;
}
add_filter('media_upload_tabs', 'arcade_hub_upload_error_messages');

// Alternative: Allow users to upload .txt files and rename them
function arcade_hub_handle_txt_roms($file, $filename) {
    // Check if this is a .txt file that might be a ROM
    if (pathinfo($filename, PATHINFO_EXTENSION) === 'txt' && 
        preg_match('/\.(nes|smc|sfc|gb|gbc|gba|bin|md|gen|z64|n64|v64|iso|rom)\.txt$/', $filename)) {
        
        // This is likely a ROM file renamed to .txt for upload
        $file['type'] = 'text/plain';
    }
    
    return $file;
}
add_filter('wp_check_filetype_and_ext', 'arcade_hub_handle_txt_roms', 10, 2);

// Enqueue media uploader for admin
function arcade_hub_admin_scripts($hook) {
    // Only enqueue on game edit pages
    if ($hook == 'post.php' || $hook == 'post-new.php') {
        global $post_type;
        if ($post_type == 'games') {
            wp_enqueue_media();
        }
    }
}
add_action('admin_enqueue_scripts', 'arcade_hub_admin_scripts');

// Create arcade-hub directory for ROM files
function arcade_hub_create_rom_directory() {
    $upload_dir = wp_upload_dir();
    $arcade_dir = $upload_dir['basedir'] . '/arcade-hub/roms/';
    
    if (!file_exists($arcade_dir)) {
        wp_mkdir_p($arcade_dir);
        
        // Create subdirectories for different console types
        $console_dirs = array('nes', 'snes', 'gameboy', 'genesis', 'arcade', 'n64', 'psx');
        foreach ($console_dirs as $console) {
            wp_mkdir_p($arcade_dir . $console);
        }
        
        // Create .htaccess file to allow ROM file downloads with CORS
        $htaccess_content = "# Allow ROM file downloads with CORS headers\n";
        $htaccess_content .= "<FilesMatch \"\.(nes|smc|sfc|gb|gbc|gba|bin|md|gen|z64|n64|v64|iso|rom)$\">\n";
        $htaccess_content .= "    # CORS headers for cross-origin access\n";
        $htaccess_content .= "    Header always set Access-Control-Allow-Origin \"*\"\n";
        $htaccess_content .= "    Header always set Access-Control-Allow-Methods \"GET, POST, OPTIONS\"\n";
        $htaccess_content .= "    Header always set Access-Control-Allow-Headers \"Content-Type, Authorization\"\n";
        $htaccess_content .= "    Header always set Access-Control-Max-Age \"3600\"\n";
        $htaccess_content .= "    \n";
        $htaccess_content .= "    # Content type and caching\n";
        $htaccess_content .= "    SetEnvIf Request_URI \"\.(nes|smc|sfc|gb|gbc|gba|bin|md|gen|z64|n64|v64|iso|rom)$\" no-gzip dont-vary\n";
        $htaccess_content .= "    Header set Content-Type \"application/octet-stream\"\n";
        $htaccess_content .= "    Header set Cache-Control \"public, max-age=31536000\"\n";
        $htaccess_content .= "</FilesMatch>\n";
        $htaccess_content .= "\n";
        $htaccess_content .= "# Handle OPTIONS requests for CORS\n";
        $htaccess_content .= "RewriteEngine On\n";
        $htaccess_content .= "RewriteCond %{REQUEST_METHOD} OPTIONS\n";
        $htaccess_content .= "RewriteRule ^(.*)$ $1 [R=200,L]\n";
        
        file_put_contents($arcade_dir . '.htaccess', $htaccess_content);
    }
}
add_action('after_setup_theme', 'arcade_hub_create_rom_directory');

// Register widget areas
function arcade_hub_widgets_init() {
    register_sidebar(array(
        'name'          => esc_html__('Sidebar', 'arcade-hub'),
        'id'            => 'sidebar-1',
        'description'   => esc_html__('Add widgets here.', 'arcade-hub'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ));
}
add_action('widgets_init', 'arcade_hub_widgets_init');




/**
 * Add Customizer Options for Livestream
 */
function arcade_hub_customize_register($wp_customize) {
    // Add Livestream Section
    $wp_customize->add_section('arcade_livestream', array(
        'title' => __('Livestream Settings', 'arcade-hub'),
        'priority' => 30,
        'description' => __('Configure your livestream embed for the homepage.', 'arcade-hub')
    ));

    // Livestream Enable/Disable
    $wp_customize->add_setting('livestream_enabled', array(
        'default' => false,
        'sanitize_callback' => 'wp_validate_boolean',
    ));

    $wp_customize->add_control('livestream_enabled', array(
        'label' => __('Enable Livestream', 'arcade-hub'),
        'section' => 'arcade_livestream',
        'type' => 'checkbox',
    ));

    // Livestream Title
    $wp_customize->add_setting('livestream_title', array(
        'default' => '🔴 Live Gaming Stream',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('livestream_title', array(
        'label' => __('Livestream Title', 'arcade-hub'),
        'section' => 'arcade_livestream',
        'type' => 'text',
    ));

    // Livestream Platform
    $wp_customize->add_setting('livestream_platform', array(
        'default' => 'youtube',
        'sanitize_callback' => 'arcade_hub_sanitize_select',
    ));

    $wp_customize->add_control('livestream_platform', array(
        'label' => __('Platform', 'arcade-hub'),
        'section' => 'arcade_livestream',
        'type' => 'select',
        'choices' => array(
            'youtube' => 'YouTube',
            'twitch' => 'Twitch',
        ),
    ));

    // YouTube Live URL/ID
    $wp_customize->add_setting('livestream_youtube_url', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control('livestream_youtube_url', array(
        'label' => __('YouTube Live URL or Video ID', 'arcade-hub'),
        'description' => __('Enter full YouTube URL or just the video ID', 'arcade-hub'),
        'section' => 'arcade_livestream',
        'type' => 'text',
        'active_callback' => 'arcade_hub_is_youtube_selected',
    ));

    // Twitch Channel
    $wp_customize->add_setting('livestream_twitch_channel', array(
        'default' => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('livestream_twitch_channel', array(
        'label' => __('Twitch Channel Name', 'arcade-hub'),
        'description' => __('Enter just the channel name (without twitch.tv/)', 'arcade-hub'),
        'section' => 'arcade_livestream',
        'type' => 'text',
        'active_callback' => 'arcade_hub_is_twitch_selected',
    ));

    // Add after the existing Twitch channel setting
    $wp_customize->add_setting('livestream_twitch_video_id', array(
        'default' => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('livestream_twitch_video_id', array(
        'label' => __('Twitch Video ID (for past streams)', 'arcade-hub'),
        'description' => __('Enter just the video ID numbers (e.g. 2158394856)', 'arcade-hub'),
        'section' => 'arcade_livestream',
        'type' => 'text',
        'active_callback' => 'arcade_hub_is_twitch_selected',
    ));

    // Add content type selector
    $wp_customize->add_setting('livestream_twitch_type', array(
        'default' => 'live',
        'sanitize_callback' => 'arcade_hub_sanitize_select',
    ));

    $wp_customize->add_control('livestream_twitch_type', array(
        'label' => __('Twitch Content Type', 'arcade-hub'),
        'section' => 'arcade_livestream',
        'type' => 'select',
        'choices' => array(
            'live' => 'Live Channel',
            'video' => 'Past Stream (VOD)',
        ),
        'active_callback' => 'arcade_hub_is_twitch_selected',
    ));

    // Livestream Description
    $wp_customize->add_setting('livestream_description', array(
        'default' => 'Join us for live retro gaming action!',
        'sanitize_callback' => 'sanitize_textarea_field',
    ));

    $wp_customize->add_control('livestream_description', array(
        'label' => __('Description', 'arcade-hub'),
        'section' => 'arcade_livestream',
        'type' => 'textarea',
    ));
}
add_action('customize_register', 'arcade_hub_customize_register');

/**
 * Sanitize select options
 */
function arcade_hub_sanitize_select($input, $setting) {
    $input = sanitize_key($input);
    $choices = $setting->manager->get_control($setting->id)->choices;
    return (array_key_exists($input, $choices) ? $input : $setting->default);
}

/**
 * Active callback for YouTube settings
 */
function arcade_hub_is_youtube_selected($control) {
    return $control->manager->get_setting('livestream_platform')->value() === 'youtube';
}

/**
 * Active callback for Twitch settings
 */
function arcade_hub_is_twitch_selected($control) {
    return $control->manager->get_setting('livestream_platform')->value() === 'twitch';
}

/**
 * Get YouTube video ID from URL
 */
function arcade_hub_get_youtube_id($url) {
    if (empty($url)) return false;
    
    // If it's already just an ID (11 characters), return it
    if (strlen($url) === 11 && !strpos($url, '/') && !strpos($url, '?')) {
        return $url;
    }
    
    // Extract ID from various YouTube URL formats
    $patterns = array(
        '/youtube\.com\/shorts\/([^"&?\/\s]{11})/',  // YouTube Shorts
        '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/',
        '/youtube\.com\/live\/([^"&?\/\s]{11})/',
        '/youtube\.com\/embed\/live_stream\?channel=([^"&?\/\s]+)/',
    );
    
    foreach ($patterns as $pattern) {
        if (preg_match($pattern, $url, $matches)) {
            return $matches[1];
        }
    }
    
    return false;
}

// General function to extract video ID based on platform
function extract_video_id($url, $type) {
    if (empty($url)) return false;
    
    switch ($type) {
        case 'youtube':
            return arcade_hub_get_youtube_id($url);
            
        case 'instagram':
            // Instagram reel URL patterns:
            // https://www.instagram.com/reel/ABC123xyz/
            // https://instagram.com/p/ABC123xyz/
            // https://www.instagram.com/p/ABC123xyz/
            $patterns = array(
                '/instagram\.com\/reel\/([A-Za-z0-9_-]+)/',
                '/instagram\.com\/p\/([A-Za-z0-9_-]+)/',
            );
            
            foreach ($patterns as $pattern) {
                if (preg_match($pattern, $url, $matches)) {
                    return $matches[1];
                }
            }
            
            // If it's already just an ID (no slashes or dots), return it
            if (!strpos($url, '/') && !strpos($url, '.') && strlen($url) > 5) {
                return $url;
            }
            
            return false;
            
        default:
            return false;
    }
}

/**
 * Get Instagram thumbnail URL using oEmbed API
 */
function get_instagram_thumbnail($reel_id) {
    if (empty($reel_id)) return false;
    
    // Check if we have a cached thumbnail
    $cache_key = 'instagram_thumb_' . $reel_id;
    $cached_thumb = get_transient($cache_key);
    
    if ($cached_thumb !== false) {
        return $cached_thumb;
    }
    
    // Try both /reel/ and /p/ endpoints
    $urls_to_try = array(
        'https://www.instagram.com/reel/' . $reel_id . '/',
        'https://www.instagram.com/p/' . $reel_id . '/'
    );
    
    foreach ($urls_to_try as $reel_url) {
        $oembed_url = 'https://api.instagram.com/oembed/?url=' . urlencode($reel_url);
        
        // Use WordPress HTTP API
        $response = wp_remote_get($oembed_url, array(
            'timeout' => 10,
            'sslverify' => false,
            'headers' => array(
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
            )
        ));
        
        if (!is_wp_error($response) && wp_remote_retrieve_response_code($response) === 200) {
            $body = wp_remote_retrieve_body($response);
            $data = json_decode($body, true);
            
            if (isset($data['thumbnail_url']) && !empty($data['thumbnail_url'])) {
                // Cache for 24 hours
                set_transient($cache_key, $data['thumbnail_url'], DAY_IN_SECONDS);
                return $data['thumbnail_url'];
            }
        }
    }
    
    // Fallback: return a generic Instagram placeholder or false
    return false;
}


/**
 * Register Gaming News Custom Post Type
 */
function register_gaming_news_post_type() {
    $args = array(
        'public' => true,
        'label' => 'Gaming News',
        'labels' => array(
            'name' => 'Gaming News',
            'singular_name' => 'News Article',
            'menu_name' => 'Gaming News',
            'all_items' => 'All Articles',
            'add_new' => 'Add New Article',
            'add_new_item' => 'Add New News Article',
            'edit_item' => 'Edit Article',
            'new_item' => 'New Article',
            'view_item' => 'View Article',
            'search_items' => 'Search Articles',
            'not_found' => 'No articles found',
            'not_found_in_trash' => 'No articles found in trash'
        ),
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'author', 'comments'),
        'menu_icon' => 'dashicons-megaphone',
        'menu_position' => 5,
        'has_archive' => true,
        'rewrite' => array('slug' => 'gaming-news'),
        'show_in_rest' => true,
        'capability_type' => 'post',
        'hierarchical' => false,
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'publicly_queryable' => true,
        'exclude_from_search' => false,
        'query_var' => true,
        'can_export' => true,
    );
    
    register_post_type('gaming_news', $args);
}
add_action('init', 'register_gaming_news_post_type');

/**
 * Register Gaming News Taxonomies
 */
function register_gaming_news_taxonomies() {
    // News Categories
    register_taxonomy('news_category', 'gaming_news', array(
        'labels' => array(
            'name' => 'News Categories',
            'singular_name' => 'News Category',
            'menu_name' => 'Categories',
        ),
        'hierarchical' => true,
        'public' => true,
        'rewrite' => array('slug' => 'news-category'),
        'show_in_rest' => true,
    ));
    
    // News Tags
    register_taxonomy('news_tag', 'gaming_news', array(
        'labels' => array(
            'name' => 'News Tags',
            'singular_name' => 'News Tag',
            'menu_name' => 'Tags',
        ),
        'hierarchical' => false,
        'public' => true,
        'rewrite' => array('slug' => 'news-tag'),
        'show_in_rest' => true,
    ));
}
add_action('init', 'register_gaming_news_taxonomies');

/**
 * Add Gaming News Meta Boxes
 */
function add_gaming_news_meta_boxes() {
    add_meta_box(
        'gaming_news_details',
        'Article Details',
        'gaming_news_meta_box_callback',
        'gaming_news',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'add_gaming_news_meta_boxes');

/**
 * Gaming News Meta Box Content
 */
function gaming_news_meta_box_callback($post) {
    wp_nonce_field('gaming_news_meta_nonce', 'gaming_news_meta_nonce');
    
    $breaking_news = get_post_meta($post->ID, '_breaking_news', true);
    $featured_article = get_post_meta($post->ID, '_featured_article', true);
    $article_source = get_post_meta($post->ID, '_article_source', true);
    $external_link = get_post_meta($post->ID, '_external_link', true);
    $video_url = get_post_meta($post->ID, '_video_url', true);
    $gallery_images = get_post_meta($post->ID, '_gallery_images', true);
    ?>
    
    <table class="form-table">
        <tr>
            <th><label for="breaking_news">Breaking News</label></th>
            <td>
                <input type="checkbox" id="breaking_news" name="breaking_news" value="yes" <?php checked($breaking_news, 'yes'); ?> />
                <span class="description">Mark as breaking news (appears with red badge)</span>
            </td>
        </tr>
        
        <tr>
            <th><label for="featured_article">Featured Article</label></th>
            <td>
                <input type="checkbox" id="featured_article" name="featured_article" value="yes" <?php checked($featured_article, 'yes'); ?> />
                <span class="description">Feature on homepage and archive top</span>
            </td>
        </tr>
        
        <tr>
            <th><label for="article_source">Source</label></th>
            <td>
                <input type="text" id="article_source" name="article_source" value="<?php echo esc_attr($article_source); ?>" style="width: 100%;" />
                <span class="description">Original news source (if applicable)</span>
            </td>
        </tr>
        
        <tr>
            <th><label for="external_link">External Link</label></th>
            <td>
                <input type="url" id="external_link" name="external_link" value="<?php echo esc_attr($external_link); ?>" style="width: 100%;" />
                <span class="description">Link to original article or related content</span>
            </td>
        </tr>
        
        <tr>
            <th><label for="video_url">Video URL</label></th>
            <td>
                <input type="url" id="video_url" name="video_url" value="<?php echo esc_attr($video_url); ?>" style="width: 100%;" />
                <span class="description">YouTube URL or video embed link</span>
            </td>
        </tr>
        
        <tr>
            <th><label for="gallery_images">Gallery Images</label></th>
            <td>
                <textarea id="gallery_images" name="gallery_images" rows="5" style="width: 100%;" placeholder="Enter image URLs, one per line or separated by commas"><?php echo esc_textarea($gallery_images); ?></textarea>
                <span class="description">Image URLs separated by commas or new lines. Example:<br>
                https://example.com/image1.jpg<br>
                https://example.com/image2.jpg<br>
                https://example.com/image3.jpg</span>
            </td>
        </tr>
    </table>
    <?php
}
/**
 * Save Gaming News Meta
 */
function save_gaming_news_meta($post_id) {
    // Security checks
    if (!isset($_POST['gaming_news_meta_nonce'])) return;
    if (!wp_verify_nonce($_POST['gaming_news_meta_nonce'], 'gaming_news_meta_nonce')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    // Save all fields
    $fields = array(
        'breaking_news',
        'featured_article', 
        'article_source',
        'external_link',
        'video_url',
        'gallery_images'
    );

    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            $value = $_POST[$field];
            
            // Sanitize based on field type
            switch ($field) {
                case 'breaking_news':
                case 'featured_article':
                    $value = ($value === 'yes') ? 'yes' : 'no';
                    break;
                case 'external_link':
                case 'video_url':
                    $value = esc_url_raw($value);
                    break;
                case 'article_source':
                    $value = sanitize_text_field($value);
                    break;
                case 'gallery_images':
                    $value = sanitize_textarea_field($value);
                    break;
                default:
                    $value = sanitize_text_field($value);
            }
            
            if (!empty($value) && $value !== 'no') {
                update_post_meta($post_id, '_' . $field, $value);
            } else {
                delete_post_meta($post_id, '_' . $field);
            }
        } else {
            // If checkbox fields aren't set, save as 'no' or delete
            if (in_array($field, array('breaking_news', 'featured_article'))) {
                update_post_meta($post_id, '_' . $field, 'no');
            } else {
                delete_post_meta($post_id, '_' . $field);
            }
        }
    }
}
add_action('save_post', 'save_gaming_news_meta');

// Custom post types
function arcade_hub_post_types() {
    // Games post type
    register_post_type('games', array(
        'labels' => array(
            'name' => 'Games',
            'singular_name' => 'Game',
            'add_new' => 'Add New Game',
            'add_new_item' => 'Add New Game',
            'edit_item' => 'Edit Game',
            'new_item' => 'New Game',
            'view_item' => 'View Game',
            'search_items' => 'Search Games',
            'not_found' => 'No games found',
            'not_found_in_trash' => 'No games found in trash'
        ),
        'public' => true,
        'has_archive' => true,
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        'menu_icon' => 'dashicons-games',
        'rewrite' => array('slug' => 'games'),
    ));

    // Reviews post type
    register_post_type('reviews', array(
        'labels' => array(
            'name' => 'Reviews',
            'singular_name' => 'Review',
            'add_new' => 'Add New Review',
            'add_new_item' => 'Add New Review',
            'edit_item' => 'Edit Review',
            'new_item' => 'New Review',
            'view_item' => 'View Review',
            'search_items' => 'Search Reviews',
            'not_found' => 'No reviews found',
            'not_found_in_trash' => 'No reviews found in trash'
        ),
        'public' => true,
        'has_archive' => true,
        'supports' => array('title', 'editor', 'thumbnail', 'author', 'custom-fields'),
        'menu_icon' => 'dashicons-star-filled',
        'rewrite' => array('slug' => 'reviews'),
    ));

    // YouTube Videos post type
    register_post_type('retro_videos', array(
        'labels' => array(
            'name' => 'Retro Videos',
            'singular_name' => 'Retro Video',
            'add_new' => 'Add New Video',
            'add_new_item' => 'Add New Video',
            'edit_item' => 'Edit Video',
            'new_item' => 'New Video',
            'view_item' => 'View Video',
            'search_items' => 'Search Videos',
            'not_found' => 'No videos found',
            'not_found_in_trash' => 'No videos found in trash'
        ),
        'public' => true,
        'has_archive' => true,
        'supports' => array('title', 'editor', 'thumbnail', 'custom-fields'),
        'menu_icon' => 'dashicons-video-alt3',
        'rewrite' => array('slug' => 'retro-videos'),
    ));

    // Game Mods post type
    register_post_type('game_mods', array(
        'labels' => array(
            'name' => 'Game Mods',
            'singular_name' => 'Game Mod',
            'add_new' => 'Add New Mod',
            'add_new_item' => 'Add New Game Mod',
            'edit_item' => 'Edit Game Mod',
            'new_item' => 'New Game Mod',
            'view_item' => 'View Game Mod',
            'search_items' => 'Search Game Mods',
            'not_found' => 'No game mods found',
            'not_found_in_trash' => 'No game mods found in trash'
        ),
        'public' => true,
        'has_archive' => true,
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'comments'),
        'menu_icon' => 'dashicons-admin-tools',
        'rewrite' => array('slug' => 'mods'),
        'show_in_rest' => true,
    ));
}
add_action('init', 'arcade_hub_post_types');

// Custom taxonomies
function arcade_hub_taxonomies() {
    // Console taxonomy for games
    register_taxonomy('console', 'games', array(
        'labels' => array(
            'name' => 'Consoles',
            'singular_name' => 'Console',
            'search_items' => 'Search Consoles',
            'all_items' => 'All Consoles',
            'edit_item' => 'Edit Console',
            'update_item' => 'Update Console',
            'add_new_item' => 'Add New Console',
            'new_item_name' => 'New Console Name',
            'menu_name' => 'Consoles',
        ),
        'hierarchical' => true,
        'public' => true,
        'rewrite' => array('slug' => 'console'),
    ));

    // Genre taxonomy for games
    register_taxonomy('game_genre', 'games', array(
        'labels' => array(
            'name' => 'Game Genres',
            'singular_name' => 'Game Genre',
            'search_items' => 'Search Genres',
            'all_items' => 'All Genres',
            'edit_item' => 'Edit Genre',
            'update_item' => 'Update Genre',
            'add_new_item' => 'Add New Genre',
            'new_item_name' => 'New Genre Name',
            'menu_name' => 'Genres',
        ),
        'hierarchical' => true,
        'public' => true,
        'rewrite' => array('slug' => 'genre'),
    ));

    // Year taxonomy for games
    register_taxonomy('game_year', 'games', array(
        'labels' => array(
            'name' => 'Release Years',
            'singular_name' => 'Release Year',
            'search_items' => 'Search Years',
            'all_items' => 'All Years',
            'edit_item' => 'Edit Year',
            'update_item' => 'Update Year',
            'add_new_item' => 'Add New Year',
            'new_item_name' => 'New Year',
            'menu_name' => 'Years',
        ),
        'hierarchical' => false,
        'public' => true,
        'rewrite' => array('slug' => 'year'),
    ));

    // Base Game taxonomy for mods (which game the mod is for)
    register_taxonomy('base_game', 'game_mods', array(
        'labels' => array(
            'name' => 'Base Games',
            'singular_name' => 'Base Game',
            'search_items' => 'Search Base Games',
            'all_items' => 'All Base Games',
            'edit_item' => 'Edit Base Game',
            'update_item' => 'Update Base Game',
            'add_new_item' => 'Add New Base Game',
            'new_item_name' => 'New Base Game Name',
            'menu_name' => 'Base Games',
        ),
        'hierarchical' => true,
        'public' => true,
        'rewrite' => array('slug' => 'base-game'),
        'show_in_rest' => true,
    ));

    // Mod Category taxonomy (gameplay, graphics, sound, etc.)
    register_taxonomy('mod_category', 'game_mods', array(
        'labels' => array(
            'name' => 'Mod Categories',
            'singular_name' => 'Mod Category',
            'search_items' => 'Search Categories',
            'all_items' => 'All Categories',
            'edit_item' => 'Edit Category',
            'update_item' => 'Update Category',
            'add_new_item' => 'Add New Category',
            'new_item_name' => 'New Category Name',
            'menu_name' => 'Categories',
        ),
        'hierarchical' => true,
        'public' => true,
        'rewrite' => array('slug' => 'mod-category'),
        'show_in_rest' => true,
    ));

    // Mod Type taxonomy (total conversion, map pack, weapon mod, etc.)
    register_taxonomy('mod_type', 'game_mods', array(
        'labels' => array(
            'name' => 'Mod Types',
            'singular_name' => 'Mod Type',
            'search_items' => 'Search Types',
            'all_items' => 'All Types',
            'edit_item' => 'Edit Type',
            'update_item' => 'Update Type',
            'add_new_item' => 'Add New Type',
            'new_item_name' => 'New Type Name',
            'menu_name' => 'Types',
        ),
        'hierarchical' => false,
        'public' => true,
        'rewrite' => array('slug' => 'mod-type'),
        'show_in_rest' => true,
    ));
}
add_action('init', 'arcade_hub_taxonomies');

// Add custom meta boxes
function arcade_hub_meta_boxes() {
    // Game meta box
    add_meta_box(
        'game_details',
        'Game Details',
        'arcade_hub_game_meta_box',
        'games',
        'normal',
        'high'
    );

    // Review meta box
    add_meta_box(
        'review_details',
        'Review Details',
        'arcade_hub_review_meta_box',
        'reviews',
        'normal',
        'high'
    );

    // Video meta box
    add_meta_box(
        'video_details',
        'Video Details',
        'arcade_hub_video_meta_box',
        'retro_videos',
        'normal',
        'high'
    );

    // Game Mod meta box
    add_meta_box(
        'mod_details',
        'Mod Details',
        'arcade_hub_mod_meta_box',
        'game_mods',
        'normal',
        'high'
    );

    // Mod Load Order meta box
    add_meta_box(
        'mod_load_order',
        'Load Order & Compatibility',
        'arcade_hub_mod_load_order_meta_box',
        'game_mods',
        'side',
        'high'
    );
}
add_action('add_meta_boxes', 'arcade_hub_meta_boxes');

// Game meta box callback
function arcade_hub_game_meta_box($post) {
    wp_nonce_field('arcade_hub_game_meta', 'arcade_hub_game_nonce');
    
    $rom_url = get_post_meta($post->ID, '_game_rom_url', true);
    $rom_file_id = get_post_meta($post->ID, '_game_rom_file_id', true);
    $emulator_type = get_post_meta($post->ID, '_game_emulator_type', true);
    $trivia = get_post_meta($post->ID, '_game_trivia', true);
    $developer = get_post_meta($post->ID, '_game_developer', true);
    $publisher = get_post_meta($post->ID, '_game_publisher', true);
    $retroarch_core = get_post_meta($post->ID, '_game_retroarch_core', true);
    $game_controls = get_post_meta($post->ID, '_game_controls', true);
    
    // Get ROM file info if exists
    $rom_file_url = '';
    $rom_file_name = '';
    if ($rom_file_id) {
        $rom_file_url = wp_get_attachment_url($rom_file_id);
        $rom_file_name = basename(get_attached_file($rom_file_id));
    }
    ?>
    <table class="form-table">
        <tr>
            <th><label>ROM File Options</label></th>
            <td>
                <div style="margin-bottom: 15px;">
                    <h4 style="margin: 0 0 10px 0;">Option 1: External ROM URL</h4>
                    <input type="url" id="game_rom_url" name="game_rom_url" value="<?php echo esc_attr($rom_url); ?>" class="large-text" placeholder="https://example.com/roms/game.nes" />
                    <p class="description">Direct link to ROM file hosted elsewhere, or embedded game URL</p>
                </div>
                
                <div style="border-top: 1px solid #ddd; padding-top: 15px;">
                    <h4 style="margin: 0 0 10px 0;">Option 2: Upload ROM File</h4>
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <input type="hidden" id="game_rom_file_id" name="game_rom_file_id" value="<?php echo esc_attr($rom_file_id); ?>" />
                        <button type="button" class="button" id="upload_rom_button">
                            <?php echo $rom_file_id ? 'Change ROM File' : 'Upload ROM File'; ?>
                        </button>
                        <?php if ($rom_file_id): ?>
                            <span style="color: #46b450;">✓ <?php echo esc_html($rom_file_name); ?></span>
                            <button type="button" class="button button-small" id="remove_rom_button">Remove</button>
                        <?php endif; ?>
                    </div>
                    <p class="description">Upload a ROM file directly to your WordPress media library (.nes, .smc, .gb, .gba, .bin, etc.)</p>
                    <?php if ($rom_file_url): ?>
                        <p style="color: #666; font-size: 12px;">Current file: <a href="<?php echo esc_url($rom_file_url); ?>" target="_blank"><?php echo esc_html($rom_file_name); ?></a></p>
                    <?php endif; ?>
                </div>
                
                <div style="background: #fff3cd; border: 1px solid #ffeaa7; padding: 10px; margin-top: 15px; border-radius: 3px;">
                    <strong>📝 Note:</strong> If both options are provided, the uploaded ROM file will take priority over the external URL.
                </div>
            </td>
        </tr>
        <tr>
            <th><label for="game_emulator_type">Console System</label></th>
            <td>
                <select id="game_emulator_type" name="game_emulator_type">
                    <option value="nes" <?php selected($emulator_type, 'nes'); ?>>NES (Nintendo Entertainment System)</option>
                    <option value="snes" <?php selected($emulator_type, 'snes'); ?>>SNES (Super Nintendo)</option>
                    <option value="gb" <?php selected($emulator_type, 'gb'); ?>>Game Boy</option>
                    <option value="gbc" <?php selected($emulator_type, 'gbc'); ?>>Game Boy Color</option>
                    <option value="gba" <?php selected($emulator_type, 'gba'); ?>>Game Boy Advance</option>
                    <option value="genesis" <?php selected($emulator_type, 'genesis'); ?>>Sega Genesis/Mega Drive</option>
                    <option value="arcade" <?php selected($emulator_type, 'arcade'); ?>>Arcade</option>
                    <option value="n64" <?php selected($emulator_type, 'n64'); ?>>Nintendo 64</option>
                    <option value="psx" <?php selected($emulator_type, 'psx'); ?>>PlayStation 1</option>
                </select>
            </td>
        </tr>
        <tr>
            <th><label for="game_retroarch_core">RetroArch Core</label></th>
            <td>
                <select id="game_retroarch_core" name="game_retroarch_core">
                    <option value="">Auto-detect from system</option>
                    <option value="fceumm" <?php selected($retroarch_core, 'fceumm'); ?>>FCEUmm (NES)</option>
                    <option value="snes9x" <?php selected($retroarch_core, 'snes9x'); ?>>Snes9x (SNES)</option>
                    <option value="gambatte" <?php selected($retroarch_core, 'gambatte'); ?>>Gambatte (Game Boy/GBC)</option>
                    <option value="mgba" <?php selected($retroarch_core, 'mgba'); ?>>mGBA (Game Boy Advance)</option>
                    <option value="genesis_plus_gx" <?php selected($retroarch_core, 'genesis_plus_gx'); ?>>Genesis Plus GX (Genesis)</option>
                    <option value="mame2003_plus" <?php selected($retroarch_core, 'mame2003_plus'); ?>>MAME 2003-Plus (Arcade)</option>
                    <option value="mupen64plus_next" <?php selected($retroarch_core, 'mupen64plus_next'); ?>>Mupen64Plus-Next (N64)</option>
                    <option value="pcsx_rearmed" <?php selected($retroarch_core, 'pcsx_rearmed'); ?>>PCSX ReARMed (PSX)</option>
                </select>
                <p class="description">Specific RetroArch core for advanced users (optional)</p>
            </td>
        </tr>
        <tr>
            <th><label for="game_developer">Developer</label></th>
            <td><input type="text" id="game_developer" name="game_developer" value="<?php echo esc_attr($developer); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="game_publisher">Publisher</label></th>
            <td><input type="text" id="game_publisher" name="game_publisher" value="<?php echo esc_attr($publisher); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="game_controls">Custom Controls</label></th>
            <td>
                <textarea id="game_controls" name="game_controls" rows="3" class="large-text" placeholder="Special control instructions for this game..."><?php echo esc_textarea($game_controls); ?></textarea>
                <p class="description">Override default controls with game-specific instructions</p>
            </td>
        </tr>
        <tr>
            <th><label for="game_trivia">Trivia & Fun Facts</label></th>
            <td><textarea id="game_trivia" name="game_trivia" rows="5" class="large-text"><?php echo esc_textarea($trivia); ?></textarea></td>
        </tr>
    </table>
    
                <div style="background: #f0f8ff; border: 1px solid #0073aa; padding: 15px; margin-top: 20px; border-radius: 4px;">
        <h4 style="margin-top: 0; color: #0073aa;">🕹️ ROM File Testing & Debug Info:</h4>
        <div style="margin-bottom: 15px;">
            <button type="button" id="test_rom_accessibility" class="button button-secondary">🔍 Test ROM File Access</button>
            <div id="rom_test_results" style="margin-top: 10px; font-family: monospace; font-size: 12px;"></div>
        </div>
        
        <h4 style="color: #0073aa;">💡 Troubleshooting Tips:</h4>
        <ul style="margin-bottom: 0;">
            <li><strong>CORS Issues:</strong> ROM URLs must allow cross-origin access for web emulators</li>
            <li><strong>Local Development:</strong> Local files often don't work due to CORS restrictions</li>
            <li><strong>Archive.org:</strong> Most reliable source - use download URLs or embed URLs</li>
            <li><strong>File Formats:</strong> Use standard ROM formats (.nes, .smc, .gb, .gba, .bin, etc.)</li>
            <li><strong>EmulatorJS Issues:</strong> Third-party services can be unreliable</li>
            <li><strong>Self-Hosted:</strong> Files uploaded to your WordPress site should work better</li>
        </ul>
    </div>
    
    <script type="text/javascript">
    jQuery(document).ready(function($) {
        // ROM accessibility testing
        $('#test_rom_accessibility').click(function(e) {
            e.preventDefault();
            
            // Get ROM URL from either field
            var romUrl = $('#game_rom_url').val().trim();
            var romFileId = $('#game_rom_file_id').val();
            
            if (!romUrl && romFileId) {
                // If we have a file ID but no URL, try to construct the URL
                romUrl = '<?php echo wp_upload_dir()['baseurl']; ?>/' + '<?php echo date('Y/m'); ?>/' + 'rom-file-' + romFileId;
                alert('Note: Testing uploaded file requires the file to be accessible via direct URL. This test may not work for uploaded files without proper URL.');
            }
            
            if (!romUrl) {
                $('#rom_test_results').html('<div style="color: #d63638; background: #f8d7da; padding: 8px; border-radius: 3px;">❌ No ROM URL to test. Please enter a ROM URL or upload a file first.</div>');
                return;
            }
            
            $('#rom_test_results').html('<div style="color: #0073aa; background: #f0f8ff; padding: 8px; border-radius: 3px;">🔄 Testing ROM accessibility...</div>');
            $('#test_rom_accessibility').prop('disabled', true).text('🔄 Testing...');
            
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'test_rom_file',
                    rom_url: romUrl,
                    nonce: '<?php echo wp_create_nonce('arcade_nonce'); ?>'
                },
                success: function(response) {
                    var resultHtml = '<div style="padding: 10px; border-radius: 4px; font-family: monospace; font-size: 11px; max-height: 200px; overflow-y: auto; ';
                    
                    if (response.status === 'success') {
                        resultHtml += 'background: #d1e7dd; border: 1px solid #badbcc; color: #0f5132;">';
                        resultHtml += '<strong>✅ ROM FILE ACCESSIBLE</strong><br>';
                    } else {
                        resultHtml += 'background: #f8d7da; border: 1px solid #f5c2c7; color: #721c24;">';
                        resultHtml += '<strong>❌ ROM FILE NOT ACCESSIBLE</strong><br>';
                        if (response.message) {
                            resultHtml += '<strong>Error:</strong> ' + response.message + '<br>';
                        }
                    }
                    
                    resultHtml += '<br><strong>Details:</strong><br>';
                    resultHtml += 'URL: ' + response.url + '<br>';
                    resultHtml += 'Status Code: ' + response.status_code + '<br>';
                    resultHtml += 'Content Type: ' + response.content_type + '<br>';
                    resultHtml += 'Content Length: ' + response.content_length + '<br>';
                    
                    if (response.cors_headers) {
                        resultHtml += '<br><strong>CORS Headers:</strong><br>';
                        resultHtml += 'Access-Control-Allow-Origin: ' + response.cors_headers.access_control_allow_origin + '<br>';
                        resultHtml += 'Access-Control-Allow-Methods: ' + response.cors_headers.access_control_allow_methods + '<br>';
                        
                        if (response.cors_headers.access_control_allow_origin === 'missing') {
                            resultHtml += '<br><strong>⚠️ CORS Issue:</strong> This ROM file doesn\'t allow cross-origin access. Web emulators may not be able to load it.<br>';
                            resultHtml += '<br><strong>🔧 CORS Solutions:</strong><br>';
                            resultHtml += '• Add CORS headers to your .htaccess file (see documentation)<br>';
                            resultHtml += '• Use Archive.org ROM URLs instead (recommended)<br>';
                            resultHtml += '• Use embedded game URLs like: https://archive.org/embed/collection-name<br>';
                        }
                    }
                    
                    if (response.status !== 'success') {
                        resultHtml += '<br><strong>💡 Suggestions:</strong><br>';
                        resultHtml += '• Try using an Archive.org ROM URL<br>';
                        resultHtml += '• Use embedded game URLs instead of direct ROM files<br>';
                        resultHtml += '• Check if the file exists and is publicly accessible<br>';
                        resultHtml += '• For local development, try deploying to live site<br>';
                    }
                    
                    resultHtml += '</div>';
                    $('#rom_test_results').html(resultHtml);
                },
                error: function() {
                    $('#rom_test_results').html('<div style="color: #d63638; background: #f8d7da; padding: 8px; border-radius: 3px;">❌ Error testing ROM file. Please try again.</div>');
                },
                complete: function() {
                    $('#test_rom_accessibility').prop('disabled', false).text('🔍 Test ROM File Access');
                }
            });
        });
        
        // ROM file upload functionality
        $('#upload_rom_button').click(function(e) {
            e.preventDefault();
            
            var mediaUploader = wp.media({
                title: 'Upload ROM File',
                button: {
                    text: 'Use This ROM File'
                },
                library: {
                    type: ['application/octet-stream', 'text/plain', 'application/zip']
                },
                multiple: false
            });
            
            // Handle upload errors
            mediaUploader.on('open', function() {
                // Add help text for ROM uploads
                setTimeout(function() {
                    var $toolbar = $('.media-toolbar');
                    if ($toolbar.length && !$toolbar.find('.rom-upload-help').length) {
                        $toolbar.append('<div class="rom-upload-help" style="background: #fff3cd; border: 1px solid #ffeaa7; padding: 8px; margin: 10px; border-radius: 3px; font-size: 12px;"><strong>💡 ROM Upload Tips:</strong><br>• Supported: .nes, .smc, .gb, .gba, .bin, .z64, .iso<br>• Having issues? Try renaming to .txt, upload, then rename back<br>• Or use external ROM URL option instead</div>');
                    }
                }, 500);
            });
            
            mediaUploader.on('select', function() {
                var attachment = mediaUploader.state().get('selection').first().toJSON();
                
                // Check if this is a valid ROM file or renamed file
                var fileName = attachment.filename || attachment.title;
                var validExtensions = ['nes', 'smc', 'sfc', 'gb', 'gbc', 'gba', 'bin', 'md', 'gen', 'z64', 'n64', 'v64', 'iso', 'rom', 'txt'];
                var fileExt = fileName.split('.').pop().toLowerCase();
                
                if (validExtensions.includes(fileExt)) {
                    $('#game_rom_file_id').val(attachment.id);
                    
                    // Update the UI
                    $('#upload_rom_button').text('Change ROM File');
                    
                    // Remove any existing status
                    $('#upload_rom_button').siblings('span, #remove_rom_button').remove();
                    $('#upload_rom_button').after('<span style="color: #46b450; margin-left: 10px;">✓ ' + fileName + '</span><button type="button" class="button button-small" id="remove_rom_button" style="margin-left: 10px;">Remove</button>');
                    
                    // Show success message if it's a .txt file (likely renamed ROM)
                    if (fileExt === 'txt') {
                        $('#upload_rom_button').after('<div style="color: #856404; background: #fff3cd; border: 1px solid #ffeaa7; padding: 5px; margin: 5px 0; border-radius: 3px; font-size: 12px;">📝 Uploaded as .txt file - this is fine for ROM emulation!</div>');
                    }
                } else {
                    alert('Please select a valid ROM file (.nes, .smc, .gb, .gba, .bin, .z64, .iso) or rename it to .txt for upload.');
                }
            });
            
            mediaUploader.open();
        });
        
        // ROM file remove functionality
        $(document).on('click', '#remove_rom_button', function(e) {
            e.preventDefault();
            $('#game_rom_file_id').val('');
            $('#upload_rom_button').text('Upload ROM File');
            $(this).siblings('span, div').remove();
            $(this).remove();
        });
    });
    </script>
    <?php
}

// Review meta box callback
function arcade_hub_review_meta_box($post) {
    wp_nonce_field('arcade_hub_review_meta', 'arcade_hub_review_nonce');
    
    $rating = get_post_meta($post->ID, '_review_rating', true);
    $game_title = get_post_meta($post->ID, '_review_game_title', true);
    $reviewer_name = get_post_meta($post->ID, '_review_reviewer_name', true);
    ?>
    <table class="form-table">
        <tr>
            <th><label for="review_game_title">Game Title</label></th>
            <td><input type="text" id="review_game_title" name="review_game_title" value="<?php echo esc_attr($game_title); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="review_rating">Rating (1-5)</label></th>
            <td>
                <select id="review_rating" name="review_rating">
                    <option value="1" <?php selected($rating, '1'); ?>>1 Star</option>
                    <option value="2" <?php selected($rating, '2'); ?>>2 Stars</option>
                    <option value="3" <?php selected($rating, '3'); ?>>3 Stars</option>
                    <option value="4" <?php selected($rating, '4'); ?>>4 Stars</option>
                    <option value="5" <?php selected($rating, '5'); ?>>5 Stars</option>
                </select>
            </td>
        </tr>
        <tr>
            <th><label for="review_reviewer_name">Reviewer Name</label></th>
            <td><input type="text" id="review_reviewer_name" name="review_reviewer_name" value="<?php echo esc_attr($reviewer_name); ?>" class="regular-text" /></td>
        </tr>
    </table>
    <?php
}

// Video meta box callback
function arcade_hub_video_meta_box($post) {
    wp_nonce_field('arcade_hub_video_meta', 'arcade_hub_video_nonce');
    
    $youtube_id = get_post_meta($post->ID, '_video_youtube_id', true);
    $video_type = get_post_meta($post->ID, '_video_type', true);
    ?>
    <table class="form-table">
        <tr>
            <th><label for="video_youtube_id">YouTube Video ID</label></th>
            <td><input type="text" id="video_youtube_id" name="video_youtube_id" value="<?php echo esc_attr($youtube_id); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="video_type">Video Type</label></th>
            <td>
                <select id="video_type" name="video_type">
                    <option value="commercial" <?php selected($video_type, 'commercial'); ?>>Commercial</option>
                    <option value="gameplay" <?php selected($video_type, 'gameplay'); ?>>Gameplay</option>
                    <option value="review" <?php selected($video_type, 'review'); ?>>Review</option>
                    <option value="documentary" <?php selected($video_type, 'documentary'); ?>>Documentary</option>
                </select>
            </td>
        </tr>
    </table>
    <?php
}

// Game Mod meta box callback
function arcade_hub_mod_meta_box($post) {
    wp_nonce_field('arcade_hub_mod_meta', 'arcade_hub_mod_nonce');
    
    $mod_files = get_post_meta($post->ID, '_mod_files', true) ?: array();
    $mod_version = get_post_meta($post->ID, '_mod_version', true);
    $mod_author = get_post_meta($post->ID, '_mod_author', true);
    $mod_website = get_post_meta($post->ID, '_mod_website', true);
    $mod_requirements = get_post_meta($post->ID, '_mod_requirements', true);
    $install_instructions = get_post_meta($post->ID, '_install_instructions', true);
    ?>
    <table class="form-table">
        <tr>
            <th><label for="mod_version">Mod Version</label></th>
            <td><input type="text" id="mod_version" name="mod_version" value="<?php echo esc_attr($mod_version); ?>" class="regular-text" placeholder="1.0, v2.3, etc." /></td>
        </tr>
        <tr>
            <th><label for="mod_author">Mod Author(s)</label></th>
            <td><input type="text" id="mod_author" name="mod_author" value="<?php echo esc_attr($mod_author); ?>" class="regular-text" placeholder="Author name or team" /></td>
        </tr>
        <tr>
            <th><label for="mod_website">Official Website/Source</label></th>
            <td><input type="url" id="mod_website" name="mod_website" value="<?php echo esc_attr($mod_website); ?>" class="large-text" placeholder="https://moddb.com/mods/example" /></td>
        </tr>
        <tr>
            <th><label for="mod_requirements">Requirements</label></th>
            <td>
                <textarea id="mod_requirements" name="mod_requirements" rows="3" class="large-text"><?php echo esc_textarea($mod_requirements); ?></textarea>
                <p class="description">Base game version, required source ports, dependencies, etc.</p>
            </td>
        </tr>
        <tr>
            <th><label>Mod Files & Download Links</label></th>
            <td>
                <div id="mod-files-container">
                    <?php if (!empty($mod_files)) : ?>
                        <?php foreach ($mod_files as $index => $file) : ?>
                            <div class="mod-file-row" style="border: 1px solid #ddd; padding: 15px; margin-bottom: 10px; background: #f9f9f9;">
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 10px;">
                                    <div>
                                        <label><strong>File Name:</strong></label>
                                        <input type="text" name="mod_files[<?php echo $index; ?>][name]" value="<?php echo esc_attr($file['name'] ?? ''); ?>" class="regular-text" placeholder="filename.wad, graphics.pk3" />
                                    </div>
                                    <div>
                                        <label><strong>File Type:</strong></label>
                                        <select name="mod_files[<?php echo $index; ?>][type]">
                                            <option value="main" <?php selected($file['type'] ?? '', 'main'); ?>>Main Mod File</option>
                                            <option value="patch" <?php selected($file['type'] ?? '', 'patch'); ?>>Patch/Update</option>
                                            <option value="addon" <?php selected($file['type'] ?? '', 'addon'); ?>>Add-on/Optional</option>
                                            <option value="resource" <?php selected($file['type'] ?? '', 'resource'); ?>>Resource/Asset Pack</option>
                                        </select>
                                    </div>
                                </div>
                                <div style="margin-bottom: 10px;">
                                    <label><strong>Download URL:</strong></label>
                                    <input type="url" name="mod_files[<?php echo $index; ?>][url]" value="<?php echo esc_attr($file['url'] ?? ''); ?>" class="large-text" placeholder="https://download-link.com/file.zip" />
                                </div>
                                <div style="margin-bottom: 10px;">
                                    <label><strong>Description:</strong></label>
                                    <input type="text" name="mod_files[<?php echo $index; ?>][description]" value="<?php echo esc_attr($file['description'] ?? ''); ?>" class="large-text" placeholder="Brief description of this file" />
                                </div>
                                <button type="button" class="button button-small remove-mod-file" style="color: #a00;">Remove File</button>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <button type="button" id="add-mod-file" class="button">Add Download File</button>
                <p class="description">Add multiple download links for different files (main mod, patches, resources, etc.)</p>
            </td>
        </tr>
        <tr>
            <th><label for="install_instructions">Installation Instructions</label></th>
            <td>
                <textarea id="install_instructions" name="install_instructions" rows="6" class="large-text"><?php echo esc_textarea($install_instructions); ?></textarea>
                <p class="description">Step-by-step installation guide for users</p>
            </td>
        </tr>
    </table>
    
    <script>
    jQuery(document).ready(function($) {
        let fileIndex = <?php echo count($mod_files); ?>;
        
        $('#add-mod-file').click(function() {
            const template = `
                <div class="mod-file-row" style="border: 1px solid #ddd; padding: 15px; margin-bottom: 10px; background: #f9f9f9;">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 10px;">
                        <div>
                            <label><strong>File Name:</strong></label>
                            <input type="text" name="mod_files[${fileIndex}][name]" class="regular-text" placeholder="filename.wad, graphics.pk3" />
                        </div>
                        <div>
                            <label><strong>File Type:</strong></label>
                            <select name="mod_files[${fileIndex}][type]">
                                <option value="main">Main Mod File</option>
                                <option value="patch">Patch/Update</option>
                                <option value="addon">Add-on/Optional</option>
                                <option value="resource">Resource/Asset Pack</option>
                            </select>
                        </div>
                    </div>
                    <div style="margin-bottom: 10px;">
                        <label><strong>Download URL:</strong></label>
                        <input type="url" name="mod_files[${fileIndex}][url]" class="large-text" placeholder="https://download-link.com/file.zip" />
                    </div>
                    <div style="margin-bottom: 10px;">
                        <label><strong>Description:</strong></label>
                        <input type="text" name="mod_files[${fileIndex}][description]" class="large-text" placeholder="Brief description of this file" />
                    </div>
                    <button type="button" class="button button-small remove-mod-file" style="color: #a00;">Remove File</button>
                </div>
            `;
            $('#mod-files-container').append(template);
            fileIndex++;
        });
        
        $(document).on('click', '.remove-mod-file', function() {
            $(this).closest('.mod-file-row').remove();
        });
    });
    </script>
    <?php
}

// Mod Load Order meta box callback
function arcade_hub_mod_load_order_meta_box($post) {
    wp_nonce_field('arcade_hub_mod_load_order_meta', 'arcade_hub_mod_load_order_nonce');
    
    $load_order = get_post_meta($post->ID, '_mod_load_order', true);
    $compatibility = get_post_meta($post->ID, '_mod_compatibility', true);
    $conflicts = get_post_meta($post->ID, '_mod_conflicts', true);
    $recommended_mods = get_post_meta($post->ID, '_recommended_mods', true);
    ?>
    <table class="form-table">
        <tr>
            <th><label for="mod_load_order">Suggested Load Order</label></th>
            <td>
                <textarea id="mod_load_order" name="mod_load_order" rows="4" class="large-text" placeholder="1. Base game&#10;2. This mod&#10;3. Graphics enhancement&#10;4. Sound pack"><?php echo esc_textarea($load_order); ?></textarea>
                <p class="description">For ZDL, Doom Launcher, or manual loading</p>
            </td>
        </tr>
        <tr>
            <th><label for="mod_compatibility">Compatibility Notes</label></th>
            <td>
                <textarea id="mod_compatibility" name="mod_compatibility" rows="3" class="large-text" placeholder="Works with GZDoom 4.0+, requires hardware acceleration..."><?php echo esc_textarea($compatibility); ?></textarea>
            </td>
        </tr>
        <tr>
            <th><label for="mod_conflicts">Known Conflicts</label></th>
            <td>
                <textarea id="mod_conflicts" name="mod_conflicts" rows="3" class="large-text" placeholder="Conflicts with: ModX, ModY. Do not use together."><?php echo esc_textarea($conflicts); ?></textarea>
            </td>
        </tr>
        <tr>
            <th><label for="recommended_mods">Recommended Combinations</label></th>
            <td>
                <textarea id="recommended_mods" name="recommended_mods" rows="3" class="large-text" placeholder="Pairs well with: Sound Pack X, Graphics Mod Y"><?php echo esc_textarea($recommended_mods); ?></textarea>
            </td>
        </tr>
    </table>
    <?php
}

// Save meta box data
function arcade_hub_save_meta_boxes($post_id) {
    // Game meta
    if (isset($_POST['arcade_hub_game_nonce']) && wp_verify_nonce($_POST['arcade_hub_game_nonce'], 'arcade_hub_game_meta')) {
        if (isset($_POST['game_rom_url'])) {
            update_post_meta($post_id, '_game_rom_url', sanitize_url($_POST['game_rom_url']));
        }
        if (isset($_POST['game_rom_file_id'])) {
            $rom_file_id = intval($_POST['game_rom_file_id']);
            if ($rom_file_id > 0) {
                update_post_meta($post_id, '_game_rom_file_id', $rom_file_id);
            } else {
                delete_post_meta($post_id, '_game_rom_file_id');
            }
        }
        if (isset($_POST['game_emulator_type'])) {
            update_post_meta($post_id, '_game_emulator_type', sanitize_text_field($_POST['game_emulator_type']));
        }
        if (isset($_POST['game_retroarch_core'])) {
            update_post_meta($post_id, '_game_retroarch_core', sanitize_text_field($_POST['game_retroarch_core']));
        }
        if (isset($_POST['game_trivia'])) {
            update_post_meta($post_id, '_game_trivia', sanitize_textarea_field($_POST['game_trivia']));
        }
        if (isset($_POST['game_developer'])) {
            update_post_meta($post_id, '_game_developer', sanitize_text_field($_POST['game_developer']));
        }
        if (isset($_POST['game_publisher'])) {
            update_post_meta($post_id, '_game_publisher', sanitize_text_field($_POST['game_publisher']));
        }
        if (isset($_POST['game_controls'])) {
            update_post_meta($post_id, '_game_controls', sanitize_textarea_field($_POST['game_controls']));
        }
    }

    // Review meta
    if (isset($_POST['arcade_hub_review_nonce']) && wp_verify_nonce($_POST['arcade_hub_review_nonce'], 'arcade_hub_review_meta')) {
        if (isset($_POST['review_rating'])) {
            update_post_meta($post_id, '_review_rating', sanitize_text_field($_POST['review_rating']));
        }
        if (isset($_POST['review_game_title'])) {
            update_post_meta($post_id, '_review_game_title', sanitize_text_field($_POST['review_game_title']));
        }
        if (isset($_POST['review_reviewer_name'])) {
            update_post_meta($post_id, '_review_reviewer_name', sanitize_text_field($_POST['review_reviewer_name']));
        }
    }

    // Video meta
    if (isset($_POST['arcade_hub_video_nonce']) && wp_verify_nonce($_POST['arcade_hub_video_nonce'], 'arcade_hub_video_meta')) {
        if (isset($_POST['video_youtube_id'])) {
            update_post_meta($post_id, '_video_youtube_id', sanitize_text_field($_POST['video_youtube_id']));
        }
        if (isset($_POST['video_type'])) {
            update_post_meta($post_id, '_video_type', sanitize_text_field($_POST['video_type']));
        }
    }

    // Mod meta
    if (isset($_POST['arcade_hub_mod_nonce']) && wp_verify_nonce($_POST['arcade_hub_mod_nonce'], 'arcade_hub_mod_meta')) {
        if (isset($_POST['mod_version'])) {
            update_post_meta($post_id, '_mod_version', sanitize_text_field($_POST['mod_version']));
        }
        if (isset($_POST['mod_author'])) {
            update_post_meta($post_id, '_mod_author', sanitize_text_field($_POST['mod_author']));
        }
        if (isset($_POST['mod_website'])) {
            update_post_meta($post_id, '_mod_website', sanitize_url($_POST['mod_website']));
        }
        if (isset($_POST['mod_requirements'])) {
            update_post_meta($post_id, '_mod_requirements', wp_kses_post($_POST['mod_requirements']));
        }
        if (isset($_POST['install_instructions'])) {
            update_post_meta($post_id, '_install_instructions', wp_kses_post($_POST['install_instructions']));
        }
        if (isset($_POST['mod_files']) && is_array($_POST['mod_files'])) {
            $mod_files = array();
            foreach ($_POST['mod_files'] as $file) {
                if (!empty($file['name']) || !empty($file['url'])) {
                    $mod_files[] = array(
                        'name' => sanitize_text_field($file['name']),
                        'type' => sanitize_text_field($file['type']),
                        'url' => sanitize_url($file['url']),
                        'description' => sanitize_text_field($file['description'])
                    );
                }
            }
            update_post_meta($post_id, '_mod_files', $mod_files);
        }
    }

    // Mod load order meta
    if (isset($_POST['arcade_hub_mod_load_order_nonce']) && wp_verify_nonce($_POST['arcade_hub_mod_load_order_nonce'], 'arcade_hub_mod_load_order_meta')) {
        if (isset($_POST['mod_load_order'])) {
            update_post_meta($post_id, '_mod_load_order', wp_kses_post($_POST['mod_load_order']));
        }
        if (isset($_POST['mod_compatibility'])) {
            update_post_meta($post_id, '_mod_compatibility', wp_kses_post($_POST['mod_compatibility']));
        }
        if (isset($_POST['mod_conflicts'])) {
            update_post_meta($post_id, '_mod_conflicts', wp_kses_post($_POST['mod_conflicts']));
        }
        if (isset($_POST['recommended_mods'])) {
            update_post_meta($post_id, '_recommended_mods', wp_kses_post($_POST['recommended_mods']));
        }
    }
}
add_action('save_post', 'arcade_hub_save_meta_boxes');

// Helper function to get ROM URL (prioritizes uploaded file over external URL)
function arcade_hub_get_rom_url($post_id) {
    // Check for uploaded ROM file first
    $rom_file_id = get_post_meta($post_id, '_game_rom_file_id', true);
    if ($rom_file_id) {
        $rom_file_url = wp_get_attachment_url($rom_file_id);
        if ($rom_file_url) {
            return $rom_file_url;
        }
    }
    
    // Fall back to external ROM URL
    $rom_url = get_post_meta($post_id, '_game_rom_url', true);
    return $rom_url;
}

// Validate and fix ROM URLs for better compatibility
function arcade_hub_validate_rom_url($rom_url) {
    if (empty($rom_url)) {
        return array('status' => 'empty', 'url' => '', 'message' => 'No ROM URL provided');
    }
    
    // Check if it's an Archive.org download URL that should be converted to embed
    if (strpos($rom_url, 'archive.org/download/') !== false) {
        // Extract collection name and suggest embed URL
        preg_match('/archive\.org\/download\/([^\/]+)/', $rom_url, $matches);
        if (!empty($matches[1])) {
            $collection = $matches[1];
            $embed_url = "https://archive.org/embed/$collection";
            return array(
                'status' => 'convertible',
                'url' => $rom_url,
                'suggested_url' => $embed_url,
                'message' => "Archive.org download URL detected. Consider using embed URL: $embed_url"
            );
        }
    }
    
    // Check if it's already an embed URL
    if (strpos($rom_url, '/embed/') !== false || strpos($rom_url, 'emulator') !== false) {
        return array('status' => 'embed', 'url' => $rom_url, 'message' => 'Embed URL detected - should work reliably');
    }
    
    // Check if it's a direct ROM file
    if (preg_match('/\.(nes|smc|sfc|gb|gbc|gba|bin|md|gen|z64|n64|v64|iso|rom)(\?|$)/i', $rom_url)) {
        return array('status' => 'direct_rom', 'url' => $rom_url, 'message' => 'Direct ROM file - may have CORS issues');
    }
    
    return array('status' => 'unknown', 'url' => $rom_url, 'message' => 'Unknown URL type');
}

// Helper function to check if game has a ROM file available
function arcade_hub_has_rom_file($post_id) {
    $rom_file_id = get_post_meta($post_id, '_game_rom_file_id', true);
    $rom_url = get_post_meta($post_id, '_game_rom_url', true);
    
    return !empty($rom_file_id) || !empty($rom_url);
}

// Test ROM file accessibility (for debugging)
function arcade_hub_test_rom_accessibility($rom_url) {
    if (empty($rom_url)) {
        return array('status' => 'error', 'message' => 'No ROM URL provided');
    }
    
    // Test if URL is accessible
    $response = wp_remote_head($rom_url, array(
        'timeout' => 10,
        'sslverify' => false,
        'headers' => array(
            'User-Agent' => 'Mozilla/5.0 (compatible; Arcade-Hub-ROM-Test/1.0)'
        )
    ));
    
    if (is_wp_error($response)) {
        return array(
            'status' => 'error', 
            'message' => 'Cannot access ROM file: ' . $response->get_error_message(),
            'url' => $rom_url
        );
    }
    
    $status_code = wp_remote_retrieve_response_code($response);
    $headers = wp_remote_retrieve_headers($response);
    
    $result = array(
        'status' => $status_code == 200 ? 'success' : 'error',
        'status_code' => $status_code,
        'url' => $rom_url,
        'content_type' => isset($headers['content-type']) ? $headers['content-type'] : 'unknown',
        'content_length' => isset($headers['content-length']) ? $headers['content-length'] : 'unknown',
        'cors_headers' => array(
            'access_control_allow_origin' => isset($headers['access-control-allow-origin']) ? $headers['access-control-allow-origin'] : 'missing',
            'access_control_allow_methods' => isset($headers['access-control-allow-methods']) ? $headers['access-control-allow-methods'] : 'missing'
        )
    );
    
    if ($status_code != 200) {
        $result['message'] = "HTTP Error $status_code - ROM file not accessible";
    }
    
    return $result;
}

// AJAX handler for ROM testing
function arcade_hub_test_rom_file() {
    check_ajax_referer('arcade_nonce', 'nonce');
    
    $rom_url = sanitize_url($_POST['rom_url']);
    $test_result = arcade_hub_test_rom_accessibility($rom_url);
    
    wp_send_json($test_result);
}
add_action('wp_ajax_test_rom_file', 'arcade_hub_test_rom_file');
add_action('wp_ajax_nopriv_test_rom_file', 'arcade_hub_test_rom_file');

// AJAX handlers for game filtering
function arcade_hub_filter_games() {
    check_ajax_referer('arcade_nonce', 'nonce');
    
    $console = sanitize_text_field($_POST['console']);
    $genre = sanitize_text_field($_POST['genre']);
    $year = sanitize_text_field($_POST['year']);
    
    $args = array(
        'post_type' => 'games',
        'posts_per_page' => -1,
        'tax_query' => array('relation' => 'AND')
    );
    
    if (!empty($console)) {
        $args['tax_query'][] = array(
            'taxonomy' => 'console',
            'field' => 'slug',
            'terms' => $console
        );
    }
    
    if (!empty($genre)) {
        $args['tax_query'][] = array(
            'taxonomy' => 'game_genre',
            'field' => 'slug',
            'terms' => $genre
        );
    }
    
    if (!empty($year)) {
        $args['tax_query'][] = array(
            'taxonomy' => 'game_year',
            'field' => 'slug',
            'terms' => $year
        );
    }
    
    $games = new WP_Query($args);
    
    if ($games->have_posts()) {
        while ($games->have_posts()) {
            $games->the_post();
            get_template_part('template-parts/game-card');
        }
    } else {
        echo '<p>No games found matching your criteria.</p>';
    }
    
    wp_reset_postdata();
    wp_die();
}
add_action('wp_ajax_filter_games', 'arcade_hub_filter_games');
add_action('wp_ajax_nopriv_filter_games', 'arcade_hub_filter_games');

// Helper function to get star rating HTML
function arcade_hub_get_star_rating($rating) {
    $output = '';
    for ($i = 1; $i <= 5; $i++) {
        if ($i <= $rating) {
            $output .= '<span class="star">★</span>';
        } else {
            $output .= '<span class="star empty">☆</span>';
        }
    }
    return $output;
}

// Add custom body classes
function arcade_hub_body_classes($classes) {
    if (is_post_type_archive('games')) {
        $classes[] = 'games-archive';
    }
    if (is_post_type_archive('reviews')) {
        $classes[] = 'reviews-archive';
    }
    if (is_post_type_archive('retro_videos')) {
        $classes[] = 'videos-archive';
    }
    return $classes;
}
add_filter('body_class', 'arcade_hub_body_classes');

// Modify main query for game mods archive filtering
function arcade_hub_modify_mod_archive_query($query) {
    if (!is_admin() && $query->is_main_query()) {
        if (is_post_type_archive('game_mods')) {
            // Handle taxonomy filters
            $tax_query = array('relation' => 'AND');
            
            if (!empty($_GET['base_game'])) {
                $tax_query[] = array(
                    'taxonomy' => 'base_game',
                    'field' => 'slug',
                    'terms' => sanitize_text_field($_GET['base_game'])
                );
            }
            
            if (!empty($_GET['mod_category'])) {
                $tax_query[] = array(
                    'taxonomy' => 'mod_category',
                    'field' => 'slug',
                    'terms' => sanitize_text_field($_GET['mod_category'])
                );
            }
            
            if (!empty($_GET['mod_type'])) {
                $tax_query[] = array(
                    'taxonomy' => 'mod_type',
                    'field' => 'slug',
                    'terms' => sanitize_text_field($_GET['mod_type'])
                );
            }
            
            if (count($tax_query) > 1) {
                $query->set('tax_query', $tax_query);
            }
            
            // Handle sorting
            if (!empty($_GET['sort_by'])) {
                switch ($_GET['sort_by']) {
                    case 'date_asc':
                        $query->set('orderby', 'date');
                        $query->set('order', 'ASC');
                        break;
                    case 'title':
                        $query->set('orderby', 'title');
                        $query->set('order', 'ASC');
                        break;
                    case 'title_desc':
                        $query->set('orderby', 'title');
                        $query->set('order', 'DESC');
                        break;
                    case 'popular':
                        $query->set('meta_key', '_mod_download_count');
                        $query->set('orderby', 'meta_value_num');
                        $query->set('order', 'DESC');
                        break;
                    default: // 'date'
                        $query->set('orderby', 'date');
                        $query->set('order', 'DESC');
                }
            }
            
            // Set posts per page
            $query->set('posts_per_page', 12);
        }
    }
}
add_action('pre_get_posts', 'arcade_hub_modify_mod_archive_query');


/**
 * Add Theme Options Page for Reels
 */
function add_reels_options_page() {
    add_theme_page(
        'Featured Reels Settings',
        'Featured Reels',
        'manage_options',
        'featured-reels',
        'featured_reels_options_page'
    );
}
add_action('admin_menu', 'add_reels_options_page');

/**
 * Options Page Callback
 */
function featured_reels_options_page() {
    if (isset($_POST['submit'])) {
        // Save the options
        for ($i = 1; $i <= 8; $i++) {
            update_option("reel_url_$i", esc_url_raw($_POST["reel_url_$i"]));
            update_option("reel_title_$i", sanitize_text_field($_POST["reel_title_$i"]));
            update_option("reel_type_$i", sanitize_text_field($_POST["reel_type_$i"]));
            update_option("reel_thumbnail_$i", esc_url_raw($_POST["reel_thumbnail_$i"]));
        }
        echo '<div class="notice notice-success"><p>Reels updated successfully!</p></div>';
    }
    
    ?>
    <div class="wrap">
        <h1>Featured Reels Settings</h1>
        <form method="post" action="">
            <?php wp_nonce_field('reels_options', 'reels_nonce'); ?>
            
            <table class="form-table">
                <?php for ($i = 1; $i <= 8; $i++) : 
                    $url = get_option("reel_url_$i", '');
                    $title = get_option("reel_title_$i", '');
                    $type = get_option("reel_type_$i", 'youtube');
                    $thumbnail = get_option("reel_thumbnail_$i", '');
                ?>
                <tr>
                    <th colspan="2"><h3>Reel #<?php echo $i; ?></h3></th>
                </tr>
                <tr>
                    <th scope="row">Platform</th>
                    <td>
                        <select name="reel_type_<?php echo $i; ?>">
                            <option value="youtube" <?php selected($type, 'youtube'); ?>>YouTube Short</option>
                            <option value="instagram" <?php selected($type, 'instagram'); ?>>Instagram Reel</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row">URL</th>
                    <td>
                        <input type="url" name="reel_url_<?php echo $i; ?>" value="<?php echo esc_attr($url); ?>" class="regular-text" placeholder="https://youtube.com/shorts/ABC123">
                    </td>
                </tr>
                <tr>
                    <th scope="row">Title</th>
                    <td>
                        <input type="text" name="reel_title_<?php echo $i; ?>" value="<?php echo esc_attr($title); ?>" class="regular-text" placeholder="Enter reel title">
                    </td>
                </tr>
                <tr>
                    <th scope="row">Custom Thumbnail URL <small>(Optional - for Instagram only)</small></th>
                    <td>
                        <input type="url" name="reel_thumbnail_<?php echo $i; ?>" value="<?php echo esc_attr($thumbnail); ?>" class="regular-text" placeholder="https://example.com/thumbnail.jpg">
                        <p class="description">If Instagram thumbnail doesn't load automatically, paste a direct image URL here.</p>
                    </td>
                </tr>
                <?php endfor; ?>
            </table>
            
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}


// Add meta box for resource difficulty and featured status
function add_resource_meta_boxes() {
    add_meta_box(
        'resource_settings',
        'Resource Settings',
        'resource_settings_callback',
        'post',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'add_resource_meta_boxes');

// Meta box callback
function resource_settings_callback($post) {
    wp_nonce_field('save_resource_settings', 'resource_settings_nonce');
    
    $difficulty = get_post_meta($post->ID, '_resource_difficulty', true);
    $featured = get_post_meta($post->ID, '_featured_resource', true);
    ?>
    <table class="form-table">
        <tr>
            <th><label for="resource_difficulty">Difficulty Level</label></th>
            <td>
                <select name="resource_difficulty" id="resource_difficulty">
                    <option value="beginner" <?php selected($difficulty, 'beginner'); ?>>🟢 Beginner</option>
                    <option value="intermediate" <?php selected($difficulty, 'intermediate'); ?>>🟡 Intermediate</option>
                    <option value="advanced" <?php selected($difficulty, 'advanced'); ?>>🔴 Advanced</option>
                </select>
            </td>
        </tr>
        <tr>
            <th><label for="featured_resource">Featured Resource</label></th>
            <td>
                <input type="checkbox" name="featured_resource" id="featured_resource" value="1" <?php checked($featured, '1'); ?> />
                <label for="featured_resource">Mark as featured resource</label>
            </td>
        </tr>
    </table>
    <?php
}

// Save meta box data
function save_resource_settings($post_id) {
    if (!isset($_POST['resource_settings_nonce']) || !wp_verify_nonce($_POST['resource_settings_nonce'], 'save_resource_settings')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (isset($_POST['resource_difficulty'])) {
        update_post_meta($post_id, '_resource_difficulty', sanitize_text_field($_POST['resource_difficulty']));
    }

    $featured = isset($_POST['featured_resource']) ? '1' : '0';
    update_post_meta($post_id, '_featured_resource', $featured);
}
add_action('save_post', 'save_resource_settings');


// Include sample data installer
require_once get_template_directory() . '/sample-data.php';

// ==================================================
// WORK WITH ME — Contact Form Handler
// ==================================================
function wwm_handle_contact_form() {
    // Verify nonce
    if ( ! isset( $_POST['wwm_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['wwm_nonce'] ) ), 'wwm_contact_nonce' ) ) {
        wp_safe_redirect( add_query_arg( 'wwm_error', '1', wp_get_referer() ) );
        exit;
    }

    $redirect_base = isset( $_POST['wwm_redirect'] )
        ? esc_url_raw( wp_unslash( $_POST['wwm_redirect'] ) )
        : home_url( '/work-with-me/' );

    // Sanitize & validate inputs
    $name    = isset( $_POST['wwm_name'] )    ? sanitize_text_field( wp_unslash( $_POST['wwm_name'] ) )    : '';
    $email   = isset( $_POST['wwm_email'] )   ? sanitize_email( wp_unslash( $_POST['wwm_email'] ) )         : '';
    $company = isset( $_POST['wwm_company'] ) ? sanitize_text_field( wp_unslash( $_POST['wwm_company'] ) ) : '';
    $type    = isset( $_POST['wwm_type'] )    ? sanitize_text_field( wp_unslash( $_POST['wwm_type'] ) )    : '';
    $message = isset( $_POST['wwm_message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['wwm_message'] ) ) : '';

    $allowed_types = array(
        'Sponsored Reels', 'YouTube Shorts', 'Product Review',
        'Website Feature', 'Affiliate Campaign', 'Brand Ambassadorship', 'Other',
    );

    if ( empty( $name ) || ! is_email( $email ) || empty( $message ) || ! in_array( $type, $allowed_types, true ) ) {
        wp_safe_redirect( add_query_arg( 'wwm_error', '1', $redirect_base ) );
        exit;
    }

    $to      = 'johnny5arcademail@gmail.com';
    $subject = '[Work With Me] ' . $type . ' inquiry from ' . $name;

    $body  = "New partnership inquiry from the Work With Me page.\n\n";
    $body .= "Name:              " . $name . "\n";
    $body .= "Email:             " . $email . "\n";
    $body .= "Company / Brand:   " . ( $company ?: '—' ) . "\n";
    $body .= "Partnership Type:  " . $type . "\n\n";
    $body .= "Message:\n" . $message . "\n";

    $headers = array(
        'Content-Type: text/plain; charset=UTF-8',
        'Reply-To: ' . $name . ' <' . $email . '>',
    );

    $sent = wp_mail( $to, $subject, $body, $headers );

    if ( $sent ) {
        wp_safe_redirect( add_query_arg( 'wwm_sent', '1', $redirect_base ) );
    } else {
        wp_safe_redirect( add_query_arg( 'wwm_error', '1', $redirect_base ) );
    }
    exit;
}
add_action( 'admin_post_wwm_contact',        'wwm_handle_contact_form' );
add_action( 'admin_post_nopriv_wwm_contact', 'wwm_handle_contact_form' );
?>
