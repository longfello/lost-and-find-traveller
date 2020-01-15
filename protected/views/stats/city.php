<?php
foreach ($citys as $city) {
    if (!empty($city['name'])) {
        ?>
        <div class="row">
            <?= $city['name'] ?>: <?= $city['cnt'] ?>
        </div>
        <?php
    }
}
?>
