<?php

/**
 * Functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package WordPress
 * @subpackage growthlabseotheme03
 * 
 */

// Theme text domain global
$GLOBALS['theme_text_domain'] = 'growthlabseotheme03';

// Custom breakpoints for this theme
$GLOBALS['breakpoints'] = [
    'mobile' => '0px',
    'tablet' => '768px',   // differs from the default
    'ldpi'   => '1024px',
    'mdpi'   => '1280px',  // differs from the default
    'hdpi'   => '1920px',  // differs from the default
];


// Include Theme Functions
$includes = [
    'theme-functions/theme-optimization.php',
    'theme-functions/color-scheme.php',
    'theme-functions/fonts-selector.php',
    'theme-functions/acf-functions.php',
    'theme-functions/helpers.php',
    'theme-functions/svg-support.php',
    'theme-functions/picture-optimization.php',
    'theme-functions/tiny-mce.php',
    'theme-functions/security-functions.php',
];

foreach ($includes as $file) {
    if (file_exists(get_template_directory() . '/' . $file)) {
        require_once get_template_directory() . '/' . $file;
    }
}


if (!function_exists('growthlabseotheme03_setup')) {
    /**
     * Sets up theme defaults and registers support for various WordPress features.
     *
     * Note that this function is hooked into the after_setup_theme hook, which
     * runs before the init hook. The init hook is too late for some features, such
     * as indicating support for post thumbnails.
     *
     *
     * @return void
     */

    function growthlabseotheme03_setup()
    {
        // Load the theme translations
        load_theme_textdomain('growthlabseotheme03', get_template_directory() . '/languages');

        /*
		* Let WordPress manage the document title.
		* This theme does not use a hard-coded <title> tag in the document head,
		* WordPress will provide it for us.
		*/
        add_theme_support('title-tag');

        /*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
        add_theme_support('post-thumbnails');

        // Custom Logo Support
        $defaults = array(
            'height'               => 200,
            'width'                => 360,
            'flex-height'          => true,
            'flex-width'           => true,
            'unlink-homepage-logo' => true,
        );

        add_theme_support('custom-logo', $defaults);

        // Disable hard cropping for all image sizes (write only when the stored value differs)
        if ((int) get_option('thumbnail_crop') !== 0) {
            update_option('thumbnail_crop', 0);
        }


        //Remove Sizes from default WordPress Image Sizes
        add_filter('intermediate_image_sizes', function ($sizes) {
            return array_diff($sizes, [
                'medium_large',
                '1536x1536',
                '2048x2048'
            ]);
        });

        add_filter('intermediate_image_sizes_advanced', function ($sizes) {
            unset($sizes['medium_large']);
            return $sizes;
        });

        //Add custom sized images
        // Covers
        add_image_size('cover-desktop', 1920, 1080, false);
        add_image_size('cover-tablet', 1280, 960, false);
        add_image_size('cover-mobile', 768, 432, false);

        // Covers portrait
        add_image_size('cover-tablet-portrait', 960, 1280, true);
        add_image_size('cover-mobile-portrait', 768, 1100, true);

        // Content
        add_image_size('content', 800, 600, false);

        // Cards
        add_image_size('featured-small', 480, 360, false);

        // Add custom image sizes to the media selector
        add_filter('image_size_names_choose', function ($sizes) {
            return array_merge([
                'thumbnail'    => __('Thumbnail'),
                'medium'       => __('Medium'),
                'large'        => __('Large'),
                'full'         => __('Full Size'),
                'cover-desktop'  => __('Cover Desktop (1920×1080)', 'growthlabseotheme03'),
                'cover-tablet'   => __('Cover Tablet (1280x960)', 'growthlabseotheme03'),
                'cover-mobile'   => __('Cover Mobile (768×432)', 'growthlabseotheme03'),
                'cover-tablet-portrait'   => __('Cover Tablet (960x1280)', 'growthlabseotheme03'),
                'cover-mobile-portrait'   => __('Cover Mobile (768×1100)', 'growthlabseotheme03'),
                'content'   => __('Content (800×600)', 'growthlabseotheme03'),
                'featured-small' => __('Featured Small (480×360)', 'growthlabseotheme03'),
            ], $sizes);
        });

        // Border, color, spacing and typography controls for core blocks (same as theme-02)
        add_theme_support('appearance-tools');

        // Register Navigation Menus
        register_nav_menus(
            array(
                'main' => esc_html__('Main Menu', 'growthlabseotheme03')
            )
        );

        if (function_exists('get_languages_map')) {
            foreach (get_languages_map() as $slug => $language) {
                register_nav_menus(
                    array(
                        'main_' . $slug => sprintf(esc_html__('Main Menu %s', 'growthlabseotheme03'), $language)
                    )
                );
            }
        }
    }
}
add_action('after_setup_theme', 'growthlabseotheme03_setup');

add_filter('wp_theme_json_data_theme', function ($theme_json) {
    global $default_colors, $default_fonts;

    $data = $theme_json->get_data();

    // ─── DISABLE UNWANTED GLOBAL CSS ────────────────────────────────────────
    $data['settings']['color']['defaultPalette']            = false;
    $data['settings']['color']['defaultGradients']          = false;
    $data['settings']['typography']['defaultFontSizes']     = false;
    $data['settings']['spacing']['defaultSpacingSizes']     = false;

    // ─── COLOR PALETTE ───────────────────────────────────────────────────────
    $data['settings']['color']['palette'] = [
        ['name' => __('Primary Color',        'growthlabseotheme03'), 'slug' => 'primary-color',        'color' => get_theme_mod('primary_color',        $default_colors['primary']['default'])],
        ['name' => __('Primary Color Dark',   'growthlabseotheme03'), 'slug' => 'primary-color-dark',   'color' => get_theme_mod('primary_color_dark',   $default_colors['primary']['dark'])],
        ['name' => __('Primary Color Light',  'growthlabseotheme03'), 'slug' => 'primary-color-light',  'color' => get_theme_mod('primary_color_light',  $default_colors['primary']['light'])],
        ['name' => __('Secondary Color',      'growthlabseotheme03'), 'slug' => 'secondary-color',      'color' => get_theme_mod('secondary_color',      $default_colors['secondary']['default'])],
        ['name' => __('Secondary Color Dark', 'growthlabseotheme03'), 'slug' => 'secondary-color-dark', 'color' => get_theme_mod('secondary_color_dark', $default_colors['secondary']['dark'])],
        ['name' => __('Secondary Color Light', 'growthlabseotheme03'), 'slug' => 'secondary-color-light', 'color' => get_theme_mod('secondary_color_light', $default_colors['secondary']['light'])],
        ['name' => __('Tertiary Color',       'growthlabseotheme03'), 'slug' => 'tertiary-color',       'color' => get_theme_mod('tertiary_color',       $default_colors['tertiary']['default'])],
        ['name' => __('Tertiary Color Dark',  'growthlabseotheme03'), 'slug' => 'tertiary-color-dark',  'color' => get_theme_mod('tertiary_color_dark',  $default_colors['tertiary']['dark'])],
        ['name' => __('Tertiary Color Light', 'growthlabseotheme03'), 'slug' => 'tertiary-color-light', 'color' => get_theme_mod('tertiary_color_light', $default_colors['tertiary']['light'])],
        ['name' => __('Text Color',           'growthlabseotheme03'), 'slug' => 'text-color',           'color' => get_theme_mod('text_color',           $default_colors['text'])],
    ];

    // ─── FONTS ───────────────────────────────────────────────────────────────
    $font_primary   = get_theme_mod('font_primary',   $default_fonts['primary']);
    $font_secondary = get_theme_mod('font_secondary', $default_fonts['secondary']);

    $data['settings']['typography']['fontFamilies'] = [
        ['name' => $font_primary,   'slug' => 'primary',   'fontFamily' => "\"{$font_primary}\", sans-serif"],
        ['name' => $font_secondary, 'slug' => 'secondary', 'fontFamily' => "\"{$font_secondary}\", serif"],
    ];

    $theme_json->update_with($data);
    return $theme_json;
});

/**
 * Remove link from custom logo
 */
function growthlabseotheme03_remove_custom_logo_link($html)
{
    // Extract just the <img> tag from the logo HTML
    preg_match('/<img[^>]*>/', $html, $matches);

    if (!empty($matches[0])) {
        return $matches[0];
    }

    return $html;
}
add_filter('get_custom_logo', 'growthlabseotheme03_remove_custom_logo_link');

/*Custom Excerpt Size*/
function growthlabseotheme03_custom_excerpt_length($length)
{
    if (get_post_type() === 'team') {
        return 50;
    }
    return 10;
}
add_filter('excerpt_length', 'growthlabseotheme03_custom_excerpt_length', 999);

/*custom excerpt more*/
function wpdocs_excerpt_more($more)
{
    return '...';
}
add_filter('excerpt_more', 'wpdocs_excerpt_more');

/**
 * Add scripts and styles.
 *
 *
 * @return void
 */

/**
 * Whether the current request renders a Splide carousel block, in the post content (synced patterns included)
 * or in a sidebar block widget. No theme template uses Splide outside these blocks.
 */
function growthlabseotheme03_request_uses_splide()
{
    $splide_blocks = ['acf/logos-carousel', 'acf/posts-carousel'];

    $contains_splide_block = function ($content) use ($splide_blocks) {
        foreach ($splide_blocks as $block_name) {
            if (str_contains((string) $content, '<!-- wp:' . $block_name . ' ')) return true;
        }
        return false;
    };

    foreach (get_current_post_blocks() as $block) {
        if (in_array($block['blockName'] ?? '', $splide_blocks, true)) return true;

        if (($block['blockName'] ?? '') === 'core/block' && !empty($block['attrs']['ref'])) {
            if ($contains_splide_block(get_post_field('post_content', (int) $block['attrs']['ref']))) return true;
        }
    }

    // Carousel blocks placed in the sidebars as block widgets
    $widget_blocks = get_option('widget_block', []);
    foreach (wp_get_sidebars_widgets() as $sidebar_id => $widget_ids) {
        if ($sidebar_id === 'wp_inactive_widgets' || !is_array($widget_ids)) continue;

        foreach ($widget_ids as $widget_id) {
            if (!preg_match('/^block-(\d+)$/', $widget_id, $matches)) continue;
            if ($contains_splide_block($widget_blocks[$matches[1]]['content'] ?? '')) return true;
        }
    }

    return false;
}

function inline_main_critical_css()
{
    // CSS files of the blocks used on the page, grouped by block type (collected on wp_enqueue_scripts)
    global $block_critical_css_files;
    $block_css_files = !empty($block_critical_css_files) ? $block_critical_css_files : array();

    // Dynamic Fonts
    $fonts_css = theme_get_fonts_css();

    // Dynamic Color Scheme
    $color_scheme = theme_get_customizer_css();

    $main_css_file = get_template_directory() . "/styles/main-min.css";
    $style_css_file = get_stylesheet_directory() . '/style.css';

    // Add Splide critical CSS only when carousels are present
    $splide_css_file = get_template_directory() . '/styles/vendor/splide/splide-core.min.css';
    $include_splide = file_exists($splide_css_file) && growthlabseotheme03_request_uses_splide();

    // Cache the final CSS, keyed by everything that affects it
    $files = array($main_css_file, $style_css_file);
    if ($include_splide) $files[] = $splide_css_file;
    foreach ($block_css_files as $type_files) {
        $files = array_merge($files, $type_files);
    }

    $file_mtimes = array();
    foreach ($files as $file) {
        $file_mtimes[$file] = @filemtime($file);
    }

    $cache_key = 'growthlabseotheme03_critical_css_' . md5(serialize(array(
        // This file builds the CSS, so changing it invalidates the cache too
        @filemtime(__FILE__),
        get_template_directory_uri(),
        $file_mtimes,
        $block_css_files,
        $include_splide,
        md5($fonts_css . $color_scheme),
    )));

    // Customizer previews change fonts/colors on every refresh, so don't fill the cache from them
    $use_cache = !is_customize_preview();
    $critical_css = $use_cache ? get_transient($cache_key) : false;

    if (false === $critical_css) {
        $critical_css = file_get_contents($main_css_file);
        $critical_css .= file_get_contents($style_css_file);
        $critical_css = $fonts_css . $color_scheme . $critical_css;

        if ($include_splide) {
            $critical_css .= "\n/* Splide Critical CSS */\n" . file_get_contents($splide_css_file);
        }

        // Add block critical CSS if any
        $block_critical_css = '';
        foreach ($block_css_files as $type => $type_files) {
            // Wrap type CSS in a comment for debugging
            $block_critical_css .= "/* Block type: {$type} */\n";
            foreach ($type_files as $file) {
                $block_critical_css .= file_get_contents($file) . "\n";
            }
        }

        if (!empty($block_critical_css)) {
            $critical_css .= "\n/* Block Critical CSS */\n" . $block_critical_css;
        }

        $critical_css = preg_replace('/\{theme-path\}/', get_template_directory_uri(), $critical_css);
        // Minify CSS: remove comments, extra spaces, and newlines
        $critical_css = preg_replace('/\/\*.*?\*\//s', '', $critical_css); // Remove CSS comments
        $critical_css = preg_replace('/\s+/', ' ', $critical_css); // Collapse whitespace runs into one space
        // Spaces around braces and semicolons are never significant; the ones around ":" and "," can be (".a :hover", strings)
        $critical_css = preg_replace('/\s*([{};])\s*/', '$1', $critical_css);
        $critical_css = trim($critical_css); // Trim leading/trailing whitespace

        // An expiration keeps the transient out of the autoloaded options
        if ($use_cache) set_transient($cache_key, $critical_css, WEEK_IN_SECONDS);
    }

    echo '<style id="main-css">' . $critical_css . '</style>';
}
add_action('wp_head', 'inline_main_critical_css', 1);

function growthlabseotheme03_scripts()
{
    if (is_admin()) return;

    // Move jQuery to footer (safe for GF)
    wp_scripts()->add_data('jquery', 'group', 1);
    wp_scripts()->add_data('jquery-core', 'group', 1);

    // Drop jQuery Migrate from the jquery dependencies (not needed for modern GF).
    // Deregistering it instead would make WordPress skip jquery and every script that depends on it.
    $jquery = wp_scripts()->query('jquery', 'registered');
    if ($jquery) {
        $jquery->deps = array_values(array_diff($jquery->deps, ['jquery-migrate']));
    }

    // Gravity Forms - remove maps
    wp_dequeue_script('gform_gravityforms_maps');

    // Main JS scripts.
    wp_enqueue_script(
        'growthlabseotheme03-main-scripts',
        get_template_directory_uri() . '/js/main-min.js',
        array(),
        filemtime(get_template_directory() . '/js/main-min.js'),
        true
    );
    wp_script_add_data('growthlabseotheme03-main-scripts', 'strategy', 'defer');
    wp_localize_script('growthlabseotheme03-main-scripts', 'siteData', [
        'homeURL' => home_url(),
        // Printed inside HTML attributes by main.js
        'i18n' => [
            'closeVideo'  => esc_attr__('Close video', 'growthlabseotheme03'),
            'videoDialog' => esc_attr__('Video player', 'growthlabseotheme03'),
        ],
    ]);

    // Styled select fields.
    wp_enqueue_script(
        'growthlabseotheme03-styled-select-fields',
        get_template_directory_uri() . '/js/styled-select-fields-min.js',
        array(),
        filemtime(get_template_directory() . '/js/styled-select-fields-min.js'),
        true
    );
    wp_script_add_data('growthlabseotheme03-styled-select-fields', 'strategy', 'defer');

    // Picture Optimization scripts.
    wp_enqueue_script(
        'growthlabseotheme03-picture-optimization',
        get_template_directory_uri() . '/js/picture-optimization-min.js',
        array(),
        filemtime(get_template_directory() . '/js/picture-optimization-min.js'),
        true
    );
    wp_script_add_data('growthlabseotheme03-picture-optimization', 'strategy', 'defer');

    // Third party JS scripts.
    wp_localize_script('growthlabseotheme03-main-scripts', 'splideData', [
        'url' => get_template_directory_uri() . '/js/vendor/splide/splide-min.js',
    ]);

    // IMask is registered here and enqueued only when a rendered form has a phone field (see gform_enqueue_scripts below)
    growthlabseotheme03_register_imask();

    // Load specific template stylesheet
    if (is_page() || is_single()) {
        if (!is_page_template('page-templates/template-full-width.php')) {
            wp_enqueue_style('growthlabseotheme03-template-default', get_template_directory_uri() . '/styles/page-templates/template-default-min.css', array(),  filemtime(get_template_directory() . '/styles/page-templates/template-default-min.css'));
        }
        if (is_singular('team')) {
            wp_enqueue_style('growthlabseotheme03-template-team-member', get_template_directory_uri() . '/styles/page-templates/template-team-member-min.css', array(),  filemtime(get_template_directory() . '/styles/page-templates/template-team-member-min.css'));
        }
    }
    if (is_listing_page()) {
        wp_enqueue_style('growthlabseotheme03-template-default', get_template_directory_uri() . '/styles/page-templates/template-default-min.css', array(),  filemtime(get_template_directory() . '/styles/page-templates/template-default-min.css'));
        wp_enqueue_style('growthlabseotheme03-blog', get_template_directory_uri() . '/styles/page-templates/template-blog-min.css', array(),  filemtime(get_template_directory() . '/styles/page-templates/template-blog-min.css'));
        wp_enqueue_script(
            'growthlabseotheme03-posts-filters',
            get_template_directory_uri() . '/js/posts-filters-min.js',
            array(),
            filemtime(get_template_directory() . '/js/posts-filters-min.js'),
            ["strategy" => "defer", "in_footer" => true]
        );
        wp_localize_script(
            'growthlabseotheme03-posts-filters',
            'postsFiltersData',
            array(
                'restUrl' => get_rest_url(null, 'wp/v2'),
                'perPage' => 9,
                'defaultImage' => function_exists('get_field_options')
                    ? (get_field_options('options')['posts_default_image']['url'] ?? '')
                    : '',
            )
        );
    }
}

add_action('wp_enqueue_scripts', 'growthlabseotheme03_scripts');

// IMask for Gravity Forms phone fields. Phone inputs are masked on load and again whenever Gravity Forms re-renders a
// form (AJAX submissions, multi-page forms); data-imask prevents masking an input twice.
function growthlabseotheme03_register_imask()
{
    if (wp_script_is('imask', 'registered')) return;

    wp_register_script('imask', get_template_directory_uri() . '/js/vendor/imask/imask-min.js', array(), filemtime(get_template_directory() . '/js/vendor/imask/imask-min.js'), true);
    wp_add_inline_script('imask', "
        (function () {
            function maskPhoneInputs() {
                document.querySelectorAll('.ginput_container_phone input').forEach(function (input) {
                    if (input.dataset.imask) return;
                    input.dataset.imask = '1';
                    IMask(input, { mask: '(000) 000-0000' });
                });
            }
            document.addEventListener('DOMContentLoaded', function () {
                maskPhoneInputs();
                if (window.jQuery) jQuery(document).on('gform_post_render', maskPhoneInputs);
            });
            document.addEventListener('gform/post_render', maskPhoneInputs);
        })();
    ");
}

add_action('gform_enqueue_scripts', function ($form) {
    foreach ($form['fields'] ?? array() as $field) {
        if (($field->type ?? '') === 'phone') {
            growthlabseotheme03_register_imask();
            wp_enqueue_script('imask');
            return;
        }
    }
});

// Preload background image to prevent CLS
add_action('wp_head', function () {
    $image_path = get_template_directory_uri() . '/assets/img/transparent-bg.webp';
    echo '<link rel="preload" as="image" href="' . esc_url($image_path) . '" />';
}, 1);

// Add theme and parent/child theme classes to body
add_filter('body_class', function ($classes) {
    if (is_child_theme()) {
        $theme = wp_get_theme();
        $classes[] = 'theme-child-' . sanitize_html_class($theme->get_stylesheet());
        $classes[] = 'theme-parent-' . sanitize_html_class($theme->get_template());
    }
    return $classes;
});

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 *
 * @return void
 */
function growthlabseotheme03_widgets_init()
{

    register_sidebar(
        array(
            'name'          => esc_html__('Default Sidebar', 'growthlabseotheme03'),
            'id'            => 'sidebar-default',
            'description'   => esc_html__('Add widgets here to appear in the page sidebar.', 'growthlabseotheme03'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<p class="widget-title">',
            'after_title'   => '</p>',
        )
    );

    register_sidebar(
        array(
            'name'          => esc_html__('Blog Sidebar', 'growthlabseotheme03'),
            'id'            => 'sidebar-blog',
            'description'   => esc_html__('Add widgets here to appear in the Blog sidebar.', 'growthlabseotheme03'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<p class="widget-title">',
            'after_title'   => '</p>',
        )
    );

    if (function_exists('get_languages_map')) {
        foreach (get_languages_map() as $slug => $language) {
            register_sidebar(
                array(
                    'name'          => sprintf(esc_html__('%s Sidebar', 'growthlabseotheme03'), $language),
                    'id'            => "sidebar-default-{$slug}",
                    'description'   => esc_html__('Add widgets here to appear in the page sidebar.', 'growthlabseotheme03'),
                    'before_widget' => '<div id="%1$s" class="widget %2$s">',
                    'after_widget'  => '</div>',
                    'before_title'  => '<p class="widget-title">',
                    'after_title'   => '</p>',
                )
            );

            register_sidebar(
                array(
                    'name'          => sprintf(esc_html__('%s Blog Sidebar', 'growthlabseotheme03'), $language),
                    'id'            => "sidebar-blog-{$slug}",
                    'description'   => esc_html__('Add widgets here to appear in the Blog sidebar.', 'growthlabseotheme03'),
                    'before_widget' => '<div id="%1$s" class="widget %2$s">',
                    'after_widget'  => '</div>',
                    'before_title'  => '<p class="widget-title">',
                    'after_title'   => '</p>',
                )
            );
        }
    }
}

add_action('widgets_init', 'growthlabseotheme03_widgets_init');


// Gravity Forms 
add_filter('gform_disable_css', '__return_true');
add_filter('gform_disable_theme_editor_styles', '__return_true');
add_filter('gform_init_scripts_footer', '__return_true');


//Import All Theme Icons to the Media Library
//Run only once after theme installation
function import_theme_images_to_folder()
{
    // Path to your theme's images folder
    $image_folder = get_template_directory() . '/assets/icons/';

    // Create custom folder in uploads
    $upload_dir = wp_upload_dir();
    $custom_folder = $upload_dir['basedir'] . '/theme-icons/';
    $custom_url = $upload_dir['baseurl'] . '/theme-icons/';

    // Create folder if it doesn't exist
    if (!file_exists($custom_folder)) {
        wp_mkdir_p($custom_folder);
    }

    // Get all image files
    $images = glob($image_folder . '*.{svg}', GLOB_BRACE);

    foreach ($images as $image_path) {
        $filename = basename($image_path);

        // Check if file already exists
        $existing = get_posts([
            'post_type' => 'attachment',
            'meta_query' => [[
                'key' => '_wp_attached_file',
                'value' => 'theme-icons/' . $filename,
                'compare' => '='
            ]]
        ]);

        if (!empty($existing)) continue;

        // Copy file to custom folder
        $new_file = $custom_folder . $filename;
        copy($image_path, $new_file);

        // Create attachment
        $attachment = [
            'post_mime_type' => mime_content_type($new_file),
            'post_title' => sanitize_file_name(pathinfo($filename, PATHINFO_FILENAME)),
            'post_content' => '',
            'post_status' => 'inherit'
        ];

        $attach_id = wp_insert_attachment($attachment, $new_file);

        // Update attachment metadata with correct path
        update_attached_file($attach_id, 'theme-icons/' . $filename);

        // Generate metadata
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        $attach_data = wp_generate_attachment_metadata($attach_id, $new_file);
        wp_update_attachment_metadata($attach_id, $attach_data);
    }
}

// Run once from the "Import theme icons" admin bar link (yoursite.com/?import_theme_images=1 plus a nonce)
if (
    isset($_GET['import_theme_images'])
    && current_user_can('manage_options')
    && wp_verify_nonce($_GET['_wpnonce'] ?? '', 'import_theme_images')
) {
    import_theme_images_to_folder();
    wp_die('Images imported to /uploads/theme-icons/!');
}

// Admin bar links for the theme maintenance actions (nonce protected)
add_action('admin_bar_menu', function ($wp_admin_bar) {
    if (!current_user_can('manage_options')) return;

    $wp_admin_bar->add_node([
        'id'    => 'growthlabseotheme03-tools',
        'title' => esc_html__('Theme Tools', 'growthlabseotheme03'),
    ]);

    $wp_admin_bar->add_node([
        'id'     => 'growthlabseotheme03-clear-block-cache',
        'parent' => 'growthlabseotheme03-tools',
        'title'  => esc_html__('Clear block cache', 'growthlabseotheme03'),
        'href'   => wp_nonce_url(admin_url('index.php?clear_block_cache=1'), 'clear_block_cache'),
    ]);

    $wp_admin_bar->add_node([
        'id'     => 'growthlabseotheme03-import-theme-images',
        'parent' => 'growthlabseotheme03-tools',
        'title'  => esc_html__('Import theme icons', 'growthlabseotheme03'),
        'href'   => wp_nonce_url(home_url('/?import_theme_images=1'), 'import_theme_images'),
    ]);
}, 100);

// Force the widget sections to stay available
add_action('customize_register', function ($wp_customize) {
    // Check for the widgets panel and force it active
    $widgets_panel = $wp_customize->get_panel('widgets');
    if ($widgets_panel) {
        $widgets_panel->active_callback = '__return_true';
    }

    // Force the specific sections to always be active
    $sidebar_default = $wp_customize->get_section('sidebar-widgets-sidebar-default');
    if ($sidebar_default) {
        $sidebar_default->active_callback = '__return_true';
    }

    $sidebar_blog = $wp_customize->get_section('sidebar-widgets-sidebar-blog');
    if ($sidebar_blog) {
        $sidebar_blog->active_callback = '__return_true';
    }
}, 999);

// Prevent the Customizer from hiding widget sections dynamically
add_action('customize_controls_print_footer_scripts', function () {
?>
    <script>
        (function($) {
            wp.customize.bind('ready', function() {
                // Force the widget panels to stay visible
                var widgetsPanel = wp.customize.panel('widgets');
                if (widgetsPanel) {
                    widgetsPanel.active.set(true);

                    // Prevent it from being hidden
                    widgetsPanel.active.validate = function() {
                        return true;
                    };
                }

                // Force specific sections
                ['sidebar-widgets-sidebar-default', 'sidebar-widgets-sidebar-blog'].forEach(function(sectionId) {
                    var section = wp.customize.section(sectionId);
                    if (section) {
                        section.active.set(true);
                        section.active.validate = function() {
                            return true;
                        };
                    }
                });
            });
        })(jQuery);
    </script>
<?php
}, 999);

// Add dynamic versioning to theme scripts and styles
add_filter('script_loader_src', 'add_dynamic_version_to_theme_assets', 10, 2);
add_filter('style_loader_src', 'add_dynamic_version_to_theme_assets', 10, 2);

/**
 * Version theme scripts and styles by their file mtime, for files in the parent theme
 * and, when one is active, in the child theme.
 */
function add_dynamic_version_to_theme_assets($src, $handle)
{
    // Child theme first, since its URI may start with the parent theme URI
    $theme_dirs = array();
    if (is_child_theme()) {
        $theme_dirs[get_stylesheet_directory_uri()] = get_stylesheet_directory();
    }
    $theme_dirs[get_template_directory_uri()] = get_template_directory();

    // Remove scheme for comparison
    $src_normalized = preg_replace('(^https?:)', '', $src);

    foreach ($theme_dirs as $theme_dir_uri => $theme_dir) {
        $theme_dir_normalized = preg_replace('(^https?:)', '', $theme_dir_uri);

        if (strpos($src_normalized, $theme_dir_normalized . '/') !== 0) continue;

        // Extract path without query string
        $path_parts = parse_url($src);
        $path = $path_parts['path'] ?? '';

        // Remove the theme directory from path to get relative path
        $relative_path = str_replace(parse_url($theme_dir_uri, PHP_URL_PATH), '', $path);
        $file_path = $theme_dir . $relative_path;

        if (file_exists($file_path)) {
            $version = filemtime($file_path);
            // Remove existing ver parameter and add new one
            $src = remove_query_arg('ver', $src);
            $src = add_query_arg('ver', $version, $src);
        }
        break;
    }
    return $src;
}
