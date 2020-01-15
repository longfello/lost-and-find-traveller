<?php  ?>

<style>
  input.autocomplete, input.CityAc  {width:500px;}
</style>

<div class='route-add-form ac-wrapper'>
  <div class="source">
    <?php $this->widget('application.components.widgets.CityAcWidget', array('class'=>'route_start')); ?>
    <?php $this->widget('application.components.widgets.AddressAcWidget', array('class'=>'route_start_address', 'parent' => 'route_start')); ?>
    <input class="date-time-picker" type="text" name="route_start_date" value="" placeholder="Дата и время отправления"/>
  </div>
  <div class="destination">
    <?php $this->widget('application.components.widgets.CityAcWidget', array('empty'=>true, 'class'=>'route_end')); ?>
    <?php $this->widget('application.components.widgets.AddressAcWidget', array('class'=>'route_end_address', 'parent' => 'route_end')); ?>
    <input class="date-time-picker" type="text" name="route_end_date" value="" placeholder="Дата и время завершения действия объявления" />
  </div>
	<label for="avoidHighways"><input type="checkbox" id="avoidHighways" checked>Предпочитать главные дороги</label>
	<label for="optimizeWaypoints"><input type="checkbox" id="optimizeWaypoints" checked>Оптимизировать маршрут</label>

  <div id="total"></div>
  <div id="map_canvas" style="width:70%; height:600px; float:left;"></div>
  <div id="directionsPanel" style="width:30%;float:left;">
    <div class="routes-wrapper">
      <h3>Возможные маршруты:</h3>
      <div class="routes"></div>
    </div>
    <div class="points-wrapper">
      <h3>Промежуточные точки:</h3>
      <ul class="points"></ul>
      <?php $this->widget('application.components.widgets.AddressAcWidget', array('class'=>'route_waypoint')); ?>
      <a href="#" class="btn btn-default btn-add-point"><i class="glyphicon glyphicon-plus"></i> Добавить</a>
    </div>
  </div>
  <div class="clear"></div>

  <div class="route-periodicity">
    <h3>Периодичность</h3>
    <input type="radio" name="periodicity" id="periodicity-once" value="once" checked><label for="periodicity-once">разово</label>
    <input type="radio" name="periodicity" id="periodicity-week" value="week"><label for="periodicity-week">еженедельно</label>
    <input type="radio" name="periodicity" id="periodicity-month" value="month"><label for="periodicity-month">ежемесячно</label>
    <div class="periodicity-wrapper periodicity-once-wrapper"></div>
    <div class="periodicity-wrapper periodicity-week-wrapper">
      <input type="checkbox" id="periodicity-week-monday"    name="periodicity-week[]" value="monday"><label for="periodicity-week-monday">   ПН</label>
      <input type="checkbox" id="periodicity-week-tuesday"   name="periodicity-week[]" value="tuesday"><label for="periodicity-week-tuesday">  ВТ</label>
      <input type="checkbox" id="periodicity-week-wednesday" name="periodicity-week[]" value="wednesday"><label for="periodicity-week-wednesday">СР</label>
      <input type="checkbox" id="periodicity-week-thursday"  name="periodicity-week[]" value="thursday"><label for="periodicity-week-thursday"> ЧТ</label>
      <input type="checkbox" id="periodicity-week-friday"    name="periodicity-week[]" value="friday"><label for="periodicity-week-friday">   ПТ</label>
      <input type="checkbox" id="periodicity-week-saturday"  name="periodicity-week[]" value="saturday"><label for="periodicity-week-saturday"> СБ</label>
      <input type="checkbox" id="periodicity-week-sunday"    name="periodicity-week[]" value="sunday"><label for="periodicity-week-sunday">   ВС</label>
    </div>
    <div class="periodicity-wrapper periodicity-month-wrapper">
      <?php for ($i=1; $i<32; $i++){ ?>
        <input type="checkbox" id="periodicity-month-<?=$i?>" name="periodicity-month" value="<?=$i?>"><label for="periodicity-month-<?=$i?>"><?=$i?></label>
      <?php } ?>
    </div>
  </div>

  <div class="route-auto">
    <?php $this->widget('application.components.widgets.AutoChooserWidget', array('class'=>'route_car')); ?>
  </div>

  <div class="route-ai">
    <label for="route-ai-name">Имя: <input name="name" id="route-ai-name" value="<?=Yii::app()->getUser()->model()->profile->first_name?>"></label>
    <label for="route-ai-phone">Телефон: <input name="phone" id="route-ai-phone" value="<?=Yii::app()->getUser()->model()->profile->second_phone?>"></label>
    <label for="route-ai-email">Email: <input name="email" id="route-ai-email" value="<?=Yii::app()->getUser()->model()->email?>"></label>
    <label for="route-ai-comment">Комментарий: <textarea name="comment" id="route-ai-comment"></textarea></label>
  </div>

  <div class="route-submit">
    <button type="button" class="route-submit-btn">Создать</button>
  </div>

</div>
<a href="#" class="test">Get Test Full Path</a>
<div class="log"></div>

<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?libraries=places&sensor=true&region=<?=strtoupper(Yii::app()->language)?>&language=<?=Yii::app()->language?>"></script>
<script type="text/javascript">
  $(window).ready(function(){
    router.init(<?=Yii::app()->location->city->longitude?>, <?=Yii::app()->location->city->latitude?>);
  });
</script>