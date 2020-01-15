<div id="fixed-banner-wrapper">
  <div class="fixed-menu" style="top: 260px; ">
    <?php
    $this->widget('application.extensions.service_menu.service_menu', array('buttons' => array(array('idx' => '8', 'href' => '/lostFound/create?type_order=1'), array('idx' => '9', 'href' => '/lostFound/create?type_order=0'),)));
    ?>
    <div class="block-clearfix"></div>
    <?php
    if (!empty($categoryStat)){
      ?>
      <div class="lf_stat">
        <center class="title-stat"><b>Статистика бюро находок</b></center>
        <table>
          <tr>
            <td width="100%" colspan="2"><b>Группы</b></td>
            <td class="right_t"><b>Найдено</b></td>
            <td class="right_t"><b>Потеряно</b></td>
          </tr>
          <?php
          $i = 1;
          foreach ($categoryStat as $id => $cat) {
            $count_lost = isset($cat['count_lost']) ? $cat['count_lost'] + 152 * ($i % 5 + 1) : 82 * ($i % 3 + 1);
            $count_found = isset($cat['count_found']) ? $cat['count_found'] + 50 * ($i % 6 + 1) : 32 * ($i % 5 + 1);

            ?>
            <tr<?= $i % 2 == 0 ? '' : ' bgcolor="#fff"'; ?>>
              <td><?= $i ?>.</td>
              <td><?= $cat['title'] ?></td>
              <td class="right_t">
                <?= $count_found == '<span class="found">0</span>' ? 0 : '<a href="?category=' . $id . '&type_order=3" class="found">' . $count_found . '</a>'; ?>
              </td>
              <td class="right_t">
                <?= $count_lost == '<span class="lost ">0</span>' ? 0 : '<a href="?category=' . $id . '&type_order=2" class="lost ">' . $count_lost . '</a>'; ?>

              </td>
            </tr>
            <?
            $i++;
          }
          ?> </table>
      </div>
    <?
    }
    ?>
    <div style="margin-top: 20px;" class="banner-image">
      <a href="http://<?=SERVICE_NEPOTERAYKA?>.<?=Yii::app()->params['domain']?>/"><img src="/images/banner.png"></a>
    </div>
    <div style="margin-top: 20px;" class="banner-image">
      <?php $this->widget('application.components.widgets.BRotateWidget', array('position' => 'position_top')); ?>
    </div>
  </div>
</div>