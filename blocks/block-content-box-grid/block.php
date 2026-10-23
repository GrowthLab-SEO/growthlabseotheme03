<?php
if (!defined('ABSPATH')) {
    exit;
}

if (get_field('toggle_block')):
    foreach (get_block_fields($block) as $key => $value) $$key = $value;
?>

    <section<?= get_block_id_attribute($block_id ?? '') ?>
        class="block content-box-grid bg-bicolor<?= !empty($block['className']) ? ' ' . esc_attr($block['className']) : '' ?>"
        <?= block_style_attribute($block); ?>
        <?= get_block_extract_attribute($extract_block_from_content ?? false, $place ?? '') ?>>

        <div class="content-box-grid__wrapper container">

            <?php
            if (isset($pretitle) && $pretitle) print_title($pretitle, $pretitle_tag, "content-box-grid__pretitle pretitle");
            if (isset($title) && $title) print_title($title, $title_tag, "content-box-grid__title tx-center");
            ?>

            <?php if ($text_content): ?>
                <div class="content-box-grid__content formatted-text tx-center">
                    <?= $text_content ?>
                </div>
            <?php endif ?>

            <ul class="content-box-grid__grid">
                <?php
                if (!$is_a_menu && $items && count($items) > 0):

                    foreach ($items as $item):
                ?>

                        <li class="content-box">
                            <div class="content-box__wrapper">
                                <div class="content-box__inner">

                                    <?php if (($item['title'] ?? '') || ($item['text_content'] ?? '')): ?>

                                        <div class="content-box__content formatted-text">

                                            <?php
                                            $title_link_target = $item['title_link']['target'] ?? '';
                                            $title_html = !empty($item['title_link']) ? '<a href="' . esc_url($item['title_link']['url'] ?? '') . '"' . ($title_link_target ? ' target="' . esc_attr($title_link_target) . '"' . ($title_link_target === '_blank' ? ' rel="noopener noreferrer"' : '') : '') . ' aria-label="' . esc_attr($item['title'] ?? '') . '">' . ($item['title'] ?? '') . "</a>" : ($item['title'] ?? '');
                                            print_title($title_html, $item['title_tag'] ?? '', "content-box__title");
                                            ?>
                                            <?= $item['text_content'] ?? '' ?>

                                        </div>

                                    <?php endif ?>

                                </div>
                            </div>
                        </li>

                    <?php
                    endforeach;

                elseif ($is_a_menu && $menu):
                    $menu_items = wp_get_nav_menu_items($menu);
                    foreach ($menu_items as $menu_item):
                    ?>

                        <li class="content-box content-box--menu-item">
                            <a href="<?= esc_url($menu_item->url) ?>"<?= !empty($menu_item->target) ? ' target="' . esc_attr($menu_item->target) . '"' . ($menu_item->target === '_blank' ? ' rel="noopener noreferrer"' : '') : '' ?> class="content-box__wrapper" aria-label="<?= esc_attr($menu_item->title) ?>">
                                <div class="content-box__inner">

                                    <div class="content-box__content formatted-text">

                                        <strong class="content-box__title">
                                            <?= esc_html($menu_item->title) ?>
                                        </strong>

                                    </div>

                                </div>
                            </a>
                        </li>
                <?php
                    endforeach;
                endif;
                ?>
            </ul>

        </div>

    </section>

<?php
endif;
?>