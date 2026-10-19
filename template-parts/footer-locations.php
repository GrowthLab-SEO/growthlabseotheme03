<section class="footer-locations">
    <div class="footer-locations__wrapper container">
        <?php
        foreach ($args['offices'] as $office):
            if (!empty($office)):
                foreach ($office as $data => $value) $$data = $value;
        ?>

                <div class="footer-locations__location">
                    <div class="office">
                        <?php
                        include get_template_directory() . '/assets/icons/icon-location-2.svg';
                        ?>

                        <?php if (isset($city) && $city): ?>
                            <<?= $city_tag ?> class="office__city">

                                <?php
                                if (isset($target_page_url) && $target_page_url) echo "<a href='$target_page_url'>";
                                echo $city;
                                if (isset($target_page_url) && $target_page_url) echo "</a>";
                                ?>

                            </<?= $city_tag ?>>
                        <?php endif ?>

                        <?php if (isset($address) && $address): ?>
                            <p class="office__address">
                                <?= $address ?>
                            </p>
                        <?php endif ?>

                        <?php if (!empty($cta_link['url'])): ?>
                            <a href="<?= esc_url($cta_link['url']) ?>" target="<?= esc_attr($cta_link['target']) ?>" class="office__link">
                                <?= esc_html($cta_link['title']) ?>
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