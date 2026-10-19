<?php
if (!defined('ABSPATH')) {
    exit;
}

if (get_field('toggle_block')):
    foreach (get_fields() as $key => $value) $$key = $value;
?>

    <section<?= get_block_id_attribute($block_id ?? '') ?>
        class="
        block 
        selling-points
        <?= isset($background_type) && $background_type ? $background_type : 'light' ?><?= !empty($block['className']) ? ' ' . esc_attr($block['className']) : '' ?>
        "
        <?= block_style_attribute($block); ?>
        <?php if (isset($extract_block_from_content) && $extract_block_from_content) echo "data-extract='$place'"; ?>>

        <?php
        if (isset($background_image) && $background_image && $background_type === 'image') img_print_picture_tag(img: $background_image, is_cover: true, classes: "selling-points__bg bg-image gradient-overlay");
        ?>


        <div class="selling-points__wrapper container">

            <?php
            if (isset($title) && $title) print_title($title, $title_tag, "selling-points__title tx-center");
            if (isset($subtitle) && $subtitle) print_title($subtitle, $subtitle_tag, "selling-points__subtitle tx-center");
            ?>

            <?php if (isset($subtitle) && $text_content): ?>
                <div class="selling-points__content formatted-text tx-center">
                    <?= $text_content ?>
                </div>
            <?php endif ?>

            <div class="selling-points__items">
                <?php
                if (isset($items) && !empty($items)):
                    foreach ($items as $item):
                        foreach ($item as $key => $value) $$key = $value;
                        $item_wrapper = isset($link) && $link ? "a" : "div";
                ?>

                        <<?= $item_wrapper ?> class="item" <?= isset($link) && $link ? "href='" . esc_url($link['url']) . "' target='" . esc_attr($link['target']) . "'" : "" ?>>
                            <?php
                            if ($items_background_image && !empty($items_background_image)) {
                                img_print_picture_tag(img: $items_background_image, is_cover: false, classes: "item__bg", max_size: "featured-small", min_size: "thumbnail");
                            } else {
                                $bg_item_url = get_template_directory_uri() . '/assets/img/figure_selling_points.webp';
                                echo '<img data-src="' . esc_url($bg_item_url) . '" alt="" class="item__bg lazy-image">';
                            }
                            ?>

                            <div class="item__icon">
                                <?php if (isset($icon) && $icon) img_print_picture_tag(img: $icon, max_size: "thumbnail"); ?>
                            </div>
                            <?php print_title($title, isset($title_tag) && $title_tag ? $title_tag : "h3", "item__title tx-center"); ?>
                        </<?= $item_wrapper ?>>

                <?php
                    endforeach;
                endif;
                ?>
            </div>

            <?php if ($cta_link): ?>
                <a href="<?= esc_url($cta_link['url']) ?>" target="<?= esc_attr($cta_link['target']) ?>" class="cta-btn btn--tertiary" aria-label="<?= esc_attr($cta_link['title']) ?>">
                    <span><?= esc_html($cta_link['title']) ?></span>
                </a>
            <?php endif ?>

        </div>

    </section>

<?php
endif;
?>