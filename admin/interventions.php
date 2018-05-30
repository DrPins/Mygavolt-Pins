
<?php

require_once('header.php');?>


<a href="?action=add" class="btn btn-warning" role="button">Ajouter</a>

<a href="?action=modify" class="btn btn-warning" role="button">Modifier / Supprimer</a><br>

<div class="full_cart">
  <?php
// vérification qu'on a bien rentré un username
  if(isset($_SESSION['username'])){
  // vérification si il y a un code action
    if(isset($_GET['action'])){
    // si le code action est en mode 'ajout'

      if ($_GET['action']=='add'){

        if(isset($_POST['submit'])){
      // on récupère dans des variables les informations produit
          $employe     = $_POST['employe'];
          $client      = $_POST['client'];
          $date_inter  = $_POST['date_inter'];
          $heure       = $_POST['hour'];
          $motif       = $_POST['motive'];




      //************************************************************CREATION************************************************************

      // on vérifie que toutes les informations ont bien été renseignées
          if($employe&&$client&&$motif&&$date_inter&&$heure){
        //permet de récupérer l'id de l'employé, du client puis du motif afin de pouvoir alimenter la base
            $select_emp = $db->prepare("SELECT * FROM employees where lastname = '$employe'");
            $select_emp->execute();
            $emp_id = $select_emp->fetch(PDO::FETCH_OBJ);
            $emp_id = $emp_id->id;

            $select_client = $db->prepare("SELECT * FROM clients where lastname = '$client'");
            $select_client->execute();
            $client_id = $select_client->fetch(PDO::FETCH_OBJ);
            $client_id = $client_id->id;

            $select_motif = $db->prepare("SELECT * FROM motives where label = '$motif'");
            $select_motif->execute();
            $motif_id = $select_motif->fetch(PDO::FETCH_OBJ);
            $motif_id = $motif_id->id;


            $insert = $db->prepare("INSERT INTO interventions (id_employee, id_client, date_inter, id_motive, time_inter) VALUES ('$emp_id', '$client_id',  '$date_inter', '$motif_id', '$heure')");
            $insert->execute();

          }
          else{
            echo "Veuillez remplir tous les champs";
          }
        }

        ?>



        <form action="" method="POST" enctype="multipart/form-data">

          <h3>Employé : </h3><select class="custom-select" name="employe" class="form-control" >
            <?php
            $select=$db->query("SELECT * FROM employees ORDER BY lastname");
            while ($s = $select->fetch(PDO::FETCH_OBJ)) {?>
              <option><?php echo $s->lastname;?></option>
              <?php
            }
            ?></select>

            <h3>Client    : </h3><select class="custom-select" name="client" class="form-control" >
              <?php
              $select=$db->query("SELECT * FROM clients ORDER BY lastname");
              while ($s = $select->fetch(PDO::FETCH_OBJ)) {?>
                <option><?php echo $s->lastname;?></option>
                <?php
              }
              ?></select>
              <h3>Motif       : </h3><select class="custom-select" name="motive" class="form-control" >
                <?php
                $select=$db->query("SELECT * FROM motives ORDER BY label");
                while ($s = $select->fetch(PDO::FETCH_OBJ)) {?>
                  <option><?php echo $s->label;?></option>
                  <?php
                }
                ?></select>
                <h3>Date     : </h3><input type="date" name="date_inter" class="form-control" >
                <h3>Heure     : </h3><input type="time" name="hour" class="form-control" >


                <br>
                <input type="submit" name="submit">

              </form>

              <?php

            }
  //************************************************************AFFICHAGE**************************************************
            else if ($_GET['action']=='modify'){
    // ce qu'il va se passer lorsque l'on va cliquer sur supprimer/modifier un produit
              ?>
              <br>
              <?php
              $select = $db->prepare("SELECT * FROM interventions ORDER BY date_inter DESC");
              $select->execute();
              ?>
              <table class="table">
                <tr>
                 <th>N° d'intervention</th>
                 <th>Employé</th>
                 <th>Client</th>
                 <th>Motif</th>
                 <th>Date</th>
                 <th>Status</th>
                 <th>Rapport</th>
                 <th></th>
                 <th></th>
               </tr>

               <?php
               while($s=$select->fetch(PDO::FETCH_OBJ)){

                $select_emp = $db->prepare("SELECT * FROM employees where id = '$s->id_employee'");
                $select_emp->execute();
                $emp_name = $select_emp->fetch(PDO::FETCH_OBJ);
                $emp_name = $emp_name->lastname;

                $select_client = $db->prepare("SELECT * FROM clients where id = '$s->id_client'");
                $select_client->execute();
                $client_name = $select_client->fetch(PDO::FETCH_OBJ);
                $client_name = $client_name->lastname;



                $select_motif = $db->prepare("SELECT * FROM motives where id = '$s->id_motive'");
                $select_motif->execute();
                $motif_label = $select_motif->fetch(PDO::FETCH_OBJ);
                $motif_label = $motif_label->label;

                if($s->pending == 1){
                  $pending = "Effectué";
                }
                else{
                  $pending = "En Attente";
                }

                ?>
                <tr>
                  <td><?php echo $s->id;?></td>
                  <td><?php echo $emp_name;?></td>
                  <td><?php echo $client_name;?></td>
                  <td><?php echo $motif_label;?></td>
                  <td><?php echo $s->date_inter;?></td>
                  <td><?php echo $pending;?></td>
                  <td><a href="?action=modrapport&amp;id=<?php echo $s->id; ?>">Voir le rapport</a></td>
                  <td><a href="?action=mod&amp;id=<?php echo $s->id; ?>">Modifier</a></td>
                  <td><a href="?action=del&amp;id=<?php echo $s->id; ?>">X</a></td>
                </tr>

                <?php

              }

              ?></table><?php

            }
  //************************************************************MODIFICATION************************************************************
            else if ($_GET['action']=='mod'){
    // ce qu'il va se passer lorsque l'on va cliquer sur modifier


              $id=$_GET['id'];
        // on récupère les données du produit dans une variable
              $select = $db->prepare("SELECT * FROM interventions WHERE id = $id");
              $select->execute();

              $intervention = $select->fetch(PDO::FETCH_OBJ);

        // on affiche ces données dans les champs




              ?>

              <form action="" method="POST" enctype="multipart/form-data">

                <?php

                $select_emp = $db->prepare("SELECT * FROM employees where id = '$intervention->id_employee'");
                $select_emp->execute();
                $emp_name = $select_emp->fetch(PDO::FETCH_OBJ);
                $emp_name = $emp_name->lastname;

                $select_client = $db->prepare("SELECT * FROM clients where id = '$intervention->id_client'");
                $select_client->execute();
                $client_name = $select_client->fetch(PDO::FETCH_OBJ);
                $client_name = $client_name->lastname;



                $select_motif = $db->prepare("SELECT * FROM motives where id = '$intervention->id_motive'");
                $select_motif->execute();
                $motif_label = $select_motif->fetch(PDO::FETCH_OBJ);
                $motif_label = $motif_label->label;
                ?>


                <h3>Employé : </h3><select class="custom-select" name="employe" class="form-control">
                  <?php
                  $select=$db->query("SELECT * FROM employees ORDER BY lastname");
                  while ($s = $select->fetch(PDO::FETCH_OBJ))
                    {if ($s->lastname == $emp_name){
                      ?>
                      <option selected="selected"><?php echo $s->lastname;?></option>
                      <?php
                    }
                    else{
                      ?>
                      <option><?php echo $s->lastname;?></option>
                      <?php
                    }
                  }
                  ?></select>

                  <h3>Client    : </h3><select class="custom-select" name="client" class="form-control">
                    <?php
                    $select=$db->query("SELECT * FROM clients ORDER BY lastname");
                    while ($s = $select->fetch(PDO::FETCH_OBJ)) {
                     if ($s->lastname == $client_name){
                      ?>
                      <option selected="selected"><?php echo $s->lastname;?></option>
                      <?php
                    }
                    else{
                      ?>
                      <option><?php echo $s->lastname;?></option>
                      <?php
                    }
                  }
                  ?></select>
                  <h3>Motif       : </h3><select class="form-control" class="custom-select" name="motive">
                    <?php
                    $select=$db->query("SELECT * FROM motives ORDER BY label");
                    while ($s = $select->fetch(PDO::FETCH_OBJ)) {
                      if ($s->label == $motif_label){
                        ?>
                        <option selected="selected"><?php echo $s->label;?></option>
                        <?php
                      }
                      else{
                        ?>
                        <option><?php echo $s->label;?></option>
                        <?php
                      }
                    }
                    ?></select>
                    <h3>Date     : </h3><input type="datetime" name="date_inter" class="form-control" value=<?php echo $intervention->date_inter ?>>



                    <br>

                    <input type="submit" name="submit" value = "Modifier" class="btn btn-warning" role="button">

                  </form>
                  <?php
    // on récupère les données passées en POST et on les utilise pour faire l'update en base du produit
                  if (isset($_POST['submit'])) {

      // on récupère dans des variables les informations produit
                    $employe     = $_POST['employe'];
                    $client      = $_POST['client'];
                    $date_inter  = $_POST['date_inter'];
                    $motif       = $_POST['motive'];



      // on vérifie que toutes les informations ont bien été renseignées
                    if($employe&&$client&&$date_inter&&$motif){

        //permet de récupérer l'id de l'employé, du client puis du motif afin de pouvoir alimenter la base
                      $select_emp = $db->prepare("SELECT * FROM employees where lastname = '$employe'");
                      $select_emp->execute();
                      $emp_id = $select_emp->fetch(PDO::FETCH_OBJ);
                      $emp_id = $emp_id->id;

                      $select_client = $db->prepare("SELECT * FROM clients where lastname = '$client'");
                      $select_client->execute();
                      $client_id = $select_client->fetch(PDO::FETCH_OBJ);
                      $client_id = $client_id->id;

                      $select_motif = $db->prepare("SELECT * FROM motives where label = '$motif'");
                      $select_motif->execute();
                      $motif_id = $select_motif->fetch(PDO::FETCH_OBJ);
                      $motif_id = $motif_id->id;

                      $update = $db->prepare("UPDATE interventions SET id_employee='$emp_id', id_client='$client_id', id_motive='$motif_id', date_inter='$date_inter' WHERE id=$id");
                      $update->execute();

                      ?><meta http-equiv="refresh" content="1;url=interventions.php?action=modify"/><?php

                    }
                    else{
                      echo "Veuillez remplir tous les champs";
                    }



                  }?>

                  <?php
                }
  //************************************************************SUPPRESSION************************************************************
                else if ($_GET['action']=='del'){
    // ce qu'il va se passer lorsque l'on va cliquer sur X
                  $id = $_GET['id'];
                  $del = $db->prepare("DELETE FROM interventions WHERE id=$id");
                  $del->execute();

                  ?><meta http-equiv="refresh" content="1;url=interventions.php?action=modify"/><?php

                }
  //************************************************************MODIFICATION STOCK***************************************************************
                else if ($_GET['action']=='modrapport'){

                  $id=$_GET['id'];
        // on récupère les données de l'intervention dans une variable
                  $select = $db->prepare("SELECT * FROM interventions WHERE id = $id");
                  $select->execute();

                  $intervention = $select->fetch(PDO::FETCH_OBJ);

        // on affiche ces données dans les champs
                  ?>
                  <form action="" method="POST">

                    <h3>Rapport :</h3>
                    <textarea name="report" rows="20" cols="200"><?php echo  $intervention->report; ?></textarea>
                    <input type="submit" name="submit" value = "Modifier le rapport" class="btn btn-warning" role="button">

                  </form>
                  <?php
    // on récupère les données passées en POST et on les utilise pour faire l'update en base du produit
                  if (isset($_POST['submit'])) {

                    $report = $_POST['report'];

                    $update = $db->prepare("UPDATE interventions SET report='$report' WHERE id=$id");
                    $update->execute();
                    ?><br><br><meta http-equiv="refresh" content="1;url=interventions.php?action=modify"/><?php

                  }

                }



              }
            }?>
          </div>
