<?php
if (!defined('ABSPATH')) {
    exit;
}

if (get_field('toggle_block')):
    foreach (get_block_fields($block) as $key => $value) $$key = $value;
    $background_type = get_valid_field_choice('background_type', $background_type ?? '');
?>

    <section<?= get_block_id_attribute($block_id ?? '') ?>
        class="block faq <?= esc_attr($background_type ?: 'light') ?><?= !empty($block['className']) ? ' ' . esc_attr($block['className']) : '' ?>"
        <?= block_style_attribute($block); ?>
        <?= get_block_extract_attribute($extract_block_from_content ?? false, $place ?? '') ?>>

        <?php
        if (isset($background_image) && $background_image && isset($background_type) && $background_type === 'image') img_print_picture_tag(img: $background_image, is_cover: true, classes: "faq__bg bg-image gradient-overlay");
        ?>

        <?php
        $logo_symbol = (get_current_language_options()['logo_symbol'] ?? null) ?: (get_field_options('options')['logo_symbol'] ?? null);
        if (isset($side_image) && !empty($side_image)) {
            img_print_picture_tag(img: $side_image, max_size: "content", min_size: "featured-small", classes: "faq__side-image");
        } elseif ($logo_symbol) {
            img_print_picture_tag(img: $logo_symbol, max_size: "medium", classes: "faq__symbol");
        }
        ?>

        <div class="faq__wrapper container">

            <div class="faq__inner">
                <?php
                print_title($title, $title_tag, "faq__title");
                ?>

                <?php if ($text_content): ?>
                    <div class="faq__content formatted-text">
                        <?= $text_content ?>
                    </div>
                <?php endif ?>

                <?php if (isset($faq_items) && !empty($faq_items)) : ?>
                    <div class="faq__items">

                        <div class="faq__col"></div>
                        <div class="faq__col"></div>

                        <?php
                        $i = 1;
                        foreach ($faq_items as $item) :
                            $answer_id = wp_unique_id('faq-answer-');
                        ?>

                            <div class="faq__item accordion">
                                <div class="faq__question accordion__heading" role="button" tabindex="0" aria-expanded="false" aria-controls="<?= esc_attr($answer_id) ?>">
                                    <span><?= $i < 10 ? "0" . $i : "$i" ?></span>
                                    <?php print_title($item['question'] ?? '', $item['question_tag'] ?? '') ?>
                                </div>
                                <div class="faq__answer accordion__content" id="<?= esc_attr($answer_id) ?>">
                                    <div class="formatted-text accordion__inner">
                                        <?= $item['answer'] ?? '' ?>
                                    </div>
                                </div>
                            </div>

                        <?php
                            $i++;
                        endforeach;
                        ?>

                    </div>
                <?php endif ?>


                <?php if ($cta_link): ?>
                    <div class="faq__btn">
                        <a href="<?= esc_url($cta_link['url']) ?>"<?= !empty($cta_link['target']) ? ' target="' . esc_attr($cta_link['target']) . '"' . ($cta_link['target'] === '_blank' ? ' rel="noopener noreferrer"' : '') : '' ?> class="btn--tertiary" aria-label="<?= esc_attr($cta_link['title']) ?>">
                            <span><?= esc_html($cta_link['title']) ?></span>
                        </a>
                    </div>
                <?php endif ?>


            </div>
        </div>

    </section>

<?php
endif;
?>