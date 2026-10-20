<?php
if (!defined('ABSPATH')) {
    exit;
}

if (get_field('toggle_block')):
    foreach (get_block_fields($block) as $key => $value) $$key = $value;
    $background_type = get_valid_field_choice('background_type', $background_type ?? '');

    switch ($carousel_type) {
        case 'case-result':
            $posts = $select_results_posts;
            break;

        case 'team':
            $posts = $select_team_members_posts;
            break;

        case 'post':
            $posts = $select_blog_posts;
            break;

        case 'testimonial':
            $posts = $select_testimonials_posts;
            break;

        default:
            $carousel_type = isset($select_or_create_items) && $select_or_create_items ? "any" : "";
            $posts = isset($select_or_create_items) && $select_or_create_items ? ($select_posts ?? []) : [];
            break;
    }

    // Selected post IDs (empty when nothing was selected) and custom items (only in custom mode without selection)
    $posts = !empty($posts) ? array_map('intval', (array) $posts) : [];
    $custom_items = $carousel_type === "" && !empty($custom_carousel) ? $custom_carousel : [];

    // Without selected posts or custom items the carousel is not rendered; editors see a notice instead
    if (empty($posts) && empty($custom_items)) {
        print_block_notice(__('Posts Carousel: no posts selected or created. This notice is only visible to editors.', 'growthlabseotheme03'));
        return;
    }

    // Only query when posts were selected, so an empty selection never falls back to every post
    $query = null;
    if (!empty($posts)) {
        $query = new WP_Query(array(
            'post_type' => $carousel_type,
            'posts_per_page' => count($posts),
            'post__in' => $posts,
            'post_status' => 'publish',
            'orderby' => 'post__in',
        ));
    }

    $posts_link_target = isset($posts_links_target) && $posts_links_target === '_blank' ? '_blank' : '_self';

    // Links this carousel with its own thumbnails carousel (team type), so several carousels on a page don't clash
    $thumbnails_id = wp_unique_id('posts-carousel-thumbnails-');

    $logo_symbol = (get_current_language_options()['logo_symbol'] ?? null) ?: (get_field_options('options')['logo_symbol'] ?? null);

?>

    <section<?= get_block_id_attribute($block_id ?? '') ?>
        class="
        block 
        posts-carousel
        <?= esc_attr(isset($background_type) && $background_type ? $background_type : 'light') ?>
        <?= esc_attr($carousel_type) ?><?= !empty($block['className']) ? ' ' . esc_attr($block['className']) : '' ?>
        "
        <?= block_style_attribute($block); ?>
        <?= get_block_extract_attribute($extract_block_from_content ?? false, $place ?? '') ?>>

        <?php
        if (isset($background_image) && $background_image && isset($background_type) && $background_type === 'image') img_print_picture_tag(img: $background_image, is_cover: true, classes: "posts-carousel__bg bg-image");

        if ($logo_symbol) img_print_picture_tag(img: $logo_symbol, classes: "posts-carousel__symbol");
        ?>

        <div class="posts-carousel__wrapper container">

            <div class="posts-carousel__content">
                <?php
                if (isset($title) && $title) print_title($title, $title_tag, "posts-carousel__title tx-center");
                if (isset($subtitle) && $subtitle) print_title($subtitle, $subtitle_tag, "posts-carousel__subtitle tx-center");
                ?>

                <?php if (isset($text_content) && $text_content): ?>
                    <div class="posts-carousel__text-content formatted-text tx-center">
                        <?= wp_kses_post($text_content) ?>
                    </div>
                <?php endif ?>


                <?php if (isset($cta_link) && $cta_link): ?>
                    <a href="<?= esc_url($cta_link['url']) ?>"<?= !empty($cta_link['target']) ? ' target="' . esc_attr($cta_link['target']) . '"' . ($cta_link['target'] === '_blank' ? ' rel="noopener noreferrer"' : '') : '' ?> class="posts-carousel__link btn--tertiary" aria-label="<?= esc_attr($cta_link['title']) ?>">
                        <span><?= esc_html($cta_link['title']) ?></span>
                    </a>
                <?php endif ?>
            </div>

            <div class="posts-carousel__carousel" data-type="<?= esc_attr($carousel_type) ?>"<?= $carousel_type === "team" ? ' data-thumbnails="' . esc_attr($thumbnails_id) . '"' : '' ?>>

                <div class="splide">
                    <div class="splide__track">
                        <div class="splide__list">

                            <?php
                            if (!empty($custom_items)) {
                                foreach ($custom_items as $item) {
                                    get_template_part('template-parts/default', 'card', array(
                                        "title_tag" => $items_title_tag ?? "p",
                                        "classes" => "splide__slide posts-carousel__card",
                                        "picture" => $item['picture'] ?? '',
                                        "title" => $item['title'] ?? '',
                                        "link_url" => $item['link']['url'] ?? '',
                                        "link_target" => $item['link']['target'] ?? '_self',
                                    ));
                                }
                            } elseif ($query && $query->have_posts()) {
                                while ($query->have_posts()) {
                                    $query->the_post();

                                    $post_fields = get_fields(get_the_ID()) ?: [];

                                    switch ($carousel_type) {
                                        case 'case-result':
                                            get_template_part('template-parts/result', 'card', array(
                                                "title_tag" => $items_title_tag ?? "h3",
                                                "classes" => "splide__slide posts-carousel__card " . (isset($background_type) && $background_type ? $background_type : 'light'),
                                                "numerical_amount" => ($post_fields['numerical_amount'] ?? ''),
                                                "case_title" => ($post_fields['case_title'] ?? ''),
                                                "case_description" => ($post_fields['case_description'] ?? ''),
                                            ));
                                            break;

                                        case 'team':
                                            get_template_part('template-parts/team', 'card', array(
                                                "title_tag" => $items_title_tag ?? "h3",
                                                "classes" => "splide__slide posts-carousel__card " . (isset($background_type) && $background_type ? $background_type : 'light'),
                                                "picture" => ($post_fields['headshot'] ?? ''),
                                                "title" => get_the_title(),
                                                "content" => get_the_excerpt(),
                                                "link_url" => get_the_permalink(),
                                                "link_target" => $posts_link_target,
                                            ));
                                            break;

                                        case 'post':
                                            get_template_part('template-parts/post', 'card', array(
                                                "title_tag" => $items_title_tag ?? "h3",
                                                "classes" => "splide__slide posts-carousel__card " . (isset($background_type) && $background_type ? $background_type : 'light'),
                                                "picture" => get_the_post_thumbnail_url(),
                                                "cat" =>  get_the_category()[0]->name ?? '',
                                                "meta" => get_the_date(),
                                                "title" => get_the_title(),
                                                "excerpt" => get_the_excerpt(),
                                                "link_url" => get_the_permalink(),
                                                "link_target" => $posts_link_target,
                                            ));
                                            break;

                                        case 'testimonial':
                                            get_template_part('template-parts/testimonial', 'card', array(
                                                "classes" => "splide__slide posts-carousel__card " . (isset($background_type) && $background_type ? $background_type : 'light'),
                                                "author" => ($post_fields['author_name'] ?? ''),
                                                "role" => ($post_fields['author_role'] ?? ''),
                                                "content" => ($post_fields['testimonial_content'] ?? ''),
                                            ));
                                            break;

                                        default:
                                            get_template_part('template-parts/default', 'card', array(
                                                "title_tag" => $items_title_tag ?? "h3",
                                                "classes" => "splide__slide posts-carousel__card " . (isset($background_type) && $background_type ? $background_type : 'light'),
                                                "picture" => get_the_post_thumbnail_url(),
                                                "title" => get_the_title(),
                                                "link_url" => get_the_permalink(),
                                                "link_target" => $posts_link_target,
                                            ));
                                            break;
                                    }
                                }
                            }
                            ?>

                        </div>
                    </div>

                    <?php
                    get_template_part('template-parts/splide', 'navigation', array(
                        'nav_link' => $cta_link,
                        'classes' => 'posts-carousel__arrows'
                    ));
                    ?>

                </div>

            </div>

            <?php if ($carousel_type === "team" && $query): ?>
                <div
                    id="<?= esc_attr($thumbnails_id) ?>"
                    class="posts-carousel__thumbnails splide"
                    style="--size:<?= count($posts) ?>">
                    <div class="splide__track">
                        <ul class="splide__list">
                            <?php if ($query->have_posts()) :
                                $query->rewind_posts();
                                $bg_item_url = get_template_directory_uri() . '/assets/img/thumbnail-bg.webp';
                                while ($query->have_posts()) :
                                    $query->the_post();

                                    $post_fields = get_fields(get_the_ID()) ?: [];
                            ?>
                                    <li class="splide__slide thumbnail">
                                        <div class="thumbnail__wrapper">
                                            <img data-src="<?= esc_url($bg_item_url) ?>" alt="" class="thumbnail__bg lazy-image">
                                            <?php
                                            if (!empty($post_fields['headshot'])) {
                                                img_print_picture_tag(img: ($post_fields['headshot'] ?? ''), max_size: "medium", min_size: "medium", classes: "thumbnail__pic");
                                            } else {
                                                echo get_theme_svg('assets/icons/icon-file-image.svg');
                                            }
                                            ?>
                                        </div>
                                    </li>

                            <?php endwhile;
                            endif; ?>
                        </ul>
                    </div>

                    <?php
                    get_template_part('template-parts/splide', 'navigation', array(
                        'nav_link' => "",
                        'classes' => 'thumbnails__arrows'
                    ));
                    ?>
                </div>
            <?php endif; ?>

        </div>
    </section>

<?php
    wp_reset_postdata();
endif;
?>