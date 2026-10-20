<?php $form_link = !empty($args['link']) && is_array($args['link']) ? $args['link'] : null; ?>
<section class="contact-form-footer">
    <div class="contact-form-footer__wrapper container">
        <div class="contact-form-footer__inner">

            <div class="contact-form-footer__col">
                <?php
                print_title($args['title'], $args['tag'], "contact-form-footer__title");
                ?>

                <?php if ($form_link): ?>
                    <a href="<?= esc_url($form_link['url'] ?? '') ?>"<?= !empty($form_link['target']) ? ' target="' . esc_attr($form_link['target']) . '"' . ($form_link['target'] === '_blank' ? ' rel="noopener noreferrer"' : '') : '' ?> class="contact-form-footer__link formatted-text">
                        <?= esc_html($form_link['title'] ?? '') ?>
                    </a>
                <?php endif ?>
            </div>

            <div class="contact-form-footer__col">

                <div class="contact-form-footer__form">
                    <?php render_gravity_form($args['form'], display_title: false, display_description: false); ?>
                </div>

            </div>

        </div>

    </div>
</section>