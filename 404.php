<?php
if (!defined('ABSPATH')) {
    exit;
}
get_header();
?>

<main id="main-content" role="main" class="error-404">

    <div class="container">

        <p class="h1 tx-center" aria-hidden="true">404:</p>
        <h1 class="h2 tx-center error-404__title"><?= esc_html__('Page not found', 'growthlabseotheme03') ?></h1>

    </div>

</main>

<?php get_footer() ?>