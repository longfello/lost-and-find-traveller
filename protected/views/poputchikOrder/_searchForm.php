<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places"></script>
<?php
/* @var $search_info PoputchikSearchSQLHelper */
Yii::app()->clientScript->registerScriptFile('/js/g_maps.js', CClientScript::POS_HEAD);
?>

<div id="poput-orders-filter" class="container poput-orders-filter container-clearfix form ext top_banner_position">
 <div class="row">
  <form method="post">
      <div class="row">
        <div class="type-order col-md-12 col-sm-12 col-xs-24">
            <div class="col-md-24 col-sm-24 col-xs-24">
              <div class="r-item first">
                <?php echo CHtml::radioButton('type_order', $search_info->order == 1, array('value' => 1, 'id' => 'type-order_1'));
                echo CHtml::label(Yii::t('poputchik', 'Найти водителя'), 'type-order_1'); ?>
              </div>
              <div class="r-item">
                <?php echo CHtml::radioButton('type_order', $search_info->order == 2, array('value' => 2, 'id' => 'type-order_2'));
                echo CHtml::label(Yii::t('poputchik', 'Найти пассажира'), 'type-order_2'); ?>
              </div>
            </div>
          <div class="block-clearfix"></div>
        </div>

        <div class="type-route col-md-12 col-sm-12 col-xs-24">
            <div class="col-md-24 col-sm-24 col-xs-24">
                <div class="r-item first">
                    <?php echo CHtml::radioButton('type_route', $search_info->route == 1, array('value' => 1, 'id' => 'type-route_1'));
                    echo CHtml::label(Yii::t('poputchik', 'По городу'), 'type-route_1'); ?>
                </div>
                <div class="r-item last">
                    <?php echo CHtml::radioButton('type_route', $search_info->route == 2, array('value' => 2, 'id' => 'type-route_2'));
                    echo CHtml::label(Yii::t('poputchik', 'Межгород'), 'type-route_2'); ?>
                </div>

            </div>
          <div class="block-clearfix"></div>
        </div>
      </div>


        <div class="dates-row route-1">
            <div class="sity-row row">
            <div id="start_settlement-wrapper">
                <?= CHtml::textField('settlement_name_g', $search_info->from->name, array('size' => 50, 'maxlength' => 255, 'id' => 'start_name', 'class' => 'g_search_au', 'placeholder' => 'ГОРОД',)); ?>
                <?php echo CHtml::hiddenField('start_settlement', $search_info->from->id, array('data-start-id' => 1)); ?>
                <?php echo CHtml::hiddenField('settlement_name_1', $search_info->from->full_name, array('data-start-name' => 1)); ?>
                <a href='#' class="clear-input glyphicon glyphicon-remove"></a>
                <span class="example">Например: <a href="#">Москва</a>, <a href="#">Киров</a></span>
            </div>

              <div class="for-route-2 route-direction"><a href="#"><img src="/images/arrow-search.png" alt=""></a></div>

              <div id="end_settlement-wrapper" class="row for-route-2">
                <?php echo CHtml::textField('settlement_name_2', $search_info->to->name, array('size' => 100, 'maxlength' => 255, 'id' => 'end_name', 'class' => 'g_search_au', 'placeholder' => 'ГОРОД',)); ?>
                <?php echo CHtml::hiddenField('end_settlement', $search_info->to->id, array('data-end-id' => 1)); ?>
                <a href='#' class="clear-input glyphicon glyphicon-remove"></a>
                <span class="example">Например: <a href="#">Киев</a>, <a href="#">Днепропетрвоск</a></span>
              </div>
            </div>
            <div class="row">
                <div class="f-block1">
                    <input size="10" maxlength="10" defaultvalue="Улица" id="street_from" type="text" value="" name="street_from" class="default">
                </div>
                <div class="f-block2 route-street" style="text-align:center;">
                    <a href="#"><img src="/images/arrow-search.png" alt=""></a>
                    </div>
                <div class="f-block3">
                    <input size="10" maxlength="10" defaultvalue="Улица" id="street_to" type="text" value="" name="street_to" class="default">
                </div>
            </div>

            <a class="btn btn-primary btn-more collapsed" data-toggle="collapse" href="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
              <span class="collapsed">Свернуть</span>
              <span class="collapsed in">Еще</span>
            </a>
            <div class="clearfix"></div>
            <div class="collapse" id="collapseExample">
                <div class="well">
                    <div id="date_from-row" class="row col-md-12 col-sm-12">
                        <?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                            'name' => 'date_from',
                            'language' => Yii::app()->language,
                            'value' => $search_info->when?date('d.m.Y', $search_info->when):false,
                            'options'=>array(
                                'numberOfMonths'=>2,
                                'showButtonPanel'=>true,
                            ),                            'htmlOptions' => array(
                                'size' => '10', // textField size
                                'maxlength' => '10', // textField maxlength
                                'defaultValue' => Yii::t('poputchik', "Когда"),
                            ),
                        ));
                        ?>
                        <p class="departureDate">Дата отправления</p>
                        <select class="customSelect">
                            <option>Эконом</option>
                            <option>Бизнес</option>
                            <option>Первый</option>
                        </select>
                        <p class="trip">Класс поездки</p>
                    </div>

                    <div class="right-column col-md-12 col-sm-12">
                        <div>
                            <a id="man-1" href="#" name="Man" rel="" class="man radio active"></a>
                            <a id="man-2" href="#" name="Man" rel="" class="man radio"></a>
                            <a id="man-3" href="#" name="Man" rel="" class="man radio"></a>
                            <a id="man-4" href="#" name="Man" rel="" class="man radio"></a>
                            <input type="radio" style="display: none;" name="Man" value="" checked="checked">
                            <input type="radio" style="display: none;" name="Man" value="">
                            <input type="radio" style="display: none;" name="Man" value="">
                            <p>Количество пассажиров</p>
                        </div>
                    </div>
                    </div>
                </div>
            </div>

        </div>
          <div id="search-button">
            <button type="submit"><i class="iSearch"></i>&nbsp;&nbsp;<?=Yii::t('common', 'Найти')?></button>
          </div>
            <a id="clear-button" href="<?= Yii::app()->homeUrl ?>" class="ajax">
                <?= Yii::t('common', 'Сбросить') ?>
            </a>
        <div class="clear">
       <div class="header-fix">
           <div class="container">
               <div class="type-order col-md-12 col-sm-12 col-xs-24">
                   <div class="col-md-24 col-sm-24 col-xs-24">
                       <div class="r-item first">
                           <input value="1" id="type-order_1" type="radio" name="type_order"><label for="type-order_1">Найти водителя</label>              </div>
                       <div class="r-item">
                           <input value="2" id="type-order_2" type="radio" name="type_order"><label for="type-order_2">Найти пассажира</label>              </div>
                   </div>
                   <div class="block-clearfix"></div>
               </div>
               <div id="search-button">
                   <button type="submit"><i class="iSearch"></i>&nbsp;&nbsp;Новый поиск</button>
               </div>
       </div>
       </div>
        </div>



  </form>
 </div>
</div>
<div class="clear"></div>

<script type="text/javascript">
  $(document).ready(function(){
    $('input.g_search_au').inputfit({minSize : 12});
    $('.clear-input').on('click', function(e){
      e.preventDefault();
      $(this).siblings('input').val('');
    });
    $( window ).scroll(function(){
      rearrangeFloatMenu();
    });
    $('#street_from, #street_to').on('blur', function(){
      $('.route-street img').addClass('active');
      setTimeout(function(){
        $('.route-street img').removeClass('active');
      }, 1000);
    });
  });

  function rearrangeFloatMenu(){
    var h = $('#search-button').offset().top + $('#search-button').height();
    var t = $(document).scrollTop();
    if (t-h > 0) {
      $('.header-fix').addClass('fixed');
    } else {
      $('.header-fix').removeClass('fixed');
    }
  }
</script>