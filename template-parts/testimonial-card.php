<?php
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="testimonial-card <?= esc_attr($args["classes"] ?? "") ?>">
    <div class="testimonial-card__wrapper">

        <hr>
        <?php echo get_theme_svg('assets/icons/icon-quote-2.svg'); ?>

        <div class="testimonial-card__inner">

            <?php $rating = 5; ?>
            <div class="testimonial-card__stars" role="img" aria-label="<?= esc_attr(sprintf(
                /* translators: %d: number of stars of the testimonial rating */
                __('Rated %d out of 5', 'growthlabseotheme03'),
                $rating
            )) ?>">
                <?php
                $i = 1;
                $star_svg = get_theme_svg('assets/icons/icon-star-2.svg');
                while ($i <= $rating) {
                    echo '<span class="star" aria-hidden="true">';
                    echo $star_svg;
                    echo '</span>';
                    $i++;
                }
                ?>
            </div>

            <blockquote class="testimonial-card__content tx-center">
                <p>
                    <?= wp_kses_post($args["content"] ?? "") ?>
                </p>
            </blockquote>

            <p class="testimonial-card__author tx-center">
                - <?= wp_kses_post($args["author"] ?? "") ?>
                <span><?= wp_kses_post($args["role"] ?? "") ?></span>
            </p>

        </div>

        <?php echo get_theme_svg('assets/icons/icon-quote-2.svg'); ?>
        <hr>

    </div>
</div>