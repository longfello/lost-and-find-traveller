(function($){
  $(document).ready(function(){
    $('#routes-paths-search-results-tabs ul.nav-tabs a').click(function(e) {
      e.preventDefault();
      $(this).tab('show');
    });

    $('.show-the-map').on('click', function(e){
      e.preventDefault();

      var id = $(this).data('id');
      $(this).hide();
      var wrapper  = $(this).parents('.poput-order').find('.the-map-wrapper').show();
      var wrapper  = $(this).parents('.poput-order').find('.the-map');
      wrapper.show().html($('<div class="route-map" id="route-map-'+id+'" style="width:'+parseInt(wrapper.width())+'px; height:'+parseInt(wrapper.width()/2)+'px;"></div>'));

      var directionsDisplay;
      var directionsService = new google.maps.DirectionsService();
      var map;

      directionsDisplay = new google.maps.DirectionsRenderer();
      var mapOptions = {
        zoom: 1,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        center: new google.maps.LatLng(44, 33)
      }
      map = new google.maps.Map(document.getElementById('route-map-'+id), mapOptions);
      directionsDisplay.setMap(map);

      $.get('/poputchikOrder/getRoutePath/id/'+id, function(data){
        console.log(data);
        var start = new google.maps.LatLng(data.from[1],data.from[0]);
        var end   = new google.maps.LatLng(data.to[1],data.to[0]);
        var waypts = [];
        for(var i in data.path) {
          waypts.push({
            location:new google.maps.LatLng(data.path[i].lat, data.path[i].lng),
            stopover:true
          });
          new google.maps.Marker({
            position: new google.maps.LatLng(data.path[i].lat, data.path[i].lng),
            map: map,
            title: data.path[i].lat+', '+data.path[i].lng
          });
          //console.log(data[i][0]);
        }
        /*
        console.log(data.pathf.length);
        for(var i in data.pathf) {

          var populationOptions = {
            strokeColor: '#FF0000',
            strokeOpacity: 0.8,
            strokeWeight: 2,
            fillColor: '#FF0000',
            fillOpacity: 0.35,
            map: map,
            center: new google.maps.LatLng(data.pathf[i][0][0], data.pathf[i][0][1]),
            radius: 100
          };
          // Add the circle for this city to the map.
          new google.maps.Circle(populationOptions);

          //console.log(data[i][0]);
        }

        console.log(waypts);
        */

        var request = {
          origin: start,
          destination: end,
          waypoints: waypts,
          optimizeWaypoints: true,
          travelMode: google.maps.TravelMode.DRIVING
        };


        directionsService.route(request, function(response, status) {
          if (status == google.maps.DirectionsStatus.OK) {
            directionsDisplay.setDirections(response);

            var route = response.routes[0];

            var summaryPanel = $(wrapper).parents('.poput-order').find('.the-map-route-info');

            summaryPanel.append(response.routes[0].legs[0].distance.text + ' - ' + response.routes[0].legs[0].duration.text);

            /*
             var summaryPanel = $(wrapper).parents('.poput-order').find('.the-map-route-info');
            // For each route, display summary information.
            var distance;
            for (var i = 0; i < route.legs.length; i++) {
              var routeSegment = i + 1;
              summaryPanel.append('<b>Route Segment: ' + routeSegment + '</b><br>');
              summaryPanel.append(route.legs[i].start_address + ' to ');
              summaryPanel.append(route.legs[i].end_address + '<br>');
              summaryPanel.append(route.legs[i].distance.text + '<br><br>');
            }


            console.log(route);
            */
          } else {
            console.log(response);
            console.log(status);
          }
        });
      }, 'json');
    });
  });

})(jQuery);

/*


var start = document.getElementById('start').value;
var end = document.getElementById('end').value;
var waypts = [];
var checkboxArray = document.getElementById('waypoints');
for (var i = 0; i < checkboxArray.length; i++) {
  if (checkboxArray.options[i].selected == true) {
    waypts.push({
      location:checkboxArray[i].value,
      stopover:true});
  }
}

var request = {
  origin: start,
  destination: end,
  waypoints: waypts,
  optimizeWaypoints: true,
  travelMode: google.maps.TravelMode.DRIVING
};
directionsService.route(request, function(response, status) {
  if (status == google.maps.DirectionsStatus.OK) {
    directionsDisplay.setDirections(response);
    var route = response.routes[0];
    var summaryPanel = document.getElementById('directions_panel');
    summaryPanel.innerHTML = '';
    // For each route, display summary information.
    for (var i = 0; i < route.legs.length; i++) {
      var routeSegment = i + 1;
      summaryPanel.innerHTML += '<b>Route Segment: ' + routeSegment + '</b><br>';
      summaryPanel.innerHTML += route.legs[i].start_address + ' to ';
      summaryPanel.innerHTML += route.legs[i].end_address + '<br>';
      summaryPanel.innerHTML += route.legs[i].distance.text + '<br><br>';
    }
  }
});

    */