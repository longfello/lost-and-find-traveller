var driver_form = {
  'maxAmount'    : 2000000,
  'stdMaxAmount' : 1500,
  'init': function(){
    $('input#amount').inputfit({minSize : 12});

    $('#pricePlaceQuantity').on('change', function(){
      $('#slider-for-place-quantity').slider('option', 'value', $(this).val());
    });

    $('#amount').on('input', function(){
      var val = parseInt($(this).val()) || 0;
      val = val<driver_form.maxAmount?val:driver_form.maxAmount;
      $(this).val(val);
      var crntMaxAmount = $('#slider-for-price').slider('option', 'max');
      if (val > crntMaxAmount) {
        $('#slider-for-price').slider('option', 'max', val);
        $('.firstRangeSlider.amount label.max').html(val);
      } else {
        if (val > driver_form.stdMaxAmount) {
          $('#slider-for-price').slider('option', 'max', val);
          $('.firstRangeSlider.amount label.max').html(val);
        } else {
          $('#slider-for-price').slider('option', 'max', driver_form.stdMaxAmount);
          $('.firstRangeSlider.amount label.max').html(driver_form.stdMaxAmount);
        }
      }
      $('#slider-for-price').slider('option', 'value', val);
    });
    $('#slider-for-price').slider({
      range: 'min',
      value: 1,
      min: 1,
      max: driver_form.stdMaxAmount,
      slide: function (event, ui) {
        $('#amount').val(ui.value);
      }
    });
    $('#amount').val($('#slider-for-price').slider('value'));

    $('#slider-for-place-quantity').slider({
      range: 'min',
      value: 1,
      min: 1,
      max: 4,
      slide: function (event, ui) {
        $('#pricePlaceQuantity').val(ui.value);
        $('#pricePlaceQuantity').selectbox('detach').selectbox('attach');
      }
    });
    $('#pricePlaceQuantity').val($('#slider-for-place-quantity').slider('value'));
    $('form.itw-multistep-form').on('submit.driver_form', function(e){
      e.preventDefault();
      var data = driver_form.get_data();
      if (driver_form.validate(data)) {
        data.action = 'create';
        $.post(document.location.href, data, function(res){
          if (res.result == 'ok') {
            document.location.href = '/advert-sucessfully-added';
          } else {
            $('.validate-el-overall').html(res.messages.overall).addClass('error');
            $('.itw-multistep-form').itwForms('goto', 1);
          }
        }, 'json');
      }
      return false;
    });
  },
  'get_data': function(){
    var data = {
      'short-path' : driver_form.get_short_path(),
      'path'       : driver_form.get_path(),
      'start-location' : {
        'id': $(router.els.start).data('id'),
        'lng': $(router.els.start).data('lng'),
        'lat': $(router.els.start).data('lat')
      },
      'periodicity-type': $('.periodicity-chooser input[name="periodicity"]:checked').val(),
      'periodicity-weekly-days': $('.periodicity-params-weekly-days input:checked').map(function(){ return $(this).val(); }).get(),
      'startAt' : {
        'date' : $('#startdate').val(),
        'time'  : {
          'H' : $('.start-time-hour').val(),
          'm' : $('.start-time-min').val()
        }
      },
      'end-location' : {
        'id': $(router.els.end).data('id'),
        'lng': $(router.els.end).data('lng'),
        'lat': $(router.els.end).data('lat')
      },
      'returnAt' : {
        'date'        : $('#returndate').val(),
        'placeholder' : $('#returndate').attr('defaultvalue'),
        'time'        : {
          'H' : $('.return-time-hour').val(),
          'm' : $('.return-time-min').val()
        }
      },
      'cost'       : {
        'price'    : $('#amount').val(),
        'forPlace' : $('#price-for-place-quantity').val()
      },
      'places'     : $('#pricePlaceQuantity').val(),
      'auto-model' : $('.auto-model').val(),
      'additional' : {
        'luggage'     : $('#luggage').val(),
        'deviation'   : $('#deviation').val(),
        'punctuality' : $('#punctuality').val()
      },
      'available_until' : {
        'date'     : $('#validdate').val(),
        'time'     : {
          'H' : $('.valid-time-hour').val(),
          'm' : $('.valid-time-min').val()
        }
      },
      'i_approve'  : $('#i_approve').is(':checked'),
      'name'       : $('#user_name').val(),
      'phone'      : $('#user_phone').val(),
      'email'      : $('#user_email').val(),
      'comment'    : $('#advert_comment').val(),
      'avatar'     : $('#user-avatar').val(),
      'route-type' : $('.route-type-chooser input[name="route-type"]:checked').val(),
      'start-address' : $('.route_start_address').val(),
      'end-address'   : $('.route_end_address').val()
    };

    return data;
  },
  'get_short_path': function() {
    data = [];
    data.push({
      lat: $(router.els.start).data('lat'),
      lng: $(router.els.start).data('lng')
    });
    for(var i in router.wpts){
      data.push({
        lat: router.wpts[i].lat(),
        lng: router.wpts[i].lng()
      });
    };
    data.push({
      lat: $(router.els.end).data('lat'),
      lng: $(router.els.end).data('lng')
    });
    return data;
  },
  'get_path': function(){
    var data = [];
    if (router.directionsDisplay.directions) {
      for(var i in router.directionsDisplay.directions.routes[router.directionsDisplay.routeIndex].overview_path) {
        data.push({
          lat : router.directionsDisplay.directions.routes[router.directionsDisplay.routeIndex].overview_path[i].lng(),
          lng : router.directionsDisplay.directions.routes[router.directionsDisplay.routeIndex].overview_path[i].lat()
        });
      }
    }
    return data;
  },
  'validate': function(data){
    var result = driver_form_validator.run(data);
    if (!result) {
      var step = 1+$('.itw-multistep-form .step').index($('.itw-multistep-form .error:first').parents('.step'));
      $('.itw-multistep-form').itwForms('goto', step);
    }
    return result;
  }
}

var driver_form_validator = {
  run: function(data){
    $('.itw-multistep-form .validate-error').html('').hide();
    $('.itw-multistep-form .validate-el').removeClass('error');

    var ok = true;
    for(var i in data) {
      var method_name = 'validate_'+i;
      if (driver_form_validator[method_name] instanceof Function) {
        // console.log('Validating: '+i+': ');
        // console.log(data[i]);
        ok = driver_form_validator[method_name](data[i]) && ok;
      } else {
        // console.log('skipping: '+i);
      }
    }
    return ok;
  },
  'validate_email': function(data){
    var re = /^(?:[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*|"(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])*")@(?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?|\[(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|[a-z0-9-]*[a-z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\])$/i;
    // email или пусто
    if (re.test(data) || data.length == 0) return true;
    driver_form_validator.setError('email');
    return false;
  },
  'validate_path': function(data){
    if (data.length > 0) return true;
    driver_form_validator.setError('path');
    return false;
  },
  'validate_start-location': function(data){
    if (data.id) return true;
    driver_form_validator.setError('start-location');
    return false;
  },
  'validate_end-location': function(data){
    if (data.id) return true;
    driver_form_validator.setError('end-location');
    return false;
  },
  'validate_available_until': function(data){
    if(data.time.H >=0 && data.time.H < 24) {
      if(data.time.m >=0 && data.time.m < 60) {
        var val = parseDMY(data.date);
        if (val.valid()) return true;
      }
    }
    driver_form_validator.setError('available_until');
    return false;
  },
  'validate_startAt': function(data){
    if((data.time.H >=0) && (data.time.H < 24)) {
      if((data.time.m >=0) && (data.time.m < 60)) {
        var val = parseDMY(data.date);
        if (val.valid()) return true;
      }
    }
    driver_form_validator.setError('startAt');
    return false;
  },
  'validate_returnAt': function(data){
    if(data.time.H >=0 && data.time.H < 24) {
      if(data.time.m >=0 && data.time.m < 60) {
        if (data.date.length > 0) {
          var val = parseDMY(data.date);
          if (val.valid()) return true;
          if (data.date == data.placeholder) return true;
        } else return true;
      }
    }
    driver_form_validator.setError('returnAt');
    return false;
  },
  'validate_name': function(data){
    if (data.length > 2) return true;
    driver_form_validator.setError('name');
    return false;
  },
  'validate_phone': function(data){
    var re = /^[+#*\(\)\[\]]*([0-9][ ext+-pw#*\(\)\[\]]*){6,45}$/i;
    if (re.test(data)) return true;
    driver_form_validator.setError('phone');
    return false;
  },
  'validate_i_approve': function(data){
    if (data) return true;
    driver_form_validator.setError('i_approve');
    return false;
  },
  'setError': function(el){
    // console.log('error: '+el);
    $('.validate-el-'+el).addClass('error');
    $('.validate-error-'+el).html(driver_form_lang['validate_'+el] || '').show();
  }
}

$(document).ready(function(){
  driver_form.init();
});