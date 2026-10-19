<?php
if (!defined('ABSPATH')) {
    exit;
}
?>
<?php
$location = $args['location'];
$accordion = $args['accordion'] ?? false;
$tp_url = $location['target_page_url'];
$city = $tp_url ? "<a href='" . esc_url($tp_url) . "' target='_blank' aria-label='" . esc_attr($location['city']) . "'>" : '';
$city .= $location['city'];
$city .= $tp_url ? "</a>" : '';
?>
<div class="location-card 
<?php if ($accordion) echo "accordion "; ?>
<?= isset($args['classes']) ? $args['classes'] : '' ?>">

    <div class="location-card__container">
        <?php if ($accordion) print_title($city, $location['city_tag'], "location-card__city accordion__heading"); ?>

        <div class="location-card__wrapper accordion__content">

            <div class="location-card__inner accordion__inner">
                <div class="location-card__row">
                    <?php if ($location['google_maps_embed_code']): ?>
                        <?php
                        $args = array(
                            "iframe_src" => $location['google_maps_embed_code'],
                            "name" => $location['city'],
                            "classes" => "location-card__map"
                        );
                        get_template_part("template-parts/google", "maps", $args);
                        ?>
                    <?php endif ?>
                </div>
                <div class="location-card__row">

                    <div class="location-card__icon">
                        <?php include get_template_directory() . '/assets/icons/icon-location-2.svg'; ?>
                    </div>

                    <?php if (!$accordion) print_title($city, $location['city_tag'], "location-card__city accordion__heading tx-center"); ?>

                    <p class="location-card__address tx-center"><?= $location['address'] ?></p>

                    <?php if (!empty($location['cta_link']['url'])): ?>
                        <a href="<?= esc_url($location['cta_link']['url']) ?>" target="<?= esc_attr($location['cta_link']['target'] ?: '_self') ?>" class="location-card__cta tx-center" aria-label="<?= esc_attr($location['cta_link']['title']) ?>">
                            <?= esc_html($location['cta_link']['title']) ?>
                        </a>
                    <?php endif ?>

                    <?php if ($location['phone']): ?>
                        <a href="tel:+1<?= get_flat_number($location['phone']) ?>" class="location-card__btn btn--primary" aria-label="Call us at <?= esc_attr($location['phone']) ?>">
                            <span>
                                <?= $location['phone'] ?>
                            </span>
                        </a>
                    <?php endif ?>

                </div>
            </div>
        </div>
    </div>
</div>