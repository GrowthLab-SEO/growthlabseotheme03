<?php
if (!defined('ABSPATH')) {
    exit;
}

// Default image of the current language, falling back to the base options
$default_image = (get_current_language_options()['posts_default_image'] ?? null) ?: (get_field_options('options')['posts_default_image'] ?? null);
$link_target = $args['link_target'] ?? '';
$link_target_attr = $link_target ? ' target="' . esc_attr($link_target) . '"' . ($link_target === '_blank' ? ' rel="noopener noreferrer"' : '') : '';
?>
<article class="post-card <?= esc_attr($args["classes"] ?? '') ?>">
    <div class="post-card__wrapper">

        <?php
        if ($args['link_url']) {
            echo '<a href="' . esc_url($args['link_url']) . '" class="post-card__pic-wrapper"' . $link_target_attr . ' aria-label="' . esc_attr($args['title']) . '">';
        } else {
            echo "<div class='post-card__pic-wrapper'>";
        }
        if (isset($args['cat']) && $args['cat']) {
            echo "<span class='post-card__cat'>" . esc_html($args['cat']) . "</span>";
        }
        if (isset($args['picture']) && $args['picture']) {
            img_print_picture_tag(img: $args["picture"], max_size: "content", min_size: "featured-small", classes: "post-card__pic");
        } elseif ($default_image) {
            img_print_picture_tag(img: $default_image, max_size: "content", min_size: "featured-small", classes: "post-card__pic");
        } else {
            echo get_theme_svg('assets/icons/icon-file-image.svg');
        }
        if ($args['link_url']) {
            echo "</a>";
        } else {
            echo "</div>";
        }

        ?>

        <div class="post-card__inner">
            <span class="post-card__meta">
                <?php echo get_theme_svg('assets/icons/icon-calendar.svg') ?>
                <?= esc_html($args["meta"] ?? '') ?></span>

            <?php print_title($args["title"], $args["title_tag"] ?? "p", "post-card__title"); ?>

            <p class="post-card__content"><?= wp_kses_post($args["excerpt"] ?? '') ?></p>

            <?php if ($args['link_url']): ?>
                <a href="<?= esc_url($args['link_url']) ?>"<?= $link_target_attr ?> class="post-card__link" aria-label="<?= esc_attr(sprintf(/* translators: %s: post title */ __('Read more about %s', 'growthlabseotheme03'), $args['title'])) ?>">
                    <span><?= esc_html__('Read More', 'growthlabseotheme03') ?>
                        <?php echo get_theme_svg('assets/icons/icon-arrow-right.svg') ?>
                    </span>
                </a>
            <?php endif ?>
        </div>
    </div>
</article>