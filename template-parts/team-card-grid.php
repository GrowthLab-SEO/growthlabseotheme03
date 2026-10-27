<?php
if (!defined('ABSPATH')) {
    exit;
}
?>

<?php $link_target = $args['link_target'] ?? ''; ?>
<div class="team-card-grid <?= esc_attr($args["classes"] ?? '') ?>">
    <?php if ($args['link_url'] && $args['link_url'] !== ''): ?>
        <a href="<?= esc_url($args['link_url']) ?>"<?= $link_target ? ' target="' . esc_attr($link_target) . '"' . ($link_target === '_blank' ? ' rel="noopener noreferrer"' : '') : '' ?> class="team-card-grid__wrapper" aria-label="<?= esc_attr($args['name']) ?>">
            <?php
            // The decorative background frame is only shown behind a picture
            $bg_item_url = '';
            if (isset($args['picture']) && $args['picture'] && $args['picture'] !== '') {
                $bg_item_url = get_template_directory_uri() . '/assets/img/thumbnail-bg.webp';
                img_print_picture_tag(img: $args["picture"], max_size: "cover-mobile", min_size: "featured-small", classes: "team-card-grid__pic", context: 'card/team-grid/picture');
            } else {
                echo get_theme_svg('assets/icons/icon-file-image.svg');
            }
            ?>
            <?php if ($bg_item_url): ?>
                <img data-src="<?= esc_url($bg_item_url) ?>" alt="" class="team-card-grid__pic-wrapper lazy-image">
            <?php endif ?>

            <div class="team-card-grid__inner tx-center">

                <?php print_title($args["name"], $args["title_tag"] ?? "p", "team-card-grid__title"); ?>

                <p class="team-card-grid__role"><?= wp_kses_post($args["role"] ?? '') ?></p>

            </div>
        </a>
    <?php endif ?>
</div>