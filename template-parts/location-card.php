<?php
if (!defined('ABSPATH')) {
    exit;
}
?>
<?php
$location = $args['location'];
$accordion = $args['accordion'] ?? false;
$tp_url = $location["target_page_url"] ?? "";
$city = $tp_url ? "<a href='" . esc_url($tp_url) . "' aria-label='" . esc_attr($location['city'] ?? '') . "'>" : '';
$city .= $location['city'] ?? '';
$city .= $tp_url ? "</a>" : '';
?>
<div class="location-card 
<?php if ($accordion) echo "accordion "; ?>
<?= esc_attr($args["classes"] ?? "") ?>">

    <div class="location-card__container">
        <?php if ($accordion) print_title($city, $location['city_tag'] ?? 'p', "location-card__city accordion__heading"); ?>

        <div class="location-card__wrapper accordion__content">

            <div class="location-card__inner accordion__inner">
                <div class="location-card__row">
                    <?php if (!empty($location["google_maps_embed_code"])): ?>
                        <?php
                        $args = array(
                            "iframe_src" => $location['google_maps_embed_code'],
                            "name" => $location['city'] ?? '',
                            "classes" => "location-card__map"
                        );
                        get_template_part("template-parts/google", "maps", $args);
                        ?>
                    <?php endif ?>
                </div>
                <div class="location-card__row">

                    <div class="location-card__icon">
                        <?php echo get_theme_svg('assets/icons/icon-location-2.svg'); ?>
                    </div>

                    <?php if (!$accordion) print_title($city, $location['city_tag'] ?? 'p', "location-card__city accordion__heading tx-center"); ?>

                    <p class="location-card__address tx-center"><?= wp_kses_post($location["address"] ?? "") ?></p>

                    <?php if (!empty($location['cta_link']['url'])): ?>
                        <a href="<?= esc_url($location['cta_link']['url']) ?>"<?= !empty($location['cta_link']['target']) ? ' target="' . esc_attr($location['cta_link']['target']) . '"' . ($location['cta_link']['target'] === '_blank' ? ' rel="noopener noreferrer"' : '') : '' ?> class="location-card__cta tx-center" aria-label="<?= esc_attr($location["cta_link"]["title"] ?? "") ?>">
                            <?= esc_html($location["cta_link"]["title"] ?? "") ?>
                        </a>
                    <?php endif ?>

                    <?php if (!empty($location["phone"])): ?>
                        <a href="<?= get_phone_href($location["phone"]) ?>" class="location-card__btn btn--primary" aria-label="Call us at <?= esc_attr($location['phone']) ?>">
                            <span>
                                <?= esc_html($location["phone"]) ?>
                            </span>
                        </a>
                    <?php endif ?>

                </div>
            </div>
        </div>
    </div>
</div>