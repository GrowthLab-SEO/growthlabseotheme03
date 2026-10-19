<?php
if (!defined('ABSPATH')) {
    exit;
}

if (get_field('toggle_block')):
    foreach (get_fields() as $key => $value) $$key = $value;
    $background_type = get_valid_field_choice('background_type', $background_type ?? '');
?>

    <section<?= get_block_id_attribute($block_id ?? '') ?>
        class="
        block 
        content-intro
        <?= esc_attr(isset($background_type) && $background_type ? $background_type : 'light') ?>
        <?= esc_attr(isset($background_type) && $background_type ? $background_type : 'light') ?><?= !empty($block['className']) ? ' ' . esc_attr($block['className']) : '' ?>
        "
        <?= block_style_attribute($block); ?>
        <?= get_block_extract_attribute($extract_block_from_content ?? false, $place ?? '') ?>>

        <?php
        if (isset($background_image) && $background_image && $background_type === 'image') img_print_picture_tag(img: $background_image, is_cover: true, classes: "content-intro__bg bg-image gradient-overlay");
        ?>

        <div class="content-intro__wrapper container">

            <?php
            foreach (get_field("columns") as $column => $col) $$column = $col;
            $featured_image_position = get_valid_field_choice('columns.featured_image_position', $featured_image_position ?? '');
            ?>

            <div class="col col--image <?= esc_attr(isset($featured_image_position) && $featured_image_position ? $featured_image_position : "left") ?>">
                <?php
                if (isset($featured_image) && $featured_image) {
                    $options = get_field_options("options");

                    if ($options["logo_symbol"]) {
                        img_print_picture_tag(img: $options["logo_symbol"], max_size: "medium", classes: "col__symbol");
                    }

                    img_print_picture_tag(
                        img: $featured_image,
                        max_size: $featured_image_position !== "bottom" ? "content" : "cover-desktop",
                        min_size: "featured-small",
                        classes: "col__image"
                    );
                }
                ?>
            </div>

            <div class="col">
                <?php
                if (isset($pretitle) && $pretitle) print_title($pretitle, $pretitle_tag, "col__pretitle pretitle");
                if (isset($title) && $title) echo "<div class='col__title'>$title</div>";
                ?>
                <div class="col__content formatted-text">
                    <?= $content ?? "" ?>
                </div>
            </div>

            <?php foreach (get_field("cta_box") as $box => $data) $$box = $data; ?>
            <div class="inner-cta-box">

                <?php
                if (isset($featured_image_position) && $featured_image_position !== "bottom") :
                    if ($content && isset($content)):
                ?>
                        <div class="inner-cta-box__content formatted-text">
                            <?= $content ?>
                        </div>
                <?php
                    endif;
                endif;
                ?>

                <div class="inner-cta-box__buttons">
                    <?php if ($cta_link && isset($cta_link) && $cta_link["url"]): ?>
                        <a href="<?= esc_url($cta_link["url"]) ?>" target="<?= esc_attr($cta_link["target"]) ?>" class="btn<?= $background_type && isset($background_type) && $background_type !== 'light' ? '--tertiary' : '--tertiary' ?>" aria-label="<?= esc_attr($cta_link["title"]) ?>">
                            <span><?= esc_html($cta_link["title"]) ?></span>
                        </a>
                    <?php endif ?>
                    <?php if ($cta_link_2 && isset($cta_link_2) && $cta_link_2["url"]): ?>
                        <a href="<?= esc_url($cta_link_2["url"]) ?>" target="<?= esc_attr($cta_link_2["target"]) ?>" class="btn" aria-label="<?= esc_attr($cta_link_2["title"]) ?>">
                            <?php if (str_contains($cta_link_2["url"], 'tel:')) include get_template_directory() . '/assets/icons/icon-phone.svg' ?>
                            <span><?= esc_html($cta_link_2["title"]) ?></span>
                        </a>
                    <?php endif ?>
                </div>
            </div>
        </div>

    </section>

<?php
endif;
?>