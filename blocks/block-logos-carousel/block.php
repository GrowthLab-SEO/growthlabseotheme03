<?php
if (!defined('ABSPATH')) {
    exit;
}

if (get_field('toggle_block')):
    foreach (get_block_fields($block) as $key => $value) $$key = $value;
    $background_type = get_valid_field_choice('background_type', $background_type ?? '');
?>

    <section<?= get_block_id_attribute($block_id ?? '') ?>
        class="block logos-carousel <?= esc_attr(isset($background_type) && $background_type ? $background_type : 'dark') ?><?= !empty($block['className']) ? ' ' . esc_attr($block['className']) : '' ?>"
        <?= block_style_attribute($block); ?>
        <?= get_block_extract_attribute($extract_block_from_content ?? false, $place ?? '') ?>>

        <div class="logos-carousel__wrapper tx-center">
            <?php print_title($title, $title_tag, "logos-carousel__title tx-center") ?>

            <?php
            if (!empty($logos)):
            ?>
                <div class="logos-carousel__carousel">
                    <div class="splide">
                        <div class="splide__track">
                            <div class="splide__list">
                                <?php foreach ($logos as $logo):
                                    if (empty($logo)) continue;
                                    $has_link = !empty($logo['link']['url']);
                                ?>
                                    <div class="logo splide__slide">
                                        <?php if ($has_link): ?>
                                            <a href="<?= esc_url($logo['link']['url']); ?>"
                                                target="<?= esc_attr($logo['link']['target']); ?>"
                                                class="logo__link"
                                                aria-label="<?= esc_attr($logo['link']['title']); ?>">
                                            <?php endif; ?>

                                            <?= img_generate_picture_tag(
                                                img: $logo['logo'],
                                                max_size: "medium",
                                                classes: "logo__pic"
                                            ); ?>


                                            <?php if ($has_link): ?>
                                                <span><?= $logo['link']['title'] ?></span>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
            endif;
            ?>
        </div>
    </section>

<?php
endif;
?>