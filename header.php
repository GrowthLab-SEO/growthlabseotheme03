<?php
if (!defined('ABSPATH')) {
    exit;
}
?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

    <?php wp_body_open(); ?>

    <?php
    $options = get_current_language_options();
    $options = fill_acf_group_defaults($options, 'options', 'option');
    foreach ($options as $key => $value) $$key = $value;

    $logo_link = home_url('/' . get_current_language()['slug']);
    $phone_number = $contact_phone ?: $main_phone_number;

    if (get_page_field('custom_header')) {
        $logo_link = get_page_field('logo_link') ?: $logo_link;
        $phone_number = get_page_field("contact_phone") ?: null;
        $top_callout = get_page_field("top_callout") ?: '';
    }

    $hero_style = is_listing_page() ? 'default' : (get_page_field('hero_style') ?: 'default');
    ?>

    <header role="banner" class="site-header 
    <?= !is_404() && $sticky_header ? "site-header--sticky" : "" ?>
    <?= $hero_style === "nohero" ? "nohero" : "" ?>">

        <div class="site-header__wrapper">

            <div class="site-header__logo">
                <a href="<?= esc_url($logo_link); ?>" class="site-logo" aria-label="<?= esc_attr(get_bloginfo('name')); ?>">
                    <?php
                    if (function_exists('the_custom_logo') && has_custom_logo()) {
                        $custom_logo_id = get_theme_mod('custom_logo');
                        $image = wp_get_attachment_image_url($custom_logo_id, "full");
                        echo img_generate_picture_tag(img: $image, max_size: "medium", alt_text: get_bloginfo('name'), is_priority: true);
                    }
                    ?>
                    <span>Site Logo</span>
                </a>
            </div>

            <?php
            if (get_page_field("custom_header") || has_nav_menu('main' . get_current_language_suffix())) {
                $menu_args = array(
                    'container'          => 'nav',
                    'container_role'     => 'navigation',
                    'container_class' => 'main-nav',
                    'menu_class'      => 'main-nav__menu',
                    'items_wrap'      => '<ul class="%2$s">%3$s</ul>',
                    'link_before'          => '<span>',
                    'link_after'              => '</span>',
                    // Never fall back to a list of every page when the menu location is empty
                    'fallback_cb'          => false
                );

                if (get_page_field("custom_header")) {
                    if (get_page_field("menu")) {
                        $menu_args['menu'] = get_page_field("menu");
                    } else {
                        $menu_args = [];
                    }
                } else {
                    $menu_args['theme_location'] = 'main' . get_current_language_suffix();
                }
            }
            ?>

            <?php if (!empty($menu_args)) : ?>
                <button class="site-header__mobile-btn" role="button" aria-label="Mobile Menu Button">
                    <div>
                        <hr>
                        <hr>
                        <hr>
                    </div>
                    <span>Menu</span>
                </button>
            <?php endif ?>

            <div class="site-header__navigation">

                <?php if (!empty($menu_args)) : ?>

                    <div class="site-header__close-btn" role="button" aria-label="Close Menu Button">
                        <hr>
                        <hr>
                    </div>

                    <?php wp_nav_menu($menu_args); ?>
                <?php endif ?>

            </div>

            <?php if ($phone_number): ?>
                <div class="site-header__callout">
                    <?php if (!empty($top_callout)): ?>
                        <span class="text"><?= wp_kses_post($top_callout) ?></span>
                    <?php endif; ?>

                    <a href="<?= get_phone_href($phone_number) ?>" class="phone btn btn--primary" aria-label="Call us at <?= esc_attr($phone_number) ?>">
                        <?php echo get_theme_svg('assets/icons/icon-phone.svg') ?>
                        <span>
                            <?= esc_html($phone_number) ?>
                        </span>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </header>

    <?php
    $hero_title = get_page_field("hero_properties")["hero_title"] ?? null;

    // Without a custom hero title the page title is the page heading
    if (empty($hero_title)) {
        $hero_title = '<h1>' . wp_kses_post(get_page_hero_title()) . '</h1>';
    } else {
        $hero_title = get_hero_heading($hero_title);
    }

    $args = array(
        "hero_title" => $hero_title,
        "hero_image_desktop_default" => $hero_image_desktop,
        "hero_image_tablet_default" => $hero_image_tablet,
        "hero_image_mobile_default" => $hero_image_mobile,
        "hero_cta_button_default" => $hero_cta_button,
    );

    if (!is_404()) {
        switch ($hero_style) {
            case 'home':
                get_template_part('template-parts/hero', 'homepage', $args);
                break;
            case 'default':
                get_template_part('template-parts/hero', 'default', $args);
                break;
            case 'home_2':
                get_template_part('template-parts/hero', 'homepage-v2', $args);
                break;
            case 'home_3':
                get_template_part('template-parts/hero', 'homepage-v3', $args);
                break;
            case 'home_4':
                get_template_part('template-parts/hero', 'homepage-v4', $args);
                break;
            case 'home_5':
                get_template_part('template-parts/hero', 'homepage-v5', $args);
                break;
            case 'home_6':
                get_template_part('template-parts/hero', 'homepage-v6', $args);
                break;
            case 'nohero':
                break;
            default:
                get_template_part('template-parts/hero', 'default', $args);
                break;
        }
    }
    ?>