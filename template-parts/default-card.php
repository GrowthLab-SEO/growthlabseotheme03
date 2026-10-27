<?php
if (!defined('ABSPATH')) {
    exit;
}

// Default image of the current language, falling back to the base options
$default_image = (get_current_language_options()['posts_default_image'] ?? null) ?: (get_field_options('options')['posts_default_image'] ?? null);
$link_target = $args['link_target'] ?? '';
?>

<div class="default-card <?php echo esc_attr($args["classes"] ?? '');
                            if (isset($args["content"]) && $args["content"]) echo " content" ?>">

    <?php
    if ($args['link_url'] && $args['link_url'] !== '') {
        echo '<a href="' . esc_url($args['link_url']) . '" class="default-card__wrapper"' . ($link_target ? ' target="' . esc_attr($link_target) . '"' . ($link_target === '_blank' ? ' rel="noopener noreferrer"' : '') : '') . ' aria-label="' . esc_attr($args['title']) . '">';
    } else {
        echo "<div class='default-card__wrapper'>";
    }
    if (isset($args['picture']) && $args['picture'] && $args['picture'] !== '') {
        img_print_picture_tag(img: $args["picture"], max_size: "cover-mobile", min_size: "featured-small", classes: "default-card__pic", context: 'card/default/picture');
    } elseif ($default_image) {
        img_print_picture_tag(img: $default_image, max_size: "content", min_size: "featured-small", classes: "default-card__pic", context: 'card/default/default-image');
    } else {
        echo get_theme_svg('assets/icons/icon-file-image.svg');
    }
    ?>

    <div class="default-card__inner tx-center">

        <?php print_title($args["title"], $args["title_tag"] ?? "p", "default-card__title"); ?>
        <p class="default-card__link"><?= esc_html__('READ MORE', 'growthlabseotheme03') ?></p>

    </div>

    <?php
    if ($args['link_url'] && $args['link_url'] !== '') {
        echo "</a>";
    } else {
        echo "</div>";
    }
    ?>
</div>