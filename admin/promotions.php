
<?php

require_once('header.php');?>

<a href="?action=addpromo" class="btn btn-warning" role="button">Ajouter </a>
<a href="?action=modifypromo" class="btn btn-warning" role="button">Modifier / Supprimer</a><br>


<?php
// vérification qu'on a bien rentré un username
if(isset($_SESSION['username'])){
  // vérification si il y a un code action
  if(isset($_GET['action'])){
    // si le code action est en mode 'ajout'

    //************************************************************PROMOTIONS************************************************************
//************************************************************AJOUTER***************************************************************
   if ($_GET['action']=='addpromo'){

    if(isset($_POST['submit'])){
      $label = $_POST['label'];
      if($label){
        $insert = $db->prepare("INSERT INTO promotions (label) VALUES ('$label')");
        $insert->execute();

      }
      else{
        echo "Veuillez renseigner une valeur";
      }
    }

        ?>
        <div class="full_cart">
    <h2>Ajout d'une promotion</h2>
    <form action="" method="post">
    <h3>Pourcentage promotion :</h3><input type="text" name="label" class="form-control"><br>
    <input type="submit" name="submit" value="Ajouter" class="btn btn-warning" role="button">
    </form>
  </div>

    <?php


  }
//************************************************************PROMOTIONS************************************************************
//************************************************************AFFICHER***************************************************************
  else if ($_GET['action']=='modifypromo'){

            // ce qu'il va se passer lorsque l'on va cliquer sur supprimer/modifier une promotion
        // on commence par afficher la liste
        ?>
<br>
        <?php
        $select = $db->prepare("SELECT * FROM promotions ORDER BY label");
        $select->execute();
        ?>
        <div class="full_cart">
        <table class="table">
          <tr>
              <th>Promotions</th>
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
          <td><a href="?action=modpromo&amp;id=<?php echo $s->id; ?>">Modifier</a></td>
          <td><a href="?action=delpromo&amp;id=<?php echo $s->id; ?>">X</a></td>
        </tr>
          <?php

        }
        ?>
          </table>
        </div>
        <?php

  }
//************************************************************PROMOTIONS************************************************************
//************************************************************MODIFICATION************************************************************
  else if ($_GET['action']=='modpromo'){
    // ce qu'il va se passer lorsque l'on va cliquer sur modifier


        $id=$_GET['id'];
        // on récupère les données du produit dans une variable
        $select = $db->prepare("SELECT * FROM promotions WHERE id = $id");
        $select->execute();

        $promo = $select->fetch(PDO::FETCH_OBJ);

        // on affiche ces données dans les champs
?>
        <div class="full_cart">
          <h2>Modification d'une promotion</h2>
        <form action="" method="POST">

          <h3>Pourcentage promotion :</h3><input class="form-control" type="text" name="label" value="<?php echo $promo->label; ?>"><br>
          <input type="submit" name="submit" value = "Modifier" class="btn btn-warning" role="button">

        </form>
      </div>
    <?php
    // on récupère les données passées en POST et on les utilise pour faire l'update en base du produit
    if (isset($_POST['submit'])) {

        $label = $_POST['label'];

        //echo $label.'   '.$description.'   '.$price;
        $update = $db->prepare("UPDATE promotions SET label='$label' WHERE id=$id");
        $update->execute();

    }?>

<?php
  }
//************************************************************PROMOTIONS************************************************************
//************************************************************SUPPRESSION************************************************************
  else if ($_GET['action']=='delpromo'){
    // ce qu'il va se passer lorsque l'on va cliquer sur X
        $id = $_GET['id'];
        $del = $db->prepare("DELETE FROM promotions WHERE id=$id");
        $del->execute();


        ?><meta http-equiv="refresh" content="1;url=promotions.php?action=modifypromo"/><?php

  }


  }
}
