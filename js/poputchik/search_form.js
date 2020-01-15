(function($){
  var search_form = {
    route_another: 'poputchik-v-gorod',
    route_same: 'poputchik-po-gorodu',
    init: function(){
      $('.clear-input').on('click', function(e){
        e.preventDefault();
        var el = $($(this).attr('href'));
        $(el).val('').removeData().attr("data-slug", "");
      });
      $('input.g_search_au').inputfit({minSize : 12});
      $( window ).scroll(function(){
        rearrangeFloatMenu();
      });
      $('#street_from, #street_to').on('blur', function(){
        $('.route-street img').addClass('active');
        setTimeout(function(){
          $('.route-street img').removeClass('active');
        }, 1000);
      });
      $('#poputchik-search-form').on('submit', function(e){
        e.preventDefault();
      });
      $('#search-button').on('click', function(e){
        e.preventDefault();
        search_form.load(search_form.genUrl());
      });
      $('.route-direction a').on('click', function(e) {
        e.preventDefault();

        var buf_s = $('#start_name').data();
        var buf_e = $('#end_name').data();
        $('#start_name').removeData().data(buf_e);
        $('#end_name').removeData().data(buf_s);

        var buf = $('#start_name').val();
        $('#start_name').val($('#end_name').val());
        $('#end_name').val(buf);

        $('.inputfit').inputfit({minSize: 12, maxSize: 24});
      });
    },
    load: function(url){
      document.location.href = url;
    },
    genUrl: function(){
      var val;
      var routeType = $('.group-routeType input:checked').val();
      var orderType = $('.group-orderType input:checked').val();
      var cityFrom  = $('#start_name').data('slug');
      var cityTo    = $('#end_name').data('slug');

      var url = '/'+routeType+'/';
      url += cityFrom?cityFrom+'/':'';
      if (routeType == search_form.route_another) {
        url += cityFrom?'':'to-city/';
        url += cityTo?cityTo+'/':'';
      }
      url += orderType+'/';
      var date = $('.tripDate').val();
      val = parseDMY(date);
      if (val.valid()) url += 'date/'+date+'/';

      /*
      val = $('.tripAutoClass').val();
      if (val != 'any') url += 'class/'+val+'/';

      val = $('.amount-block-man input:checked').val();
      if (val) url += 'seats/'+val+'/';
      */

      if (routeType == search_form.route_same) {
        val = $('#street_from').val();
        if (val) {
          val = parseFloat($('#street_from').data('lat')).toPrecision(5)+','+parseFloat($('#street_from').data('lng')).toPrecision(5)+','+val;
          url += 'from/'+val+'/';
        }

        val = $('#street_to').val();
        if (val) {
          val = parseFloat($('#street_to').data('lat')).toPrecision(5)+','+parseFloat($('#street_to').data('lng')).toPrecision(5)+','+val;
          url += 'to/'+val+'/';
        }
      }

      return url;
    }
  };

  $(document).ready(function () {
    search_form.init();
  });
})(jQuery);

