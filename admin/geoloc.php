

<?php


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

<div class="admin_home">Bienvenue <?php echo $_SESSION['username'] ?><br>


  <?php
        $select = $db->prepare("SELECT * from coordinates where date_position in (select MAX(date_position) from coordinates group by id_employee)");

        $select->execute();?>

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
            <tr>
              <td><?php echo $s->id_employee;?></td>
              <td><?php echo $s->date_position;?></td>
              <td><?php echo $lat;?></td>
              <td><?php echo $lng;?></td>
            </tr><?php
                }
                ?>

          </table>
</div>


</body>
