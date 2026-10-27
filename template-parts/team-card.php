<?php
if (!defined('ABSPATH')) {
    exit;
}

$link_target = $args['link_target'] ?? '';
?>
<div class="team-card <?= esc_attr($args["classes"] ?? '') ?>">
    <div class="team-card__wrapper">

        <div class='team-card__pic-wrapper'>
            <?php
            if (isset($args['picture']) && $args['picture']) {
                img_print_picture_tag(img: $args["picture"], max_size: "large", min_size: "featured-small", classes: "team-card__pic", context: 'card/team/picture');
            } else {
                echo get_theme_svg('assets/icons/icon-file-image.svg');
            }
            ?>
        </div>

        <div class="team-card__inner">
            <?php print_title($args["title"], $args["title_tag"] ?? "p", "team-card__title"); ?>

            <p class="team-card__content formatted-text"><?php if ($args["content"] && !empty($args["content"])) echo wp_kses_post($args["content"]) ?></p>

            <?php if ($args['link_url']): ?>
                <div class="team-card__btn">
                    <a href="<?= esc_url($args['link_url']) ?>"<?= $link_target ? ' target="' . esc_attr($link_target) . '"' . ($link_target === '_blank' ? ' rel="noopener noreferrer"' : '') : '' ?> class="btn btn--tertiary-light" aria-label="<?= esc_attr(sprintf(/* translators: %s: team member name */ __('Meet %s', 'growthlabseotheme03'), $args['title'])) ?>">
                        <span><?= sprintf(/* translators: %s: team member name */ esc_html__('meet %s', 'growthlabseotheme03'), wp_kses_post($args["title"])) ?></span>
                    </a>
                </div>
            <?php endif ?>
        </div>
    </div>

</div>