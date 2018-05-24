


<?php
//AIzaSyBOYpM5-5dcQSsoisKN8ouo9dD4giC66Vk

require_once('header.php');

//session_start();
$timestamp   = time();

        try{
            $db = new PDO('sqlsrv:Server=wserver.area42.fr;Database=mygavoltpins', 'mygavolt', 'k2Y*bswsaFyss3j7*Hsf',array(
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8',
                PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING
            ));

        }catch(PDOException $e){
            die('<h1>Impossible de se connecter</h1>');
        }?>

<div class="admin_home">
  <h3>Geolocalisation des employés</h3>

  <?php
        $select = $db->prepare("SELECT * from coordinates where date_position in (select MAX(date_position) from coordinates group by id_employee)");

        $select->execute();


        ?>

        <script >
          var locations = [];
        </script>



        <table class="table">
          <tr>
              <th>Employée</th>
              <th>Heure </th>
              <th>Latitude</th>
              <th>Longitude</th>
          </tr>
          <?php
        while($s=$select->fetch(PDO::FETCH_OBJ)){

          $c = $s->lat;
          $pos1 = strrpos($c, "(");
          $pos2 = strrpos($c, ",");
          $pos3 = strrpos($c, ")");
          $lat = substr($c, $pos1+1, $pos2-$pos1-1);
          $lng = substr($c, $pos2+1, $pos3-$pos2-1);
          ?>
            <script>


              locations.push(
                {lat: <?php echo $lat;?>, lng: <?php echo $lng;?>}
              );

             // alert("lat:"+<?php echo $lat;?>+", lng:" +<?php echo $lng;?>);

            </script>

            <tr>
              <td><?php echo $s->id_employee;?></td>
              <td><?php echo $s->date_position;?></td>
              <td><?php echo $lat;?></td>
              <td><?php echo $lng;?></td>
            </tr><?php
                }
                ?>

          </table>





<div id="map"></div>
    <script>

      function initMap() {

        //alert(locations);

        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 9,
          center: {lat: 50.633333, lng: 3.066667}
        });

        // Create an array of alphabetical characters used to label the markers.
        var labels = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

        // Add some markers to the map.
        // Note: The code uses the JavaScript Array.prototype.map() method to
        // create an array of markers based on a given "locations" array.
        // The map() method here has nothing to do with the Google Maps API.
        var markers = locations.map(function(location, i) {
          return new google.maps.Marker({
            position: location,
            label: labels[i % labels.length]
          });
        });



        // Add a marker clusterer to manage the markers.
        var markerCluster = new MarkerClusterer(map, markers,
            {imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'});
      }

    </script>
    <script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js">
    </script>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBOYpM5-5dcQSsoisKN8ouo9dD4giC66Vk&callback=initMap">
    </script>














</div>


</body>
