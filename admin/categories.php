
<?php

require_once('header.php');?>


<a href="?action=addcat" class="btn btn-warning" role="button">Ajouter</a>
<a href="?action=modifycat" class="btn btn-warning" role="button">Modifier / Supprimer </a><br>


<?php
// vérification qu'on a bien rentré un username
if(isset($_SESSION['username'])){
  // vérification si il y a un code action
  if(isset($_GET['action'])){
    // si le code action est en mode 'ajout'

  if ($_GET['action']=='addcat'){

    if(isset($_POST['submit'])){
      $label = $_POST['label'];
      if($label){
        $insert = $db->prepare("INSERT INTO categories (label) VALUES ('$label')");
        $insert->execute();

      }
      else{
        echo "Veuillez renseigner un nom de catégorie";
      }
    }

    ?>
    <br>
    <div class="full_cart">
      <h2>Ajout d'une catégorie</h2>
    <form action="" method="post">
    <h3>Nom catégorie :</h3><input type="text" name="label" class="form-control"><br>
    <input type="submit" name="submit" class="btn btn-warning"  value="Ajouter">
    </form>
    </div>
    <?php

  }
//************************************************************CATEGORIES************************************************************
//************************************************************AFFICHER***************************************************************
  else if ($_GET['action']=='modifycat'){

        // ce qu'il va se passer lorsque l'on va cliquer sur supprimer/modifier une catégorie
        // on commence par afficher la liste
        ?>
        <br>
        <?php
        $select = $db->prepare("SELECT * FROM categories");
        $select->execute();
        ?>
        <div class="full_cart">
        <table class="table">
          <tr>
              <th>Catégorie</th>
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
          <td><a href="?action=modcat&amp;id=<?php echo $s->id; ?>">Modifier</a></td>
          <td><a href="?action=delcat&amp;id=<?php echo $s->id; ?>">X</a></td>

          </tr>
          <?php

        }
        ?>
          </table>
        </div>
        <?php

  }
//************************************************************CATEGORIES************************************************************
//************************************************************MODIFICATION************************************************************
  else if ($_GET['action']=='modcat'){
    // ce qu'il va se passer lorsque l'on va cliquer sur modifier


        $id=$_GET['id'];
        // on récupère les données du produit dans une variable
        $select = $db->prepare("SELECT * FROM categories WHERE id = $id");
        $select->execute();

        $categorie = $select->fetch(PDO::FETCH_OBJ);

        // on affiche ces données dans les champs
?>
          <div class="full_cart">
          <h2>Modifier une catégorie</h2>
          <form action="" method="post">
          <h3>Nom catégorie :</h3><input type="text" name="label" class="form-control" value="<?php echo $categorie->label; ?>"><br>
          <input type="submit" name="submit" class="btn btn-warning"  value="Modifier">
          </form>
          </div>

    <?php
    // on récupère les données passées en POST et on les utilise pour faire l'update en base du produit
    if (isset($_POST['submit'])) {

        $label = $_POST['label'];

        //echo $label.'   '.$description.'   '.$price;
        $update = $db->prepare("UPDATE categories SET label='$label' WHERE id=$id");
        $update->execute();


    }?>



    <?php

  }
//************************************************************CATEGORIES************************************************************
//************************************************************SUPPRESSION***********************************************************
  else if ($_GET['action']=='delcat'){
    // ce qu'il va se passer lorsque l'on va cliquer sur X
        $id = $_GET['id'];
        $del = $db->prepare("DELETE FROM categories WHERE id=$id");
        $del->execute();

        ?><meta http-equiv="refresh" content="1;url=categories.php?action=modifycat"/><?php

  }

}}
