<div class="row route-type-chooser-wrapper">
  <ul class="route-type-chooser poput-radio-button clearfix">
    <li class="route-type-intercity">
      <input type="radio" value="intercity" checked="checked" name="route-type" id="i-route-type-intercity">
      <label for="i-route-type-intercity"><?=Yii::t('poputchik', 'Межгород');?></label>
    </li>
    <li class="route-type-city">
      <input type="radio" value="city" name="route-type" id="i-route-type-city">
      <label for="i-route-type-city"><?=Yii::t('poputchik', 'По городу');?></label>
    </li>
  </ul>
</div>

<?php

Yii::app()->clientScript->registerScript('route-type-chooser', "
  var old_RTC_to_value = {
    'lat' : '',
    'lng' : '',
    'pos' : '',
    'val' : '',
    'area' : '',
  };
  $('div.route-type').hide();
  $('div.route-type-intercity').show();
  $('.route-type-chooser-wrapper .route-type-chooser input').on('change', function(e){
    $(this).parents('.itw-multistep-form').removeClass('route-type-city route-type-intercity').addClass('route-type-'+$(this).val());
    if ($(this).val() == 'city') {
      old_RTC_to_value.lat  = $('.route_end').data('lat');
      old_RTC_to_value.lng  = $('.route_end').data('lng');
      old_RTC_to_value.pos  = $('.route_end').data('pos');
      old_RTC_to_value.area = $('.route_end').data('area');
      old_RTC_to_value.val  = $('.route_end').val();

      $('.route_end').data('lat', $('.route_start').data('lat'));
      $('.route_end').data('lng', $('.route_start').data('lng'));
      $('.route_end').data('pos', $('.route_start').data('pos'));
      $('.route_end').data('area', $('.route_start').data('area'));
      $('.route_end').val($('.route_start').val());

      $('#street_from').val('');
      $('#street_to').val('');

      var coord = $('.route_end').data('area');

      var bounds = new google.maps.LatLngBounds(
          new google.maps.LatLng(Math.min(coord.start.lat, coord.end.lat), Math.min(coord.start.lng, coord.end.lng)),
          new google.maps.LatLng(Math.max(coord.start.lat, coord.end.lat), Math.max(coord.start.lng, coord.end.lng)));

      var sel = $('.route_end').data('child');
      if (sel instanceof Array) {
        $(sel).each(function(){
          $(this).trigger('setBounds', {bounds: bounds});
        });
      } else {
        $(sel).trigger('setBounds', {bounds: bounds});
      }
    } else {
      $('.route_end').data('lat', old_RTC_to_value.lat);
      $('.route_end').data('lng', old_RTC_to_value.lng);
      $('.route_end').data('pos', old_RTC_to_value.pos);
      $('.route_end').data('area', old_RTC_to_value.area);
      $('.route_end').val(old_RTC_to_value.val);
    }
  });
", CClientScript::POS_READY);