<?php ?>

<a class="btn btn-default btn-start hidden">Start</a>

<div class="progress-text"></div>
<div class="progress">
  <div class="progress-bar progress-bar-success" style="width: 0%"></div>
  <div class="progress-bar progress-bar-danger" style="width: 0%"></div>
</div>

<div id="map"></div>
<div id="list">
  <div class="log"></div>
</div>


<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?libraries=places&sensor=true&region=<?=strtoupper(Yii::app()->language)?>&language=<?=Yii::app()->language?>"></script>
<script type="text/javascript">
  google.maps.event.addDomListener(window, 'load', initMap);

  var order_id = <?=$min_id?>;
  var processed = 0;
  var count_overall = <?= $count ?>;
  var myMap;
  var infowindow;
  var directionsDisplay;
  var directionsService;

  var errors_cnt = 0;
  var ok_cnt = 0;
  var api_errors_cnt = 0;

  $(document).ready(function(){
    $('.btn-start').on('click', function(e){
      e.preventDefault();
      $('.btn-start').addClass('hidden');

      setTimeout(getPoint, 100);
    });
  });

  function getPoint(){

    $.ajax({
      type: "POST",
      url: '/ppRoute/import',
      dataType: 'json',
      data: {
        'action' : 'get',
        'id'     : order_id
      },
      success: function(data){
        switch (data.result){
          case 'process':
            order_id = data.id;
            calcRoute(data.start, data.stop);
            break;
          case 'skip':
            order_id = data.id;
            ok_cnt++;
            setTimeout(getPoint, 10);
            break;
          default:
            alert(data.result);
        }
        processed++;
        update_pb();
      },
      error: function(XMLHttpRequest, textStatus, errorThrown) {
        errors_cnt++;
        update_pb();
        setTimeout(getPoint, 10);
      }
    });
  }

  function initMap(){
    var mapProp = {
      center:new google.maps.LatLng(55.745508, 37.435225),
      zoom:5,
      mapTypeId:google.maps.MapTypeId.ROADMAP
    };
    var myMap=new google.maps.Map(document.getElementById("map"),mapProp);
    var rendererOptions = {
      draggable: true
    };
    infowindow = new google.maps.InfoWindow();
    directionsDisplay = new google.maps.DirectionsRenderer(rendererOptions);
    directionsService = new google.maps.DirectionsService();
    directionsDisplay.setMap(myMap);

    $('.btn-start').removeClass('hidden');
    if (order_id > 0) {
      $('.btn-start').click();
    }
  }

  function calcRoute(from, to) {
    var start = new google.maps.LatLng(1*from[0], 1*from[1]);
    var end   = new google.maps.LatLng(1*to[0], 1*to[1]);

    var request = {
      origin:start,
      destination:end,
      travelMode: google.maps.TravelMode.DRIVING,
      avoidHighways: false,
      provideRouteAlternatives: false,
      unitSystem: google.maps.UnitSystem.METRIC
    };

    directionsService.route(request, function(result, status) {
      if (status == google.maps.DirectionsStatus.OK) {
        // directionsDisplay.setMap(myMap);
        directionsDisplay.setDirections(result);
        var wp = [];
        for(var i in result.routes[0].overview_path){
          wp.push({
            'lat': result.routes[0].overview_path[i].lat(),
            'lng': result.routes[0].overview_path[i].lng(),
          });
        }
        $.ajax({
          type: "POST",
          url: '/ppRoute/import',
          data: {
            'action' : 'save',
            'id'     : order_id,
            'path'   : wp
          },
          success: function(msg){
            ok_cnt++;
            update_pb();
            var t = 100+Math.round(500*Math.random());
            setTimeout(getPoint, t);
          },
          error: function(XMLHttpRequest, textStatus, errorThrown) {
            $('#list .log').prepend('Возникла ошибка: маршрут не сохранен<br>');
            errors_cnt++;
            update_pb();
            setTimeout(getPoint, 100);
          }
        });
      } else {
        // directionsDisplay.setMap(null);
        if (status == google.maps.DirectionsStatus.OVER_QUERY_LIMIT) {
          var t = 2+Math.round(Math.random()*5);
          api_errors_cnt++;
          $('#list .log').prepend('Возникла ошибка: достигли лимита API #'+api_errors_cnt+' для объявления '+order_id+'. Повтор через '+t+' секунд(ы)<br>');
          if (api_errors_cnt > 10) {
            document.location.href = '/ppRoute/import?min_id='+order_id;
          } else {
            setTimeout(function(){
              calcRoute(from, to);
            }, t*1000);
          }
        } else {
          errors_cnt++;
          update_pb();
          $('#list .log').prepend('Возникла ошибка: '+status+'<br>');
          setTimeout(getPoint, 20);
        }
      }
    });




/*


    ymaps.route([
      { type: 'wayPoint', point: [1*from[0], 1*from[1]] },
      { type: 'wayPoint', point: [1*to[0]  , 1*to[1]] },
    ], {mapStateAutoApply: true}).then(function (route) {
      myMap.geoObjects.removeAll();
      myMap.geoObjects.add(route);
      // Зададим содержание иконок начальной и конечной точкам маршрута.
      // С помощью метода getWayPoints() получаем массив точек маршрута.
      // Массив транзитных точек маршрута можно получить с помощью метода getViaPoints.
      var points = route.getWayPoints(),
          lastPoint = points.getLength() - 1;
      // Задаем стиль метки - иконки будут красного цвета, и
      // их изображения будут растягиваться под контент.
      points.options.set('preset', 'islands#redStretchyIcon');
      points.options.set('boundsAutoApply', true);
      // Задаем контент меток в начальной и конечной точках.
      points.get(0).properties.set('iconContent', 'Точка отправления');
      points.get(lastPoint).properties.set('iconContent', 'Точка прибытия');

      myMap.setCenter(points.get(0).geometry.getCoordinates());

      // Проанализируем маршрут по сегментам.
      // Сегмент - участок маршрута, который нужно проехать до следующего
      // изменения направления движения.
      // Для того, чтобы получить сегменты маршрута, сначала необходимо получить
      // отдельно каждый путь маршрута.
      // Весь маршрут делится на два пути:
      // 1) от улицы Крылатские холмы до станции "Кунцевская";
      // 2) от станции "Кунцевская" до "Пионерская".

 var wp = [],
          way,
          segments;
      // Получаем массив путей.
      for (var i = 0; i < route.getPaths().getLength(); i++) {
        way = route.getPaths().get(i);
        segments = way.getSegments();
        for (var j = 0; j < segments.length; j++) {
          var wps = segments[j].getCoordinates();
          for(var z = 0; z < wps.length; z++){
            wp.push(wps[z]);
          }
          var street = segments[j].getStreet();
        }
      }
      // console.log(wp);

      $.ajax({
        type: "POST",
        url: '/ppRoute/import',
        data: {
          'action' : 'save',
          'id'     : order_id,
          'path'   : wp
        },
        success: function(msg){
          ok_cnt++;
          update_pb();
          setTimeout(getPoint, 10);
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
          errors_cnt++;
          update_pb();
          setTimeout(getPoint, 10);
        }
      });
    }, function (error) {
      errors_cnt++;
      update_pb();
      $('#list .log').prepend('Возникла ошибка: ' + error.message+'<br>');
      setTimeout(getPoint, 10);
    });
*/
  };

  function update_pb(){
    var p0 = Math.round(1000*ok_cnt/count_overall)/10;
    var p1 = Math.round(1000*errors_cnt/count_overall)/10;
    var p  = Math.round(1000*processed/count_overall)/10;
    $('.progress-text').html(p+'% (#'+order_id+' -> '+processed+' из '+count_overall+') маршрутов конвертированно');
    $('.progress .progress-bar-success').attr('aria-valuenow', p0).css('width', p0+'%').html(p0+'%');
    $('.progress .progress-bar-danger').attr('aria-valuenow', p1).css('width', p1+'%').html(p1+'%');
  }
</script>

<style>
  #list {
    padding: 10px;
  }
  #map {
    width: 100%; height: 350px;
  }
</style>


