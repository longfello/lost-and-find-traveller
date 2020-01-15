<?php
/* @var $this CityAcWidget */
  $el_no = uniqid();
  $el_id = isset($this->htmlOptions['id'])?$this->htmlOptions['id']:'AddressAc-'.$el_no;
  $this->htmlOptions['id'] = $el_id;
  $this->htmlOptions['placeholder'] = isset($this->htmlOptions['placeholder'])?$this->htmlOptions['placeholder']:Yii::t('widget-address-ac', 'Адрес');
?>


<input type="text" class="address-ac-widget inputfit autocomplete <?=$this->class?>" <?php
foreach($this->htmlOptions as $key => $value) {
  echo($key.'="'.$value.'" ');
}
?> />

<script type="text/javascript">
  $(window).ready(function(){
    var options = {
      types: ['geocode']
    };

    var input = document.getElementById('<?=$el_id?>');

    $(input).keydown(function (e) {
      if (e.which == 13 && $('.pac-container:visible').length) return false;
    });
    $(input).blur(function (e) {
      return false;
    });
    <?php if ($this->parent) { ?>
    $('.<?=$this->parent?>').data('child', '#<?=$el_id?>');
      $(input).on('setBounds', function(a,b){
        autocomplete_<?=$el_no?>.setBounds(b.bounds);
      });
      var area = $('.<?=$this->parent?>').data('area');
      area = area?area: $('.<?=$this->parent?>').data('jarea');
      var defaultBounds = new google.maps.LatLngBounds(
          new google.maps.LatLng(area.start.lat, area.start.lng),
          new google.maps.LatLng(area.end.lat, area.end.lng)
      );
      options.bounds = defaultBounds;
    <?php } ?>


    var autocomplete_<?=$el_no?> = new google.maps.places.Autocomplete(input, options);
    google.maps.event.addListener(autocomplete_<?=$el_no?>, 'place_changed', function() {
      var place = autocomplete_<?=$el_no?>.getPlace();

      // console.log(place);

      if (!place.geometry || !(place.name || place.short_name)) {
        $(input).val('');
        return;
      }

      setTimeout(function(){
        $(input).val(place.name || place.short_name);
      }, 500);

      <?php if ($this->parent) { ?>
      $('.<?=$this->parent?>').data({
        lat: place.geometry.location.lat(),
        lng: place.geometry.location.lng(),
        pos: place.geometry.location.lat()+','+place.geometry.location.lng()
      });
        $('.<?=$this->parent?>').trigger('selected');
        // router.calcRoute();
      <?php } else {?>
        $(input).data({
          lat: place.geometry.location.lat(),
          lng: place.geometry.location.lng(),
          pos: place.geometry.location.lat()+','+place.geometry.location.lng()
        });
      <?php } ?>
    });

  });
</script>