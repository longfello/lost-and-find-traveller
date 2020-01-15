<?php
  /* @var $this CityAcWidget */
  $el_id = 'CityAc-'.uniqid();
  $pos   = ($this->lat && $this->lng)?($this->lat.','.$this->lng):'';
  $this->htmlOptions['placeholder'] = isset($this->htmlOptions['placeholder'])?$this->htmlOptions['placeholder']:Yii::t('widget-address-ac', 'Населенный пункт');
?>

<div class="city-ac-widget autocomplete" id="<?=$el_id?>">
  <input type="text" class="validate-el CityAc inputfit <?=$this->class?>" value="<?=$this->name?>" data-id="<?=$this->id?>" data-lng="<?=$this->lng?>" data-lat="<?=$this->lat?>" data-pos="<?=$pos?>" data-slug="<?=$this->slug?>" data-jarea='<?=$area?>' <?php
    foreach($this->htmlOptions as $key => $value) {
      echo($key.'="'.$value.'" ');
    }
  ?> />
</div>

<?php
Yii::app()->clientScript->registerScript('cityAc-'.$el_id, "
  $(document).ready(function(){
    var item_selected = false;
    $('#{$el_id} input').keydown(function (e) {
      if (e.which == 13 && $('.pac-container:visible').length) return false;
    });
    $('#{$el_id} input').data('area', $.parseJSON('{$area}'));
    $('#{$el_id} input').autocomplete({
      source: '/api/cityAutocomplete',
      minLength: 2,
      change: function (event, ui) {
        if((ui.item == null || ui.item == undefined) && !item_selected){
          item_selected = false;
          var bg = $(event.target).css('background-color');
          $(event.target).val('').css('background-color', '#FFBFBF');
          setTimeout(function(){
            $(event.target).css('background-color', bg);
          }, 300);
        }
      },
      select: function( event, ui ) {
        item_selected = true;
        var el = $('#{$el_id} input');
        $(el).data(ui.item).trigger('selected');
        $(this).data('onAC', true);
        $(el).blur();
        if ($(el).data('child')){
          var coord = $(el).data('area');

          var bounds = new google.maps.LatLngBounds(
              new google.maps.LatLng(Math.min(coord.start.lat, coord.end.lat), Math.min(coord.start.lng, coord.end.lng)),
              new google.maps.LatLng(Math.max(coord.start.lat, coord.end.lat), Math.max(coord.start.lng, coord.end.lng)));

          var sel = $(el).data('child');
          if (sel instanceof Array) {
            $(sel).each(function(){
              $(this).trigger('setBounds', {bounds: bounds});
            });
          } else {
            $(sel).trigger('setBounds', {bounds: bounds});
          }
        }
      }
    });
    $('#{$el_id} input').on('focus', function(){
      $(this).data('onAC', false);
    });
    // $('#{$el_id} input').data('autocomplete');
    $('#{$el_id} input')._renderItem = function( ul, item ) {
      return $('<li></li>')
          .data('item.autocomplete', item )
          .append('<a>' + item.label + '</a>' )
          .appendTo( ul );
    };
  });
", CClientScript::POS_END);