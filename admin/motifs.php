
<?php

require_once('header.php');?>

<a href="?action=addmotif" class="btn btn-warning" role="button">Ajouter </a>
<a href="?action=modifymotif" class="btn btn-warning" role="button">Modifier / Supprimer</a><br>

<div class="full_cart">

  <?php
// vérification qu'on a bien rentré un username
  if(isset($_SESSION['username'])){
  // vérification si il y a un code action
    if(isset($_GET['action'])){
    // si le code action est en mode 'ajout'



    //************************************************************MOTIFS************************************************************
//************************************************************AJOUTER***************************************************************
     if ($_GET['action']=='addmotif'){

      if(isset($_POST['submit'])){
        $label = $_POST['label'];
        if($label){
          $insert = $db->prepare("INSERT INTO motives (label) VALUES ('$label')");
          $insert->execute();

        }
        else{
          echo "Veuillez renseigner une valeur";
        }
      }

      ?>
      <h2>Ajout d'un motif</h2>
      <form action="" method="post">
        <h3>Label :</h3><input type="text" name="label">
        <input type="submit" name="submit" value="Ajouter">
      </form>

      <?php


    }
//************************************************************MOTIFS************************************************************
//************************************************************AFFICHER***************************************************************
    else if ($_GET['action']=='modifymotif'){

            // ce qu'il va se passer lorsque l'on va cliquer sur supprimer/modifier un motif
        // on commence par afficher la liste
      ?>
      <br>
      <?php
      $select = $db->prepare("SELECT * FROM motives ORDER BY label");
      $select->execute();
      ?>
      <table class="table">
        <tr>
          <th>Motifs</th>
          <th>Action </th>
          <th>Supprimer</th>
        </tr>

        <?php

        while($s=$select->fetch(PDO::FETCH_OBJ)){
          ?>
          <tr>
            <td>
              <?php
              echo $s->label;
              ?>
            </td>
            <td><a href="?action=modmotif&amp;id=<?php echo $s->id; ?>">Modifier</a></td>
            <td><a href="?action=delmotif&amp;id=<?php echo $s->id; ?>">X</a></td>
          </tr>
          <?php

        }
        ?>
      </table>
      <?php

    }
//************************************************************MOTIFS************************************************************
//************************************************************MODIFICATION************************************************************
    else if ($_GET['action']=='modmotif'){
    // ce qu'il va se passer lorsque l'on va cliquer sur modifier


      $id=$_GET['id'];
        // on récupère les données du produit dans une variable
      $select = $db->prepare("SELECT * FROM motives WHERE id = $id");
      $select->execute();

      $motif = $select->fetch(PDO::FETCH_OBJ);

        // on affiche ces données dans les champs
      ?>
      <form action="" method="POST">

        <h3>Label :</h3><input type="text" name="label" value="<?php echo $motif->label; ?>">
        <input type="submit" name="submit" value = "Modifier">

      </form>
      <?php
    // on récupère les données passées en POST et on les utilise pour faire l'update en base du produit
      if (isset($_POST['submit'])) {

        $label = $_POST['label'];

        //echo $label.'   '.$description.'   '.$price;
        $update = $db->prepare("UPDATE motives SET label='$label' WHERE id=$id");
        $update->execute();

      }?>

      <?php
    }
//************************************************************MOTIFS************************************************************
//************************************************************SUPPRESSION************************************************************
    else if ($_GET['action']=='delmotif'){
    // ce qu'il va se passer lorsque l'on va cliquer sur X
      $id = $_GET['id'];
      $del = $db->prepare("DELETE FROM motives WHERE id=$id");
      $del->execute();


      ?><meta http-equiv="refresh" content="1;url=motives.php?action=modifymotif"/><?php

    }


  }
}?>
</div>
