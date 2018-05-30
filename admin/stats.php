
<?php

require_once('header.php');?>

<a href="?action=statsprod" class="btn btn-warning" role="button">Ventes par produit</a>
<a href="?action=statscommandejour" class="btn btn-warning" role="button">Ventes par jours</a><br>

<div class="full_cart">

  <?php
// vérification qu'on a bien rentré un username
  if(isset($_SESSION['username'])){
  // vérification si il y a un code action
    if(isset($_GET['action'])){
    // si le code action est en mode 'ajout'



    //************************************************************COMMANDES PAR JOUR************************************************************
//************************************************************AJOUTER***************************************************************
     if ($_GET['action']=='statscommandejour'){

      $mes_labels = array();
      $mes_donnees = array();

      $select = $db->prepare("SELECT order_date , count(*) as qte from orders group by order_date;");
      $select->execute();
      ?>
      <table class="table">
        <tr>
          <th>Date</th>
          <th>Ventes</th>
        </tr>

        <?php

        while($s=$select->fetch(PDO::FETCH_OBJ)){

          array_push($mes_labels, $s->order_date);
          array_push($mes_donnees, $s->qte);

          ?>
          <tr>
            <td> <?php echo $s->order_date; ?></td>
            <td> <?php echo $s->qte; ?></td>
          </tr>
          <?php

        }
        ?>
      </table>

      <?php




    }
//************************************************************VENTES PAR PRODUIT************************************************************
//************************************************************AFFICHER***************************************************************
    else if ($_GET['action']=='statsprod'){?>

      <h2>Choix de la date</h2>
      <form action="" method="post">
        <input type="date" name="date_vente">
        <input type="submit" name="submit" value="Afficher">
      </form>

      <?php
      if(isset($_POST['submit'])){
        $date_vente = $_POST['date_vente'];
        if($date_vente){
          $select = $db->prepare("SELECT products.id as prod_id, products.label as prod_label, SUM(quantity) as prod_qte from orders join products on id_product = products.id WHERE order_date = '$date_vente' group by products.id, products.label;");
          $select->execute();
          echo $date_vente;
        }
        else{
          echo "Veuillez renseigner une valeur";
          return;
        }
      }
      else{
        $select = $db->prepare("SELECT products.id as prod_id, products.label as prod_label, SUM(quantity) as prod_qte from orders join products on id_product = products.id group by products.id, products.label;");
      $select->execute();
      }

      ?>


      <?php



        // on récupère et affiche tous les produits vendus depuis toujours et leur nombre de ventes
      ?>
      <br>
      <?php

      ?>
      <table class="table">
        <tr>
          <th>ID</th>
          <th>Label </th>
          <th>Quantité</th>
        </tr>

        <?php

        while($s=$select->fetch(PDO::FETCH_OBJ)){
          ?>
          <tr>
            <td> <?php echo $s->prod_id; ?></td>
            <td> <?php echo $s->prod_label; ?></td>
            <td> <?php echo $s->prod_qte; ?></td>
          </tr>
          <?php

        }
        ?>
      </table>
      <?php

    }



  }
}?>
</div>

<div class="mychart" style="width: 50%">
  <canvas id="myChart"></canvas>
</div>
<script >
  var ctx = document.getElementById('myChart').getContext('2d');

  Chart.defaults.global.title.display=true;
  Chart.defaults.global.title.text = "Toto";
  Chart.defaults.global.title.fontColor = '#FFF';
  var chart = new Chart(ctx, {
        // The type of chart we want to create
        type: 'line',

        // The data for our dataset
        data: {
          labels: ["January", "February", "March", "April", "May", "June", "July"],
          datasets: [{
            label: "My First dataset",
           // backgroundColor: 'rgb(255, 99, 132)',
            borderColor: 'rgb(255, 99, 132)',
            data: $mes_donnees,
          }]
        },

        // Configuration options go here
        options: {
          title: {
            text: "Evolution"
          },

          elements: {
            point: {
              radius: 10,
              backgroundColor: 'rgba(0,0,255)'
            }
          }
        }
      });
    </script>




