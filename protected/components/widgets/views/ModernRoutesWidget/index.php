<?php
  if (count($cols) > 0) {
    ?>
      <ul class="modern-routes-list modern-routes-list-col-<?=count($cols)?>
      clearfix">
        <?php foreach($cols as $col) {
          if (count($col['data']) > 0) { ?>
            <li>
              <h5><?=$col['name']?></h5>
              <ul class="modern-route">
                <?php foreach($col['data'] as $link => $text) {?>
                  <li><a href="<?=$link?>"><?=$text?></a></li>
                <?php } ?>
              </ul>
            </li>
          <?php } ?>
        <?php } ?>
      </ul>
    <?php
  }
?>