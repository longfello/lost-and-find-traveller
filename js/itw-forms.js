(function( $ ) {
  $.fn.itwForms = function( method ){
    var logEnabled = true;
    var settings;
    var methods  = {
      init : function( options ) {
        return this.each(function() {
          settings = $.extend( {
            steps              : [],
            step_selector      : '.step',
            next_step_selector : '.next-step',
            step_link          : '.number-step-header .step-circle a',
            step_count         : 0,
            current_step       : 0,
            form               : '',
            ajaxValidate       : true
          }, options);

          settings.form         = $(this);
          settings.current_step = 1;
          settings.steps        = $(this).find(settings.step_selector);
          settings.step_count   = settings.steps.size();
          settings.steps.hide();
          settings.steps.first().show();

          settings.form.find(settings.step_link).on('click.itwForms', function(e){
            e.preventDefault();
            var step = parseInt($(this).html());
            $(settings.form).itwForms('goto', step);
          });
          settings.form.find(settings.next_step_selector).on('click.itwForms', function(e){
            e.preventDefault();
            intMethods.log('click');
            intMethods.log(settings.form);
            $(settings.form).itwForms('goto', settings.current_step+1)
          });
          intMethods.save(settings);
        });
      },
      goto: function(step) {
        settings = intMethods.load(this);
        if (step <= settings.step_count) {
          intMethods._rewind(step);
        }
      }
    };

    var intMethods = {
      save: function(settings) {
        settings.form.data('itw-forms-settings', settings);
      },
      load: function(el){
        return $(el).data('itw-forms-settings');
      },
      log:  function(content){
        if (logEnabled) {
          console.log(content);
        }
      },
      _rewind: function(step){
        if (settings.current_step < step) {
          if (settings.current_step != step) {
            intMethods._goto(settings.current_step+1, true, function(result){
              if(result) {
                intMethods._rewind(step);
              }
            })
          }
        } else intMethods._goto(step, false);
      },
      _goto: function(step, validate, callback){
        if (validate && settings.ajaxValidate) {
          var data = settings.steps.eq(step-1).find(':input').serializeArray();
          $.post(document.location.href, {
            'action': 'validate',
            'data'  : data,
            'step'  : settings.current_step
          }, function(responce){
            console.log(responce);
            if (responce.result == 'ok') {
              intMethods._show(step);
              if (callback instanceof Function) {
                callback(true);
              }
            } else {
              alert('error');
              if (callback instanceof Function) {
                callback(false);
              }
            }
          }, 'json');
        } else {
          intMethods._show(step);
          if (callback instanceof Function) {
            callback(true);
          }
        }
      },
      _show: function(step) {
        settings.steps.hide();
        settings.steps.eq(step-1).show();
        settings.current_step = step;

        settings.form.find(settings.step_link).removeClass('active').eq(step-1).addClass('active');
        intMethods.save(settings);

        $.scrollTo(settings.step_link, 500);
      }
    }

    // Разбор методов
    if ( methods[method] ) {
      return methods[method].apply( this, Array.prototype.slice.call( arguments, 1 ));
    } else if ( typeof method === 'object' || ! method ) {
      return methods.init.apply( this, arguments );
    } else {
      $.error( 'Метод с именем ' +  method + ' не существует для jQuery.itwForms' );
    }

    return this;
  };
})(jQuery);

$(document).ready(function(){

  $('#review').hide();
  $('.itw-multistep-form').itwForms();

});