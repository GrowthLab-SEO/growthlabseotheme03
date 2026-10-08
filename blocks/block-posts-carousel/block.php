<?php
if (!defined('ABSPATH')) {
    exit;
}

if (get_field('toggle_block')):
    foreach (get_fields() as $key => $value) $$key = $value;

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
            $posts = isset($select_or_create_items) && $select_or_create_items ? $select_posts : [];
            break;
    }

    $args = array(
        'post_type' => $carousel_type,
        'posts_per_page' => -1,
        'post__in' => $posts,
        'post_status' => 'publish',
        'orderby' => 'post__in',
    );

    $query = new WP_Query($args);

    $options = get_field_options("options");

?>

    <section
        id="<?= $block_id ?? "" ?>"
        class="
        block 
        posts-carousel
        <?= isset($background_type) && $background_type ? $background_type : 'light' ?>
        <?= $carousel_type ?><?= !empty($block['className']) ? ' ' . esc_attr($block['className']) : '' ?>
        "
        <?= block_style_attribute($block); ?>
        <?php if (isset($extract_block_from_content) && $extract_block_from_content) echo "data-extract='$place'"; ?>>

        <?php
        if (isset($background_image) && $background_image && isset($background_type) && $background_type === 'image') img_print_picture_tag(img: $background_image, is_cover: true, classes: "posts-carousel__bg bg-image");

        if (isset($options["logo_symbol"]) && $options["logo_symbol"]) img_print_picture_tag(img: $options["logo_symbol"], classes: "posts-carousel__symbol");
        ?>

        <div class="posts-carousel__wrapper container">

            <div class="posts-carousel__content">
                <?php
                if (isset($title) && $title) print_title($title, $title_tag, "posts-carousel__title tx-center");
                if (isset($subtitle) && $subtitle) print_title($subtitle, $subtitle_tag, "posts-carousel__subtitle tx-center");
                ?>

                <?php if (isset($text_content) && $text_content): ?>
                    <div class="posts-carousel__text-content formatted-text tx-center">
                        <?= $text_content ?>
                    </div>
                <?php endif ?>


                <?php if (isset($cta_link) && $cta_link): ?>
                    <a href="<?= $cta_link['url'] ?>" target="<?= $cta_link['target'] ?>" class="posts-carousel__link btn--tertiary" aria-label="<?= esc_attr($cta_link['title']) ?>">
                        <span><?= $cta_link['title'] ?></span>
                    </a>
                <?php endif ?>
            </div>

            <div class="posts-carousel__carousel" data-type=<?= $carousel_type ?>>

                <div class="splide">
                    <div class="splide__track">
                        <div class="splide__list">

                            <?php
                            if (isset($custom_carousel) && !empty($custom_carousel) && !$select_or_create_items && $carousel_type === "") {
                                foreach ($custom_carousel as $item) {
                                    foreach ($item as $field => $data) $$field = $data;

                                    get_template_part('template-parts/default', 'card', array(
                                        "classes" => "splide__slide posts-carousel__card",
                                        "picture" => $picture ?? '',
                                        "title" => $title ?? '',
                                        "link_url" => $link['url'] ?? '',
                                        "link_target" => $link['target'] ?? '_self',
                                    ));
                                }
                            } elseif (isset($query) && $query->have_posts()) {
                                while ($query->have_posts()) {
                                    $query->the_post();

                                    if (!empty(get_fields(get_the_ID()))) foreach (get_fields(get_the_ID()) as $field => $content) $$field = $content;

                                    switch ($carousel_type) {
                                        case 'case-result':
                                            get_template_part('template-parts/result', 'card', array(
                                                "classes" => "splide__slide posts-carousel__card " . (isset($background_type) && $background_type ? $background_type : 'light'),
                                                "numerical_amount" => $numerical_amount,
                                                "case_title" => $case_title,
                                                "case_description" => $case_description,
                                            ));
                                            break;

                                        case 'team':
                                            get_template_part('template-parts/team', 'card', array(
                                                "classes" => "splide__slide posts-carousel__card " . (isset($background_type) && $background_type ? $background_type : 'light'),
                                                "picture" => $headshot,
                                                "title" => get_the_title(),
                                                "content" => get_the_excerpt(),
                                                "link_url" => get_the_permalink(),
                                                "link_target" => $link_target ?? '_self',
                                            ));
                                            break;

                                        case 'post':
                                            get_template_part('template-parts/post', 'card', array(
                                                "classes" => "splide__slide posts-carousel__card " . (isset($background_type) && $background_type ? $background_type : 'light'),
                                                "picture" => get_the_post_thumbnail_url(),
                                                "cat" =>  array_first(get_the_category())->name,
                                                "meta" => get_the_date(),
                                                "title" => get_the_title(),
                                                "excerpt" => get_the_excerpt(),
                                                "link_url" => get_the_permalink(),
                                                "link_target" => $link_target ?? '_self',
                                            ));
                                            break;

                                        case 'testimonial':
                                            get_template_part('template-parts/testimonial', 'card', array(
                                                "classes" => "splide__slide posts-carousel__card " . (isset($background_type) && $background_type ? $background_type : 'light'),
                                                "author" => $author_name,
                                                "role" => $author_role,
                                                "content" => $testimonial_content,
                                            ));
                                            break;

                                        default:
                                            get_template_part('template-parts/default', 'card', array(
                                                "classes" => "splide__slide posts-carousel__card " . (isset($background_type) && $background_type ? $background_type : 'light'),
                                                "picture" => get_the_post_thumbnail_url(),
                                                "title" => get_the_title(),
                                                "link_url" => get_the_permalink(),
                                                "link_target" => $link_target ?? '_self',
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

            <?php if ($carousel_type === "team"): ?>
                <div
                    id="thumbnail-carousel"
                    class="splide"
                    style="--size:<?= count($posts) ?>">
                    <div class="splide__track">
                        <ul class="splide__list">
                            <?php if (isset($query) && $query->have_posts()) :
                                $bg_item_url = get_template_directory_uri() . '\assets\img\thumbnail-bg.webp';
                                while ($query->have_posts()) :
                                    $query->the_post();

                                    if (!empty(get_fields(get_the_ID()))) foreach (get_fields(get_the_ID()) as $field => $content) $$field = $content;
                            ?>
                                    <li class="splide__slide thumbnail">
                                        <div class="thumbnail__wrapper">
                                            <img data-src="<?= $bg_item_url ?>" alt="" class="thumbnail__bg lazy-image">
                                            <?php
                                            if (isset($headshot) && $headshot) {
                                                img_print_picture_tag(img: $headshot, max_size: "medium", min_size: "medium", classes: "thumbnail__pic");
                                            } else {
                                                include get_template_directory() . '/assets/icons/icon-file-image.svg';
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