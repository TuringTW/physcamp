<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <title>Map</title>
    <style>
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
      #map {
        height: 100%;
      }
    </style>
  </head>
  <body>
    <div id="mapDiv" style="height:100%"></div>
  </body>
    <?php echo js_url('/jquery-2.1.0.js')?>
  <?php echo js_url('/bootstrap.min.js')?>
  <?php echo js_url('/bootstrap.js')?>
  <?php echo js_url('/jquery-ui.js')?>
  <?php echo js_url('/bootstrap-fileupload.js')?>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBjIndPdT0xxRoICFwBn80KBwexWYMYu9o&signed_in=true&callback=initMap"></script>
<script type="text/javascript">
var map;
var ssize;
var markers = [];

function initMap() {

  map = new google.maps.Map(document.getElementById('mapDiv'), {
    zoom: 18,
    center: {lat:25.020393, lng:121.537262},
    mapTypeId: google.maps.MapTypeId.SATELLITE,
    navigationControl: false,
    mapTypeControl: false,
    scaleControl: false,
    draggable: true,
    scaleControl: false,
    scrollwheel: false,
  });

  // // This event listener will call addMarker() when the map is clicked.
  // map.addListener('click', function(event) {
  //   addMarker(event.latLng);
  // });

  // // Adds a marker at the center of the map.
  // addMarker(haightAshbury);
  refreshmarker();
}

// Adds a marker to the map and push to the array.
function addMarker(location, team) {
  ssize = new google.maps.Size(25, 25);
  var img = getstateimage(team);
  var marker = new google.maps.Marker({
    position: location,
    map: map,
    icon: {
      url:img,
      scaledSize: ssize ,
    }


  });
  markers.push(marker);
}

// Sets the map on all markers in the array.
function setMapOnAll(map) {
  for (var i = 0; i < markers.length; i++) {
    markers[i].setMap(map);
  }
}

// Removes the markers from the map, but keeps them in the array.
function clearMarkers() {
  setMapOnAll(null);
}

// Shows any markers currently in the array.
function showMarkers() {
  setMapOnAll(map);
}

// Deletes all markers in the array by removing references to them.
function deleteMarkers() {
  clearMarkers();
  markers = [];
}

function setMarker(location, team, index){
  // alert(team);

  
  
  
  var img = getstateimage(team)
  var latlng = new google.maps.LatLng(location.lat, location.lng);
  // markers[i].setPosition(location);
  markers[index].setPosition(latlng);
  markers[index].setIcon({
    url:img,
    scaledSize:ssize,
  });
  
}

function getstateimage(state){
  var img = "http://maps.google.com/mapfiles/kml/paddle/wht-circle.png";
  if (state=="0") {
    img = "http://maps.google.com/mapfiles/kml/paddle/wht-circle.png";
  }else if(state=="1"){
    img = "http://maps.google.com/mapfiles/kml/paddle/red-circle.png";
  }else if(state=="2"){
    img = "http://maps.google.com/mapfiles/kml/paddle/blu-circle.png";
  }
  return img;
}
function refreshmarker(){
  // var stu_id = $('#stu_id').val();
  // data = 'name=' + name + '&school=' + school + '&mobile=' + mobile +  '&home=' + home + '&email=' + email + '&id_num=' + id_num + '&birthday=' + birthday + '&emg_name=' + emg_name + '&emg_phone=' + emg_phone +'&reg_address=' +reg_address + '&mailing_address=' + mailing_address+'&note='+note+'&stu_id='+stu_id;
  var xhr;  
  if (window.XMLHttpRequest) { // Mozilla, Safari, ...  
    xhr = new XMLHttpRequest();  
  } else if (window.ActiveXObject) { // IE 8 and older  
    xhr = new ActiveXObject("Microsoft.XMLHTTP");  
  }  
  data = "";
  xhr.open("POST", "<?=web_url('/site/get_all_site')?>");
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');                    
  xhr.send(data);  
  function display_datas() {  
    if (xhr.readyState == 4) {  
      if (xhr.status == 200) {  
        data = JSON.parse(xhr.responseText.trim());
        for (var i = data.sites.length - 1; i >= 0; i--) {
          var lati = Number(data.sites[i].lati);
          var longi = Number(data.sites[i].longi);
          if (markers.length!=35) {
            addMarker({lat:lati, lng:longi},data.sites[i].team);  
          }else{
            setMarker({lat:lati, lng:longi},data.sites[i].team, i);
          }
          // alert(markers.length);
          
          // alert(data.sites[i].longi);
        };
      } else {  
        errormsg('資料傳送出現問題，等等在試一次.');  
      }  
    }  
  }  
  var d = new Date();
  var n = d.getTime();

  $('#refresh_t_ref').val(n);


  xhr.onreadystatechange = display_datas; 
  setTimeout('refreshmarker()',10000); 
}
var ts=0;
function refresh_time(t){
  var t_ref = $('#refresh_t_ref').val();
  var sec = Math.round((t-t_ref)/1000);
  $('#refreshtime').html(sec);
  if (ts==3) {
    deleteMarkers();
    initMap();
  }
  ts+=1;
}
    </script>
  
  </body>
</html>