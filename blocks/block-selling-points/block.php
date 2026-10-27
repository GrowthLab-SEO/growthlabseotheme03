<?php
if (!defined('ABSPATH')) {
    exit;
}

if (get_field('toggle_block')):
    foreach (get_block_fields($block) as $key => $value) $$key = $value;
    $background_type = get_valid_field_choice('background_type', $background_type ?? '');
?>

    <section<?= get_block_id_attribute($block_id ?? '') ?>
        class="
        block 
        selling-points
        <?= esc_attr(isset($background_type) && $background_type ? $background_type : 'light') ?><?= !empty($block['className']) ? ' ' . esc_attr($block['className']) : '' ?>
        "
        <?= block_style_attribute($block); ?>
        <?= get_block_extract_attribute($extract_block_from_content ?? false, $place ?? '') ?>>

        <?php
        if (isset($background_image) && $background_image && $background_type === 'image') img_print_picture_tag(img: $background_image, is_cover: true, classes: "selling-points__bg bg-image gradient-overlay", context: 'block/selling-points/background');
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
                        $item_wrapper = !empty($item['link']) ? "a" : "div";
                        $item_target = $item['link']['target'] ?? '';
                ?>

                        <<?= $item_wrapper ?> class="item" <?= !empty($item['link']) ? 'href="' . esc_url($item['link']['url'] ?? '') . '"' . ($item_target ? ' target="' . esc_attr($item_target) . '"' . ($item_target === '_blank' ? ' rel="noopener noreferrer"' : '') : '') : "" ?>>
                            <?php
                            if ($items_background_image && !empty($items_background_image)) {
                                img_print_picture_tag(img: $items_background_image, is_cover: false, classes: "item__bg", max_size: "featured-small", min_size: "thumbnail", context: 'block/selling-points/items-background');
                            } else {
                                $bg_item_url = get_template_directory_uri() . '/assets/img/figure_selling_points.webp';
                                echo '<img data-src="' . esc_url($bg_item_url) . '" alt="" class="item__bg lazy-image">';
                            }
                            ?>

                            <div class="item__icon">
                                <?php if (!empty($item['icon'])) img_print_picture_tag(img: $item['icon'], max_size: "thumbnail", context: 'block/selling-points/icon'); ?>
                            </div>
                            <?php print_title($item['title'] ?? '', !empty($item['title_tag']) ? $item['title_tag'] : "h3", "item__title tx-center"); ?>
                        </<?= $item_wrapper ?>>

                <?php
                    endforeach;
                endif;
                ?>
            </div>

            <?php if ($cta_link): ?>
                <a href="<?= esc_url($cta_link['url']) ?>"<?= !empty($cta_link['target']) ? ' target="' . esc_attr($cta_link['target']) . '"' . ($cta_link['target'] === '_blank' ? ' rel="noopener noreferrer"' : '') : '' ?> class="cta-btn btn--tertiary" aria-label="<?= esc_attr($cta_link['title']) ?>">
                    <span><?= esc_html($cta_link['title']) ?></span>
                </a>
            <?php endif ?>

        </div>

    </section>

<?php
endif;
?>