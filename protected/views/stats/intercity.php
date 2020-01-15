<?php
foreach ($citys as $city) {
    if (!empty($city['name'])) {
        ?>
        <div class="row">
            <?= $city['name'] ?> - <?= $city['name2'] ?> : <?= $city['cnt'] ?>
        </div>
        <?php
    }
}
?>
