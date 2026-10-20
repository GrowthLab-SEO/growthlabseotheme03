  <?php
  if (!defined('ABSPATH')) {
    exit;
  }
  ?>

  <footer id="site-footer" role="contentinfo" class="site-footer">

    <?php
    $options = get_current_language_options();
    $options = fill_acf_group_defaults($options, 'options', 'option');
    foreach ($options as $key => $value) $$key = $value;
    $phone_number = $contact_phone ?: $main_phone_number;


    if (isset($footer_background_image) && $footer_background_image) img_print_picture_tag(img: $footer_background_image, is_cover: true, classes: "site-footer__bg bg-image");
    ?>

    <?php
    // Form
    if (!$form_section['hide_section'] && !get_page_field('hide_form_section')) {
      $args = [
        'title' => !empty($form_section['contact_form_title']) ? $form_section['contact_form_title'] : "",
        'tag' => !empty($form_section['contact_form_title_tag']) ? $form_section['contact_form_title_tag'] : "p",
        'link' => !empty($form_section['contact_form_link']) ? $form_section['contact_form_link'] : "",
        'form' => !empty($form_section['contact_form']) ? $form_section['contact_form'] : null,
      ];
      get_template_part('template-parts/footer', 'form', $args);
    }
    ?>

    <?php
    // Menu
    if (!$menu_section['hide_section'] && !get_page_field('hide_menu_section')) {
      if (isset($menu_section['menu']) && $menu_section['menu']) {
        wp_nav_menu(
          array(
            'menu'  => $menu_section['menu'],
            'container'          => 'nav',
            'container_class' => 'footer-menu',
            'menu_class'      => 'footer-menu__wrapper container',
            'items_wrap'      => '<ul class="%2$s">%3$s</ul>',
            'link_before'          => '<span>',
            'link_after'              => '</span>'
          )
        );
      }
    }
    ?>

    <?php
    // Locations
    if (!$locations_section['hide_section'] && !get_page_field('hide_locations_section')) {
      $args = [
        'offices' => $offices
      ];
      get_template_part('template-parts/footer', 'locations', $args);
    }
    ?>

    <?php
    // Social
    if (!$social_networks_section['hide_section'] && !get_page_field('hide_social_networks_section')) : ?>

      <section class="footer-social">
        <div class="footer-social__wrapper">
          <?php get_template_part('template-parts/social', 'networks'); ?>
        </div>
      </section>

    <?php endif ?>

    <?php
    //Copy
    if (!$copyright_section['hide_section'] && !get_page_field("hide_copyright_section")):
    ?>
      <section class="footer-copyright">
        <div class="footer-copyright__wrapper container">
          <div class="footer-copyright__advertisement">
            <?= $copyright_section['copyright'] ?? '' ?>
          </div>

          <a href="https://growthlabseo.com/" target="_blank" class="footer-copyright__logo" aria-label="Growth Lab SEO">
            <img src="<?= get_template_directory_uri() . "/assets/img/Growth-Lab-Logo.png" ?>" alt="Growth Lab SEO Logo" width="270" height="50">
          </a>

        </div>
      </section>
    <?php
    endif;
    ?>
  </footer>

  <?php wp_footer(); ?>

  </body>

  </html>