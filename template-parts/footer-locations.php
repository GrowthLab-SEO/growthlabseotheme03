<section class="footer-locations">
    <div class="footer-locations__wrapper container">
        <?php
        foreach ($args['offices'] as $office):
            if (!empty($office)):
        ?>

                <div class="footer-locations__location">
                    <div class="office">
                        <?php
                        echo get_theme_svg('assets/icons/icon-location-2.svg');
                        ?>

                        <?php if (!empty($office['city'])): ?>
                            <<?= $office['city_tag'] ?? '' ?> class="office__city">

                                <?php
                                if (!empty($office['target_page_url'])) echo "<a href='{$office['target_page_url']}'>";
                                echo $office['city'];
                                if (!empty($office['target_page_url'])) echo "</a>";
                                ?>

                            </<?= $office['city_tag'] ?? '' ?>>
                        <?php endif ?>

                        <?php if (!empty($office['address'])): ?>
                            <p class="office__address">
                                <?= $office['address'] ?>
                            </p>
                        <?php endif ?>

                        <?php if (!empty($office['cta_link']['url'])): ?>
                            <a href="<?= esc_url($office['cta_link']['url']) ?>" target="<?= esc_attr($office['cta_link']['target']) ?>" class="office__link">
                                <?= esc_html($office['cta_link']['title']) ?>
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