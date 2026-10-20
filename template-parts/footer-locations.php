<section class="footer-locations">
    <div class="footer-locations__wrapper container">
        <?php
        foreach ((array) ($args["offices"] ?? []) as $office):
            if (!empty($office)):
        ?>

                <div class="footer-locations__location">
                    <div class="office">
                        <?php
                        echo get_theme_svg('assets/icons/icon-location-2.svg');
                        ?>

                        <?php
                        if (!empty($office['city'])) {
                            $city = $office['city'];
                            if (!empty($office['target_page_url'])) $city = '<a href="' . esc_url($office['target_page_url']) . '">' . $city . '</a>';
                            print_title($city, $office['city_tag'] ?? 'p', 'office__city');
                        }
                        ?>

                        <?php if (!empty($office['address'])): ?>
                            <p class="office__address">
                                <?= wp_kses_post($office['address']) ?>
                            </p>
                        <?php endif ?>

                        <?php if (!empty($office['cta_link']['url'])): ?>
                            <a href="<?= esc_url($office['cta_link']['url']) ?>"<?= !empty($office['cta_link']['target']) ? ' target="' . esc_attr($office['cta_link']['target']) . '"' . ($office['cta_link']['target'] === '_blank' ? ' rel="noopener noreferrer"' : '') : '' ?> class="office__link">
                                <?= esc_html($office['cta_link']['title'] ?? '') ?>
                            </a>
                        <?php endif ?>

                    </div>
                </div>

        <?php
            endif;
        endforeach;
        ?>
    </div>
</section>