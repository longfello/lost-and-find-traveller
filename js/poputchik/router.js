/* copyright (c) Miloslawsky, 2015 */
var router = {
  directionsDisplay: null,
  directionsService: null,
  map: null,
  isManualAddWP: false,
  isAuto: false,
  waypoint_markers: [],
  infowindow: null,
  wpts: [],
  els: {
    map_canvas_id        : 'map_canvas',
    start                : '.route_start',
    start_addr           : '.route_start_address',
    end                  : '.route_end',
    end_addr             : '.route_end_address',
    highway              : '#avoidHighways',
    optimizeWaypoints    : '#optimizeWaypoints',
    wrapper              : 'ac_wrapper',
    route_list           : '.routes',
    route_waypoint       : 'input.route_waypoint',
    add_waypoint         : 'a.btn-add-point',
    waypoint_list        : 'ul.points',
    waypoint_item_remove : 'a.remove-waypoint',
    swap_link            : '.change-arrow a'
  },
  init: function(lng, lat){
    router.initialize_map(lng, lat);

    google.maps.Map.prototype.markers = [];

    google.maps.Map.prototype.getMarkers = function() {
      return this.markers
    };

    google.maps.Map.prototype.clearMarkers = function() {
      for(var i=0; i<this.markers.length; i++){
        this.markers[i].setMap(null);
      }
      this.markers = [];
    };

    google.maps.Marker.prototype._setMap = google.maps.Marker.prototype.setMap;

    google.maps.Marker.prototype.setMap = function(map) {
      if (map) {
        map.markers[map.markers.length] = this;
      }
      this._setMap(map);
    };

    $(router.els.start+','+router.els.end).on('selected', function(){
      router.isManualAddWP = true;
      router.updateBounce(this);
      router.calcRoute();
    });
    $(router.els.start+','+router.els.end+','+router.els.highway+','+router.els.optimizeWaypoints).on('change', function(){
      if (!$(this).data('onAC')) {
        $(this).data('pos', $(this).val());
        router.isManualAddWP = true;
        router.calcRoute();
      }
    });
    $('body').on('click', router.els.add_waypoint, function(e){
      e.preventDefault();
      router.get_current_waypoints();
      var data = $(router.els.route_waypoint).data();
      var el = new google.maps.LatLng(data.lat, data.lng);
      el.name = $(router.els.route_waypoint).val();
      router.wpts.push(el);
      router.isManualAddWP = true;
      router.calcRoute(router.wpts);

      $(router.els.route_waypoint).val('').removeData();
    });
    $(router.els.route_list).on('click', 'a', function(e){
      e.preventDefault();
      router.directionsDisplay.setRouteIndex($(this).data('index'));
      router.drawMarkers();
    });
    $(router.els.waypoint_list).on('click', router.els.waypoint_item_remove, function(e){
      e.preventDefault();
      $(this).parents('li').remove();
      router.updateWaypoints();
      router.isManualAddWP = true;
      router.calcRoute();
    });
    $('.route-periodicity input[name=periodicity]').on('change', function(){
      $('.periodicity-wrapper').slideUp();
      $('.periodicity-'+$('.route-periodicity input[name=periodicity]:checked').val()+'-wrapper').slideDown();
    }).change();
    $(router.els.waypoint_list).sortable({
      update: function(event, ui) {
        router.updateWaypoints();
        router.calcRoute();
      }
    });
    $(router.els.swap_link).on('click', function(e){
      e.preventDefault();

      var buf_s = $(router.els.start).data();
      var buf_e = $(router.els.end).data();
      $(router.els.start).removeData().data(buf_e);
      $(router.els.end)  .removeData().data(buf_s);

      var buf = $(router.els.start).val();
      $(router.els.start).val($(router.els.end).val());
      $(router.els.end).val(buf);

      $('.inputfit').inputfit({minSize : 12, maxSize : 24});

      router.updateWaypoints();
      router.isManualAddWP = true;
      router.calcRoute();
    });
  },
  updateBounce: function(el){
    if ($(el).data('child')) {
      var coord = $(el).data('area');

      var bounds = new google.maps.LatLngBounds(
          new google.maps.LatLng(Math.min(coord.start.lat, coord.end.lat), Math.min(coord.start.lng, coord.end.lng)),
          new google.maps.LatLng(Math.max(coord.start.lat, coord.end.lat), Math.max(coord.start.lng, coord.end.lng)));

      $($(el).data('child')).trigger('setBounds', {bounds: bounds});
    }
  },
  initialize_map: function (lng, lat){
    var rendererOptions = {
      draggable: true
    };

    router.infowindow = new google.maps.InfoWindow();
    router.directionsDisplay = new google.maps.DirectionsRenderer(rendererOptions);
    router.directionsService = new google.maps.DirectionsService();
    router.isAuto = false;

    var mapOptions = {
      zoom:8,
      mapTypeId: google.maps.MapTypeId.ROADMAP,
      center: new google.maps.LatLng(lat, lng)
    }
    router.map = new google.maps.Map(document.getElementById(router.els.map_canvas_id), mapOptions);
    router.directionsDisplay.setMap(router.map);

    google.maps.event.addListener(router.directionsDisplay, 'directions_changed', function() {
      // router.drawMarkers();
      if (!router.isAuto) {
        router.correctAddress();
        if (router.directionsDisplay.directions.routes.length > 0) {
          router.drawRoutes(router.directionsDisplay.directions);
        } else {
          $(router.els.route_list).empty().append('<p>Пользовательский маршрут - '+router.computeTotalDistance(router.directionsDisplay.directions)+'</p>');
        }
      }
      router.correctLocationInputs();
      router.updateInputData();

      if (!router.isManualAddWP) {
        router.get_current_waypoints();
        router.calcRoute(router.wpts);
      }
      router.isManualAddWP = false;
    });
    router.calcRoute();
  },
  get_current_waypoints: function(){
    router.wpts = [];
    var legs = router.directionsDisplay.directions.routes[router.directionsDisplay.routeIndex].legs; //[0].via_waypoints;
    for (var i=0; i<legs.length; i++) {
      for(var n=0; n<legs[i].via_waypoints.length; n++){
        var el = legs[i].via_waypoints[n];
        el.name = i.toString();
        el.name = (el.name == '0') ? el.toString() : el.name ;
        router.wpts.push(el);
      }
      var el = legs[i].end_location;
      el.name = legs[i].end_address;
      router.wpts.push(el);
    }
    router.wpts.pop();
    return router.wpts;
  },
  watch_waypoints: function (){
    router.clear_markers();
    router.get_current_waypoints();
    for(var i=0; i<router.wpts.length; i++) {
      var marker = new google.maps.Marker({
        map: router.map,
        position: router.wpts[i],
        title: router.wpts[i].name
      });
    }
  },
  clear_markers: function () {
    for(var i=0; i<router.waypoint_markers.length; i++){
      router.waypoint_markers[i].setMap(null);
    }
  },
  calcRoute: function (waypoints) {
    console.log('calcRoute');
    var start = $(router.els.start).data('pos');
    var end   = $(router.els.end).data('pos');

    if (!end || !start) return;

    var wp = [];
    waypoints = waypoints?waypoints:router.wpts;
    if(waypoints) {
      console.groupCollapsed('waypoints');
      console.log(waypoints);
      console.groupEnd();
      wp = waypoints.map(function(wpt) {return {location: wpt, stopover: true};});
    }

    var request = {
      origin:start,
      destination:end,
      travelMode: google.maps.TravelMode.DRIVING,
      optimizeWaypoints: $(router.els.optimizeWaypoints).is(':checked'),
      avoidHighways: !$(router.els.highway).is(':checked'),
      provideRouteAlternatives: true,
      waypoints:wp
    };

    /*
    console.groupCollapsed('request');
    console.log(request);
    console.groupEnd();
    */

    router.directionsService.route(request, function(result, status) {
      router.drawRoutes(result);
      if (status == google.maps.DirectionsStatus.OK) {
        router.isAuto = true;
        router.directionsDisplay.setMap(router.map);
        router.directionsDisplay.setDirections(result);
        router.isAuto = false;
        router.updateInputData();
      } else {
        google.maps.Map.prototype.clearMarkers();
        router.directionsDisplay.setMap(null);
        console.groupCollapsed('error');
        console.log(result);
        console.log(status);
        console.groupEnd();
        alert('Путь не найден');
      }
      router.drawMarkers();
      // $('body').trigger('route-rebuilded');
      // router.correctLocationInputs();
    });
  },
  drawRoutes: function (result){
    $(router.els.route_list).empty();
    if (result && result.routes) {
      for(var i=0; i<result.routes.length;i++){
        $(router.els.route_list).append('<a href="#" data-index="'+i+'">'+result.routes[i].summary+' '+ result.routes[i].legs[0].distance.text + ' - ' + result.routes[i].legs[0].duration.text +'</a><br>');
      }
    }
  },
  drawMarkers: function(){
    setTimeout(function(){
      router.get_current_waypoints();
      google.maps.Map.prototype.clearMarkers();
      router.watch_waypoints();
      router.drawEndpoints();
      router.drawWaypoints();
    }, 1000);
  },
  drawWaypoints: function(){
    $(router.els.waypoint_list).html('');
    for(var i=0; i<router.wpts.length; i++){
      $(router.els.waypoint_list).append($('<li>').html(router.wpts[i].name).data(router.wpts[i]).append($('<a class="glyphicon glyphicon-remove pull-right remove-waypoint" href="#">')));
    }
  },
  drawEndpoints: function(){
    var marker_start = new google.maps.Marker({
      map: router.map,
      icon: "/images/Map-Marker-Green.png",
      position: new google.maps.LatLng($(router.els.start).data('lat'), $(router.els.start).data('lng')),
      title: $(router.els.start).val()
    });
    var marker_end = new google.maps.Marker({
      map: router.map,
      icon: "/images/Map-Marker-Green.png",
      position: new google.maps.LatLng($(router.els.end).data('lat'), $(router.els.end).data('lng')),
      title: $(router.els.end).val()
    });
  },
  updateWaypoints: function(){
    router.wpts = [];
    $(router.els.waypoint_list+' li').each(function(){
      var data = $(this).data();
      var el = new google.maps.LatLng(data.lat(), data.lng());
      el.name = data.name;
      router.wpts.push(el);
    });
  },
  computeTotalDistance: function (result) {
    var total = 0;
    var time = 0;
    var myroute = result.routes[0];
    for (i = 0; i < myroute.legs.length; i++) {
      total += myroute.legs[i].distance.value;
      time  += myroute.legs[i].duration.value;
    }
    total = Math.round(total / 1000, 1);
    time  = Math.round(time / 3600, 1);
    return total + ' км - ' + time + ' часов';
  },
  updateInputData: function (){
    if (!router.directionsDisplay.directions) return;
    var route = router.directionsDisplay.directions.routes[router.directionsDisplay.routeIndex].overview_path;

    /*
    console.groupCollapsed('route');
    console.log(route);
    console.groupEnd();
    */

    var source = {
      lat: route[0].lat(),
      lng: route[0].lng(),
      pos: route[0].lat()+','+route[0].lng()
    };
    var target = {
      lat: route[route.length-1].lat(),
      lng: route[route.length-1].lng(),
      pos: route[route.length-1].lat()+','+route[route.length-1].lng()
    };
    $('.route_start').data(source);
    $('.route_end').data(target);
  },
  correctLocationInputs: function (func){
    var route = router.directionsDisplay.directions.routes[router.directionsDisplay.routeIndex].overview_path;

    var source = {
      lat: route[0].lat(),
      lng: route[0].lng()
    };
    var target = {
      lat: route[route.length-1].lat(),
      lng: route[route.length-1].lng()
    };

    var data2correct = [];

    var source_data = $('.route_start').data();
    var target_data = $('.route_end').data();

    if (source.lat != source_data.lat || source.lng != source_data.lng) {
      data2correct.push({
        'type': 'route_start',
        'lat' : source.lat,
        'lng' : source.lng,
        'id'  : source_data.id
      });
    }
    if (target.lat != target_data.lat || target.lng != target_data.lng) {
      data2correct.push({
        'type': 'route_end',
        'lat' : target.lat,
        'lng' : target.lng,
        'id'  : target_data.id
      });
    }

    if (data2correct.length > 0) {
      $.post('/api/cityCorrector', {data:data2correct}, function(data){
        // console.log(data.correct);
        if (data && data.result && data.correct){
          // console.log(data.correct);
          for (var i in data.correct){
            var el = $('.'+i);
            $(el)
              .data('id', data.correct[i].id)
              .data('area', data.correct[i].area)
              .data('pos', $(el).data('lat')+','+$(el).data('lng'))
              .val(data.correct[i].name);
            router.updateBounce(el);
          }
        }
        if (func instanceof Function) {
          func();
        }
      }, 'json');
    } else {
      // console.log('конечные точки не поменялись');
    }
  },
  correctAddress: function(){
    var route = router.directionsDisplay.directions.routes[router.directionsDisplay.routeIndex].overview_path;

    var source = {
      lat: route[0].lat(),
      lng: route[0].lng()
    };
    var target = {
      lat: route[route.length-1].lat(),
      lng: route[route.length-1].lng()
    };

    var source_data = $('.route_start').data();
    var target_data = $('.route_end').data();
    var data = [];

    if (source.lat != source_data.lat || source.lng != source_data.lng) {
      data.push({
        'type': 'route_start',
        'lat' : source.lat,
        'lng' : source.lng
      });
    }
    if (target.lat != target_data.lat || target.lng != target_data.lng) {
      data.push({
        'type': 'route_end',
        'lat' : target.lat,
        'lng' : target.lng
      });
    }

    for(var i=0; i<data.length; i++) {
      var info = data[i];
      var href = 'http://nominatim.openstreetmap.org/search?format=json&q='+info.lat+','+info.lng+'&accept-language=ru&addressdetails=1';
      $.get(href, function(res){
        var addr = (res[0] && res[0].address)?res[0].address:false;
        var name = (res[0] && res[0].display_name)?res[0].display_name:false;;
        if (addr) {
          name = '';
          name += addr.road?addr.road:'';
          name += addr.house_number?', '+addr.house_number:'';
        }

        $('.'+info.type+'_address').val(name);
      }, 'json');
    }
    /*
    */
    //$('.'+type+'_address').val('');
  },
  selectCityByName: function(el, name){
    $(el).val(name);
    var downKeyEvent = $.Event("keydown");
    downKeyEvent.keyCode = $.ui.keyCode.DOWN;  // event for pressing "down" key

    $(el).off( "autocompleteopen").on( "autocompleteopen", function( event, ui ) {
      $(el).off( "autocompleteopen")
      $(el).trigger(downKeyEvent);  // Second downkey highlights first item
      $(el).trigger(enterKeyEvent); // Enter key selects highlighted item
    } );

    var enterKeyEvent = $.Event("keydown");
    enterKeyEvent.keyCode = $.ui.keyCode.ENTER;  // event for pressing "enter" key
    $(el).trigger(downKeyEvent);  // First downkey invokes search
  }
}