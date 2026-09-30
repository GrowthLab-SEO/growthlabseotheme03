<?php
if (!defined('ABSPATH')) {
    exit;
}
?>
<div class="result-card <?= $args["classes"] ?>">
    <div class="result-card__wrapper">
        <div class="result-card__inner">

            <p class="result-card__title"><?= $args["case_title"] ?></p>

            <?php if (!empty($args["numerical_amount"]) && $args["numerical_amount"]) : ?>
                <span class="result-card__amount">$<?= number_format($args["numerical_amount"], 0, '.', ','); ?></span>
            <?php endif ?>

            <?php if (isset($args["case_description"]) && $args["case_description"]): ?>
                <p class="result-card__description"><?= $args["case_description"] ?></p>
            <?php endif ?>
        </div>
    </div>
</div>