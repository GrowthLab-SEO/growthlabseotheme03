<?php
// Child themes whose footer.php only passes the Gravity Form ID ('form')
if (!isset($args['form_html'])) $args['form_html'] = get_selected_form_html(['contact_form' => $args['form'] ?? null]);

$form_link = !empty($args['link']) && is_array($args['link']) ? $args['link'] : null; ?>
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

                <?php if (!empty($args['form_html'])): ?>
                    <div class="contact-form-footer__form">
                        <?= $args['form_html'] ?>
                    </div>
                <?php endif; ?>

            </div>

        </div>

    </div>
</section>