<?php
if (!defined('ABSPATH')) {
    exit;
}

// Default image of the current language, falling back to the base options
$default_image = (get_current_language_options()['posts_default_image'] ?? null) ?: (get_field_options('options')['posts_default_image'] ?? null);
?>

<div class="default-card <?php echo $args["classes"];
                            if (isset($args["content"]) && $args["content"]) echo " content" ?>">

    <?php
    if ($args['link_url'] && $args['link_url'] !== '') {
        echo "<a href=" . $args['link_url'] . " class='default-card__wrapper' target=" . $args['link_target'] . " aria-label='" . esc_attr($args['title']) . "'>";
    } else {
        echo "<div class='default-card__wrapper'>";
    }
    if (isset($args['picture']) && $args['picture'] && $args['picture'] !== '') {
        img_print_picture_tag(img: $args["picture"], max_size: "cover-mobile", min_size: "featured-small", classes: "default-card__pic");
    } elseif ($default_image) {
        img_print_picture_tag(img: $default_image, max_size: "content", min_size: "featured-small", classes: "default-card__pic");
    } else {
        echo get_theme_svg('assets/icons/icon-file-image.svg');
    }
    ?>

    <div class="default-card__inner tx-center">

        <?php print_title($args["title"], $args["title_tag"] ?? "p", "default-card__title"); ?>
        <p class="default-card__link">READ MORE</p>

    </div>

    <?php
    if ($args['link_url'] && $args['link_url'] !== '') {
        echo "</a>";
    } else {
        echo "</div>";
    }
    ?>
</div>