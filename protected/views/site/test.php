<div>
	<input type='text' id="start" value="Минск">
	<input type='text' id="end" value="Днепропетровск">
	<label for="avoidHighways"><input type="checkbox" id="avoidHighways" checked> Предпочитать автомагистрали</label>
</div>

<div id="total"></div>
<div id="map_canvas" style="width:70%; height:600px; float:left;"></div>
<div id="directionsPanel" style="width:30%;float:left;">
	<div class="routes-wrapper">
		<h3>Возможные маршруты:</h3>
		<div class="routes"></div>
	</div>
	<div class="additional-points-wrapper">
		<h3>Точки следования</h3>
		<ul class="list"></ul>
		<input type="text" id="additional-point">
		<a htef="#" id="add-additional-point">Добавить</a>
	</div>

	<a href="#" class="test">test</a>
</div>
<div class="clear"></div>
<div class="log"></div>

<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=true"></script>

<script type="text/javascript">
  $(window).ready(function(){
	  initialize();
	  $('#start, #end, #avoidHighways').on('change', function(){
		  calcRoute();
	  });
	  $('body').on('click', '.routes a', function(e){
		  e.preventDefault();
		  directionsDisplay.setRouteIndex($(this).data('index'));
	  });
	  $('body').on('click', '.additional-points-wrapper .list a', function(e){
		  e.preventDefault();
		  delete points[$(this).attr('href')];
		  $(this).parents('li').remove();
		  calcRoute();
	  });
	  $('#add-additional-point').on('click', function(e){
		  e.preventDefault();
		  var locName = $('#additional-point').val();
		  points[locName] = locName;
		  $('.additional-points-wrapper .list').append('<li data-name="'+locName+'">'+locName+'<a href="'+locName+'">X</a></li>');
		  $('#additional-point').val('');
      calcRoute();
      $('.additional-points-wrapper ul.list').sortable({
        update: function( event, ui ) {
          points = [];
          $('.additional-points-wrapper ul.list li').each(function(){
            var value = $(this).data('name');
            points[value] = value;
          });
          calcRoute();
        }
      });
	  });
	  $('a.test').on('click', function(e){
		  e.preventDefault();
		  $('.log').empty();
		  var data = [];
		  for(var i in directionsDisplay.directions.routes[directionsDisplay.routeIndex].overview_path) {
			  data.push({
				  lng : directionsDisplay.directions.routes[directionsDisplay.routeIndex].overview_path[i].lng(),
				  lat : directionsDisplay.directions.routes[directionsDisplay.routeIndex].overview_path[i].lat()
			  });
		  }
		  $.post('/site/testa', {data:data}, function(data){
			  $('.log').append(data);
		  });
	  });
  });

  var rendererOptions = {
	  draggable: true
  };

	var directionsDisplay = new google.maps.DirectionsRenderer(rendererOptions);
	var directionsService = new google.maps.DirectionsService();
	var map;
	var isAuto = false;
	var points = [];


	function initialize() {
		var chicago = new google.maps.LatLng(41.850033, -87.6500523);
		var mapOptions = {
			zoom:7,
			mapTypeId: google.maps.MapTypeId.ROADMAP,
			center: chicago
		}
		map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
		directionsDisplay.setMap(map);
		// directionsDisplay.setPanel(document.getElementById("directionsPanel"));

		google.maps.event.addListener(directionsDisplay, 'directions_changed', function() {
			if (!isAuto) {
				$('.routes').empty().append('<p>Пользовательский маршрут - '+computeTotalDistance(directionsDisplay.directions)+'</p>');
			}
		});

		calcRoute();
	}

	function calcRoute() {
		var start = document.getElementById("start").value;
		var end = document.getElementById("end").value;

		var wp = [];
		for(var i in points) {
			wp.push({
				location: points[i],
				stopover: false
			});
		}

		var request = {
			origin:start,
			destination:end,
      travelMode: google.maps.DirectionsTravelMode.DRIVING,
      optimizeWaypoints: true,
      avoidHighways: !$('#avoidHighways').is(':checked'),
			provideRouteAlternatives: true,
			waypoints:wp
		};
		directionsService.route(request, function(result, status) {
      console.log(result);
      drawRoutes(result);
			if (status == google.maps.DirectionsStatus.OK) {
				isAuto = true;
				directionsDisplay.setDirections(result);
				isAuto = false;
			}
		});

	}

  function drawRoutes(result){
    $('.routes').empty();
    for(var i=0; i<result.routes.length;i++){
      $('.routes').append('<a href="#" data-index="'+i+'">'+result.routes[i].summary+' '+ result.routes[i].legs[0].distance.text + ' - ' + result.routes[i].legs[0].duration.text +'</a><br>');
    }
  }

  function computeTotalDistance(result) {
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
  }

</script>