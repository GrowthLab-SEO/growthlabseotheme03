<?php
if (!defined('ABSPATH')) {
    exit;
}

if (get_field('toggle_block')):
    foreach (get_block_fields($block) as $key => $value) $$key = $value;
    $background_type = get_valid_field_choice('background_type', $background_type ?? '');
?>

    <section<?= get_block_id_attribute($block_id ?? '') ?>
        class="block contact-form <?= esc_attr(isset($background_type) && $background_type ? $background_type : 'light') ?><?= !empty($block['className']) ? ' ' . esc_attr($block['className']) : '' ?>"
        <?= block_style_attribute($block); ?>
        <?= get_block_extract_attribute($extract_block_from_content ?? false, $place ?? '') ?>>

        <?php
        if (isset($background_image) && $background_image && isset($background_type) && $background_type === 'image') img_print_picture_tag(img: $background_image, is_cover: true, classes: "contact-form__bg bg-image");
        ?>

        <div class="contact-form__layer">

            <div class="contact-form__wrapper <?= $reverse_columns ? "reverse" : "" ?>">

                <?php if (isset($side_image) && $side_image) img_print_picture_tag(img: $side_image, is_cover: true, classes: "contact-form__side-img") ?>

                <div class="contact-form__inner container">

                    <?php
                    if (isset($title) && $title) {
                        print_title($title, $title_tag, "contact-form__title");
                    }

                    if (isset($main_content) && !empty($main_content)):
                    ?>
                        <div class="contact-form__content formatted-text tx-center">
                            <?= $main_content ?>
                        </div>
                    <?php
                    endif;
                    ?>


                    <?php
                    $form_html = get_selected_form_html([
                        'contact_form'      => $contact_form ?? null,
                        'contact_form_type' => $contact_form_type ?? null,
                        'contact_form_code' => $contact_form_code ?? null,
                    ]);
                    if ($form_html !== ''):
                    ?>
                        <div class="form-box">

                            <div class="form-box__form">
                                <?= $form_html ?>
                            </div>

                        </div>
                    <?php endif; ?>

                </div>

            </div>

        </div>

    </section>

<?php
endif;
?>