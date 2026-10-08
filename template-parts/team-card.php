<?php
if (!defined('ABSPATH')) {
    exit;
}
?>
<div class="team-card <?= $args["classes"] ?>">
    <div class="team-card__wrapper">

        <div class='team-card__pic-wrapper'>
            <?php
            if (isset($args['picture']) && $args['picture']) {
                img_print_picture_tag(img: $args["picture"], max_size: "large", min_size: "featured-small", classes: "team-card__pic");
            } else {
                include get_template_directory() . '/assets/icons/icon-file-image.svg';
            }
            ?>
        </div>

        <div class="team-card__inner">
            <p class="team-card__title"><?= $args["title"] ?></p>

            <p class="team-card__content formatted-text"><?php if ($args["content"] && !empty($args["content"])) echo $args["content"] ?></p>

            <?php if ($args['link_url']): ?>
                <div class="team-card__btn">
                    <a href="<?= $args['link_url'] ?>" target="<?= $args['link_target'] ?>" class="btn btn--tertiary-light" aria-label="Meet <?= esc_attr($args['title']) ?>">
                        <span>meet <?= $args["title"] ?></span>
                    </a>
                </div>
            <?php endif ?>
        </div>
    </div>

</div>