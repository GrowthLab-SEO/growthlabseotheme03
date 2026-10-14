<?php
if (!defined('ABSPATH')) {
    exit;
}

if (get_field('toggle_block')):
    foreach (get_fields() as $key => $value) $$key = $value;
?>

    <section
        id="<?= $block_id ?? "" ?>"
        class="block cta-box <?= $box_style ?> <?= isset($background_type) && $background_type ? $background_type : 'light' ?><?= !empty($block['className']) ? ' ' . esc_attr($block['className']) : '' ?>"
        <?= block_style_attribute($block); ?>
        <?php if (isset($extract_block_from_content) && $extract_block_from_content) echo "data-extract='$place'"; ?>>

        <?php
        if (isset($background_image) && $background_image && isset($background_type) && $background_type === 'image') {
            img_print_picture_tag(img: $background_image, is_cover: true, classes: "cta-box__bg bg-image");
        } elseif (isset($background_video) && $background_video && isset($background_type) && $background_type === 'video') {
            $video_url = $background_video['url'] ?? null;
            if ($video_url) {
                $video_cover_url = is_array($background_video_cover ?? null) ? ($background_video_cover['url'] ?? null) : null;
                echo '<video class="cta-box__bg cta-box__bg--video" data-src="' . esc_url($video_url) . '"';
                if ($video_cover_url) {
                    echo ' data-poster="' . esc_url($video_cover_url) . '"';
                }
                echo ' muted loop playsinline preload="none"></video>';
            }
        }
        ?>

        <div class="cta-box__wrapper container">

            <?php
            $options = get_field_options("options");
            $logo_symbol = is_array($options) ? ($options["logo_symbol"] ?? null) : null;

            if (!empty($side_image)) {
                img_print_picture_tag(img: $side_image, max_size: "content", min_size: "featured-small", classes: "cta-box__side-image");
            } elseif (!empty($logo_symbol)) {
                img_print_picture_tag(img: $logo_symbol, max_size: "medium", classes: "cta-box__symbol");
            }
            ?>

            <div class="cta-box__inner">

                <div class="cta-box__title">
                    <?= isset($title) && $title ? $title : ""; ?>
                </div>

                <div class="cta-box__main">
                    <?php if ($box_style === "full" && isset($text_content) && $text_content): ?>
                        <div class="cta-box__content tx-center formatted-text">
                            <?= $text_content ?>
                        </div>
                    <?php endif ?>

                    <?php
                    if (isset($cta_buttons) && !empty($cta_buttons)):
                    ?>
                        <div class="cta-box__buttons">
                            <?php
                            $i = 0;
                            foreach ($cta_buttons as $button):
                                $cta_link = $button['link'];
                                $btn_style = $i === 0 ? "tertiary" : "tertiary-light";
                            ?>
                                <a href="<?= $cta_link['url'] ?>" target="<?= $cta_link['target'] ?>" class="btn--<?= $btn_style ?>" aria-label="<?= esc_attr($cta_link['title']) ?>">
                                    <span><?= $cta_link['title'] ?></span>
                                </a>
                            <?php
                                $i++;
                            endforeach;
                            ?>
                        </div>
                    <?php
                    endif;
                    ?>
                </div>

            </div>
        </div>

    </section>

<?php
endif;
?>