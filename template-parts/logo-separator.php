<?php
if (!defined('ABSPATH')) {
    exit;
}
foreach ($args as $key => $value) $$key = $value;
?>

<div class="logo-separator <?= esc_attr($classes); ?>">
    <hr>
    <?php
    // Logo symbol of the current language, falling back to the base options
    $logo_symbol = (get_current_language_options()['logo_symbol'] ?? null) ?: (get_field_options('options')['logo_symbol'] ?? null);

    if ($logo_symbol) {
        img_print_picture_tag(img: $logo_symbol, max_size: "medium");
    } else {
        echo get_theme_svg('assets/img/ampersand-symbol.svg');
    } ?>
    <hr>
</div>