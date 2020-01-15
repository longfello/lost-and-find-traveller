<script type="text/javascript" src="http://userapi.com/js/api/openapi.js?49"></script>
<script type="text/javascript">
  VK.init({apiId: 4282513, onlyWidgets: true});
</script>
<div id="fb-root"></div>
<script>(function (d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s);
    js.id = id;
    js.src = "//connect.facebook.net/ru_RU/all.js#xfbml=1";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));


</script>

<?php
/* @var $this LostFoundController */
/* @var $dataProvider CActiveDataProvider */
$cs = Yii::app()->clientScript;

$cs->registerCssFile('/css/lostservice.css');
$cs->registerScriptFile('/js/service.js', CClientScript::POS_HEAD);
$cs->registerScriptFile('/js/lostservice.js', CClientScript::POS_HEAD);

global $days_of_week;
$language = Yii::app()->language;
$text_not_results = '<div class="not-results">' . Yii::t('lostfound', "По вашему запросу заявки не найдены.") . '</div>';

if ($pages) {
  $current_page = $_GET['page'];
  if (!$current_page) {
    $current_page = 1;
  }
  $start_page = $current_page - 4;
  if ($start_page < 1) {
    $start_page = 1;
  }
  $end_page = $current_page + 5;
  if ($end_page > $pages) {
    $end_page = $pages;
  } else if ($end_page < 10) {
    $end_page = 10;
  }
}
?>



<?php if (isset($_GET['ajax'])): ?>

  <?php if ($orders): ?>
    <?php foreach ($orders as $order): ?>
      <?php $this->renderPartial('_order', array('order' => $order)); ?>
    <?php endforeach; ?>
  <?php else: ?>
    <?= $text_not_results ?>
  <?php endif; ?>

<?php else: ?>
  <div class="wraper-for-marging">
    <div class="left_column">
      <div class="header">
        <div id="anchor"></div>

      </div>

      <div class="search-results">
        <div id="lf-orders-filter" class="lf-orders-filter container-clearfix form ext top_banner_position">
          <form action="/lostFound/index" method="post">
            <div class="type-order">
              <div class="r-item first">
                <?php echo CHtml::radioButton('type_order', ($type_order == 1), array('value' => 1, 'id' => 'type-order_1'));
                echo CHtml::label(Yii::t('poputchik', 'Ищу всё'), 'type-order_1'); ?>
              </div>
              <div class="r-item ">
                <?php echo CHtml::radioButton('type_order', ($type_order == 2), array('value' => 2, 'id' => 'type-order_2'));
                echo CHtml::label(Yii::t('poputchik', 'Потери'), 'type-order_2'); ?>
              </div>
              <div class="r-item">
                <?php echo CHtml::radioButton('type_order', ($type_order == 3), array('value' => 3, 'id' => 'type-order_3'));
                echo CHtml::label(Yii::t('poputchik', 'Находки'), 'type-order_3'); ?>
              </div>

              <div class="block-clearfix"></div>
            </div>
            <div class="block-clearfix" style="height: 10px;"></div>
            <div id="start_settlement-wrapper" class="row">
              <div class="inner"><?php
                $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
                    'name' => 'settlement_name',
                    'source' => 'js:function(request, response) {
                      $.getJSON("' . $this->createUrl('settlements/autocomplete') . '", {
                        term:       request.term.split(/,s*/).pop(),
                        region_id:  $("#region_1").val()
                      }, response);

                    }', // additional javascript options for the autocomplete plugin
                    'options' => array(
                      'minLength' => '3',
                      'showAnim' => 'fold',
                      'delay' => 500,
                      'select' => 'js: function(event, ui) {
                        this.value = ui.item.value;
                        // записываем полученный id в скрытое поле
                        $("#settlement_id").val(ui.item.id);
                        return false;
                    }',
                    'response' => 'js: autoCompleteResponse',),
                    'htmlOptions' => array('size' => '50', 'placeholder' => Yii::t('poputchik', "Город")), 'value' => $city_name,));
                ?>
                <?php echo CHtml::hiddenField('settlement_id', $city_id); ?>
                <div class="errorMessage" style="display:none;"></div>
              </div>
              <div class="block-clearfix"></div>
            </div>
            <div id="settlement-arrow" class="row"></div>
            <?php echo CHtml::textField('category', $category, array('hidden' => TRUE, 'id' => 'cat_hidden')); ?>

            <div id="c_cat" style="float: left;" class=""><a
                  href='#'><? (!$category) ? print('Выбрать категорию') : print(Category::model()->findByPk($category)->title); ?></a>
            </div>
            <div class="del-icon"
                 style="<? (!$category) ? print('display:none;') : print('display:inline-block;'); ?> height: 20px;  margin-left: 10px; position: absolute;"
                 onclick="$('.del-icon').hide(); $('#cat_hidden').attr('value', '' );$('#c_cat a').html('Выбрать категорию'); ">
              <a style="display: inline-block; position:static; margin:0;" class="del"></a>

              <div class="del-label" style="left: -2px;position: relative;top: -7px;"
                   id="<?= isset($order->id_order) ? $order->id_order : NULL ?>">Очистить
              </div>
            </div>


            <div id="cat" class="overlay"></div>
            <div class="my_popup">
              <table class="category capitalize">
                <?php
                $count = ceil(count($categories) / 3);

                for ($i = 0; $i < $count; $i++) {
                  echo "<tr>
          <td><a href='#' class='cat_link' rel='" . @$categories[$i]['id_cat'] . "'>" . @$categories[$i]['title'] . "</a></td>
          <td><a href='#' class='cat_link' rel='" . @$categories[$i + $count]['id_cat'] . "'>" . @$categories[$i + $count]['title'] . "</a></td>
          <td><a href='#' class='cat_link' rel='" . @$categories[$i + 2 * $count]['id_cat'] . "'>" . @$categories[$i + 2 * $count]['title'] . "</a></td>
          </tr>";
                }
                ?></table>
            </div>
            <div class="block-clearfix"></div>
              <div class="clear"></div>
              <a id="clear-button" href="/<?= $language ?>/lostFound/index" class="ajax">
                  <?= Yii::t('common', 'Сбросить') ?>
              </a>
            <div id="search-button">
              <?php echo CHtml::submitButton(Yii::t('common', 'Поиск')); ?>
            </div>
              <div class="clear"></div>
          </form>
        </div>
        <div class="lf-orders container-clearfix">
          <?php if ($orders): ?>
            <?php foreach ($orders as $order): ?>
              <?php $this->renderPartial('_order', array('order' => $order)); ?>
            <?php endforeach; ?>
          <?php else: ?>
            <?= $text_not_results ?>
          <?php endif; ?>
          <div id="bottom_banner_position"></div>
          <?php if ($pages): ?>
            <div class="pager">
              <div class="child">
                <div class="dop-block">
                  <?php if ($current_page > 1): ?><a class="prev" rel="<?= ($current_page - 1) ?>"
                                                     href="?search=<?= $search ?>&page=<?= ($current_page - 1) ?>"><?= Yii::t('general', 'Предыдущая') ?></a><?php endif; ?>
                  <?php for ($i = $start_page; $i <= $end_page; $i++): ?>
                    <a class="page<?php if ($i == $current_page) {
                      print ' current';
                    } ?>" rel="<?= $i ?>" href="?search=<?= $search ?>&page=<?= $i ?>"><?= $i ?></a>
                  <?php endfor; ?>
                  <?php if ($current_page < $pages): ?><a class="next" rel="<?= ($current_page + 1) ?>"
                                                          href="?search=<?= $search ?>&page=<?= ($current_page + 1) ?>"><?= Yii::t('general', 'Следующая') ?></a><?php endif; ?>
                </div>
              </div>
            </div>
          <?php endif; ?>

        </div>
      </div>

    </div>
    <div class="block-clearfix"></div>
  </div>
  <? /*<div id="fixed-menu" >
        <div class="added" > 	</div>
        <a class="left-link" href="/lostFound/create?type_order=1">Добавить находку</a>
        <a class="up-link" href="#anchor"></a>
        <a class="right-link" href="/lostFound/create?type_order=0">Сообщить о потере</a>	
    </div>
*/
  ?>

  <div id="sendmail" class="overlay"></div>
  <div class="my_popup">

    <div class="form">
      <?php
      $ContactForm = new ContactForm;


      $form = $this->beginWidget('CActiveForm', array('id' => 'contact-form', 'enableClientValidation' => TRUE, 'action' => CHtml::normalizeUrl(Yii::app()->request->baseUrl . '/poputchikOrder/sendemail'), 'clientOptions' => array('validateOnSubmit' => TRUE,),));
      ?>
      <?php echo CHtml::hiddenField('user'); ?>
      <div class="title">Письмо автору</div>

      <div class='left'>
        <?php echo CHtml::label('Ваше имя', 'text'); ?>
        <?php echo CHtml::textField('name'); ?>

        <?php echo CHtml::label('Ваш E-mail', 'user'); ?>
        <?php echo CHtml::emailField('sender'); ?>
      </div>
      <div class='right'>
        <?php echo CHtml::label('Текст', 'text'); ?>
        <?php echo CHtml::textArea('text', '', array('rows' => 8, 'cols' => 50, 'style' => 'resize:none')); ?>
      </div>
      <div class="block-clearfix"></div>
      <div style="width:100%;height: 60px;position: relative;text-align: right;">
        <?php $this->widget('CCaptcha'); ?>
        <?php echo CHtml::textField('captcha'); ?>
        <?php echo CHtml::button('Отправить', array('id' => 'button')); ?>
      </div>


      <div id="contact_message"></div>
      <?php $this->endWidget(); ?>

    </div>
    <!-- form -->

  </div>


  <?php
  Yii::app()->clientScript->registerScript('autocomplete', "
  jQuery('#settlement_name').focus(function(){
            $(this).autocomplete('search', $(this).val());
  }).data('autocomplete')._renderItem = function( ul, item ) {
    return $('<li></li>')
      .data('item.autocomplete', item)
      .append( '<a>' + item.label + '<br><span class=\"desc\">' + item.desc + '</span></a>' )
      .appendTo(ul);
  };
  ", CClientScript::POS_READY);
  ?>
<?php endif; ?>