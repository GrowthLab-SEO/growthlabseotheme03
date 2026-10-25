<?php
/**
 * Design data of this theme. The theme functions shared by the GrowthLab themes (growthlab-theme-core) read it, so
 * this file is the one place where the themes differ in these values. It is not synced between themes.
 */

if (!defined('ABSPATH')) {
    exit;
}

// Default colors of the color scheme (Customizer > Colors), read by color-scheme.php, the editor palettes and TinyMCE
$default_colors = [
    'primary' => [
        'default' => '#0e5db8',
        'dark'    => '#031b3a',
        'light'   => '#2b7de0'
    ],
    'secondary' => [
        'default' => '#f4f8fd',
        'dark'    => '#b0d3f9',
        'light'   => '#FFFFFF'
    ],
    'tertiary' => [
        'default' => '#0b3293',
        'dark'    => '#071d3a',
        'light'   => '#869db7'
    ],
    'text' => '#232323'
];

// Default fonts (Customizer > Fonts), read by fonts-selector.php
$default_fonts = [
    'primary' => 'Figtree',
    'secondary' => 'Khand'
];

if (!function_exists('theme_get_config')) {
    /**
     * Design values of this theme, by key:
     * - font_faces: font files bundled in /fonts, by family (see theme_get_default_font_faces())
     * - above_fold_font_variants: font variants the header and the hero render, measured on this theme's templates;
     *   optional keys 'hero_styles', 'media' and 'when' (see theme_get_above_fold_font_variants())
     * - first_block_font_variants: font variants of the first block after the hero, by block name ("core" for core
     *   blocks, "default" for the rest). Codes: "s600" = secondary font weight 600, "p400" = primary font weight 400
     * - excerpt_length: words of the excerpts, 'default' and optionally by post type
     * - splide_blocks: blocks that render a Splide carousel (its scripts and CSS load only when one is on the page)
     * - template_styles_after_main: print the page template stylesheets after the main styles, so their rules win
     *   over the main rules of the same specificity (this theme's styles were written for that order)
     */
    function theme_get_config(string $key)
    {
        $homepage_heroes = ['home', 'home_2', 'home_3', 'home_4', 'home_5', 'home_6'];

        $config = [
            'font_faces' => [
                'Khand' => [
                    ['weight' => '400', 'style' => 'normal', 'file' => 'khand-v22-latin/khand-v22-latin-regular.woff2', 'display' => 'swap'],
                    ['weight' => '500', 'style' => 'normal', 'file' => 'khand-v22-latin/khand-v22-latin-500.woff2', 'display' => 'swap'],
                    ['weight' => '600', 'style' => 'normal', 'file' => 'khand-v22-latin/khand-v22-latin-600.woff2', 'display' => 'swap'],
                    ['weight' => '700', 'style' => 'normal', 'file' => 'khand-v22-latin/khand-v22-latin-700.woff2', 'display' => 'swap'],
                ],
                'Figtree' => [
                    ['weight' => '400', 'style' => 'normal', 'file' => 'figtree-v9-latin/figtree-v9-latin-regular.woff2', 'display' => 'swap'],
                    ['weight' => '400', 'style' => 'italic', 'file' => 'figtree-v9-latin/figtree-v9-latin-italic.woff2', 'display' => 'swap'],
                    ['weight' => '500', 'style' => 'normal', 'file' => 'figtree-v9-latin/figtree-v9-latin-500.woff2', 'display' => 'swap'],
                    ['weight' => '600', 'style' => 'normal', 'file' => 'figtree-v9-latin/figtree-v9-latin-600.woff2', 'display' => 'swap'],
                    ['weight' => '700', 'style' => 'normal', 'file' => 'figtree-v9-latin/figtree-v9-latin-700.woff2', 'display' => 'swap'],
                ],
            ],
            'above_fold_font_variants' => [
                // Hero titles
                ['role' => 'secondary', 'weight' => '600', 'style' => 'normal', 'hero_styles' => $homepage_heroes],
                ['role' => 'secondary', 'weight' => '700', 'style' => 'normal', 'hero_styles' => ['default']],
                // Header menu, phone button and hero text
                ['role' => 'primary', 'weight' => '400', 'style' => 'normal'],
                ['role' => 'primary', 'weight' => '600', 'style' => 'normal'],
                ['role' => 'primary', 'weight' => '700', 'style' => 'normal'],
                // Desktop menu only
                ['role' => 'primary', 'weight' => '500', 'style' => 'normal', 'media' => '(min-width: 1200px)'],
            ],
            'first_block_font_variants' => [
                'acf/contact-form'      => ['s600', 'p400', 'p700'],
                'acf/content-box-grid'  => ['s600', 's400', 'p400'],
                'acf/content-intro'     => ['s700', 's600', 's400', 'p400'],
                'acf/cta-box'           => ['s600', 's400'],
                'acf/faq'               => ['s600', 'p400'],
                'acf/featured-video'    => ['p400'],
                'acf/logos-carousel'    => ['s400'],
                'acf/office-locations'  => ['s600', 'p400', 'p700'],
                'acf/posts-carousel'    => ['s600', 's400', 'p400'],
                'acf/posts-grid'        => ['s600', 's400', 'p400'],
                'acf/selling-points'    => ['s600', 'p700'],
                'acf/two-columns'       => ['s600', 's400', 'p400'],
                'core'                  => ['p400', 'p700'],
                'default'               => ['s600', 's400', 'p400'],
            ],
            'excerpt_length' => ['default' => 10, 'team' => 50],
            'splide_blocks' => ['acf/logos-carousel', 'acf/posts-carousel'],
            'template_styles_after_main' => true,
        ];

        return $config[$key] ?? null;
    }
}
