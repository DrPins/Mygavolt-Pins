
<style type="text/css">

body{
      background-color: #2387a8;
      color: white;
      padding: 40px;
}

a{
      color: black;
}

</style>

<?php
session_start();
$timestamp   = time();

        try{
            $db = new PDO('sqlsrv:Server=wserver.area42.fr;Database=mygavoltpins', 'mygavolt', 'k2Y*bswsaFyss3j7*Hsf',array(
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8',
                PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING
            ));

        }catch(PDOException $e){
            die('<h1>Impossible de se connecter</h1>');
        }?>


	      <!-- Librairie bootstrap + jquery -->
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	  <link rel="stylesheet" type="text/css" href="style/style.css">


<body>
<h1>Mygavolt Admin Panel</h1>
<h4> User : <?php echo $_SESSION['username'] ?></h4>
<?php
// vérification qu'on a bien rentré un username
if(isset($_SESSION['username'])){
	// vérification si il y a un code action
	if(isset($_GET['action'])){
		// si le code action est en mode 'ajout'

//******************************************************************AJOUT******************************************************************
	if ($_GET['action']=='add'){

		if(isset($_POST['submit'])){
			// on récupère dans des variables les informations produit
			$label       = $_POST['label'];
			$description = $_POST['description'];
			$price       = $_POST['price'];
			$category    = $_POST['category'];
			$promotion   = $_POST['promotion'];
			$img         = $_FILES['img']['name'];
			$img_tmp     = $_FILES['img']['tmp_name'];
			$tva         = $_POST['tva'];


			if(!empty($img_tmp)){
				$img= explode('.', $img);
				$image_ext= end($img);
				print_r($image_ext);

				//on vérifie que le fichier à la bonne extension
				if(in_array(strtolower($image_ext), array('png', 'jpg', 'jpeg'))===false){

					echo "Veuillez rentrer une image ayant pour extension : png, jpg ou jpeg";
				}
				else{
					// on con
					$image_size=getimagesize($img_tmp);
					if($image_size['mime']=='image/jpeg'){
						$image_src = imagecreatefromjpeg($img_tmp);
					}
					else if($image_size['mime']=='image/png'){
						$image_src = imagecreatefrompng($img_tmp);
					}
					else {
						$image_src = false;
						echo"Veuillez rentrer une image valide";
					}

					if ($image_src!==false){
						$image_width=200;
						if($image_size[0]==$image_width){
							$image_finale = $image_src;
						}
						else{
							$new_width[0] = $image_width;
							$new_height[1] = 200 ;
							$image_finale = imagecreatetruecolor($new_width[0], $new_height[1]);

							imagecopyresampled($image_finale, $image_src, 0, 0, 0, 0, $new_width[0], $new_height[1], $image_size[0], $image_size[1]);
						}

						imagejpeg($image_finale, 'imgs/'.$timestamp.'.jpg');
					}
				}



			}
			else{
				echo'Veuilliez rentrer une image';
			}

			// on vérifie que toutes les informations ont bien été renseignées
			if($label&&$description&&$price&&$img){
				//permet de récupérer l'id de la categorie puis de la promotion afin de pouvoir alimenter la base
				$select_cat = $db->prepare("SELECT * FROM categories where label = '$category'");
				$select_cat->execute();
				$cat_id = $select_cat->fetch(PDO::FETCH_OBJ);
				$cat_id = $cat_id->id;

				$select_promo = $db->prepare("SELECT * FROM promotions where label = '$promotion'");
				$select_promo->execute();
				$promo_id = $select_promo->fetch(PDO::FETCH_OBJ);
				$promo_id = $promo_id->id;

				$insert = $db->prepare("INSERT INTO products (label, description, price, id_promotion, id_category, tva, nom_img) VALUES ('$label', '$description', '$price', '$promo_id', '$cat_id', '$tva', '$timestamp')");
				$insert->execute();

			}
			else{
				echo "Veuillez remplir tous les champs";
			}
		}

		?>



		<form action="" method="POST" enctype="multipart/form-data">

			<h3>Nom du produit : </h3><input type="text" class="form-control"  name="label">
			<h3>Description    : </h3><textarea class="form-control"  name="description"></textarea>
			<h3>Prix HT        : </h3><input type="text" class="form-control"  name="price">
			<h3>Taux TVA      : </h3><input type="text" name="tva" class="form-control"  value="19.6">
			<h3>Promotion      : </h3><select class="custom-select" name="promotion">
				<?php
					$select=$db->query("SELECT * FROM promotions ORDER BY label");
					while ($s = $select->fetch(PDO::FETCH_OBJ)) {?>
						<option><?php echo $s->label;?></option>

						<?php
					}
				?></select>
			<h3>Catégorie      : </h3><select class="custom-select" name="category">
				<?php
					$select=$db->query("SELECT * FROM categories ORDER BY label");
					while ($s = $select->fetch(PDO::FETCH_OBJ)) {?>
						<option><?php echo $s->label;?></option>

						<?php
					}
				?>

			</select>
			<h3>Photo          : </h3><input type="file" name="img" class="custom-file-input">

			<input type="submit" name="submit">

		</form>

	<?php

	}
	//************************************************************AFFICHAGE************************************************************
	else if ($_GET['action']=='modify'){
		// ce qu'il va se passer lorsque l'on va cliquer sur supprimer/modifier un produit
				?>
				<h5> Produits</h5>
				<?php
				$select = $db->prepare("SELECT * FROM products");
				$select->execute();
				?>
				<table class="table">
					<tr>
       					<th>Nom</th>
       					 <th>Prix HT</th>
      					 <th>Catégorie</th>
      					 <th>Promotion</th>
      					 <th>Stock</th>
      					 <th></th>
      					 <th></th>
  					</tr>

					<?php
				while($s=$select->fetch(PDO::FETCH_OBJ)){


				$select_promo = $db->prepare("SELECT * FROM promotions where id = '$s->id_promotion'");
				$select_promo->execute();
				$promo_label = $select_promo->fetch(PDO::FETCH_OBJ);
				$promo_label = $promo_label->label;


				$select_cat = $db->prepare("SELECT * FROM categories where id = '$s->id_category'");
				$select_cat->execute();
				$cat_label = $select_cat->fetch(PDO::FETCH_OBJ);
				$cat_label = $cat_label->label;
				?>
					<tr>
						<td><?php echo $s->label;?></td>
						<td><?php echo $s->price;?></td>
						<td><?php echo $cat_label;?></td>
						<td><?php echo $promo_label;?></td>
						<td><a href="?action=modstock&amp;id=<?php echo $s->id; ?>"><?php echo $s->stock;?></a></td>
						<td><a href="?action=mod&amp;id=<?php echo $s->id; ?>">Modifier</a></td>
						<td><a href="?action=del&amp;id=<?php echo $s->id; ?>">X</a></td>



					<?php

				}

				?></table><?php

	}
	//************************************************************MODIFICATION************************************************************
	else if ($_GET['action']=='mod'){
		// ce qu'il va se passer lorsque l'on va cliquer sur modifier


				$id=$_GET['id'];
				// on récupère les données du produit dans une variable
				$select = $db->prepare("SELECT * FROM products WHERE id = $id");
				$select->execute();

				$produit = $select->fetch(PDO::FETCH_OBJ);

				// on affiche ces données dans les champs




?>

				<form action="" method="POST" enctype="multipart/form-data">

				<?php
				$select_promo = $db->prepare("SELECT * FROM promotions where id = '$produit->id_promotion'");
				$select_promo->execute();
				$promo_label = $select_promo->fetch(PDO::FETCH_OBJ);
				$promo_label = $promo_label->label;


				$select_cat = $db->prepare("SELECT * FROM categories where id = '$produit->id_category'");
				$select_cat->execute();
				$cat_label = $select_cat->fetch(PDO::FETCH_OBJ);
				$cat_label = $cat_label->label;
				?>

			<h3>Nom du produit : </h3><input type="text" name="label" value="<?php echo $produit->label; ?>">
			<h3>Description    : </h3><textarea name="description" ><?php echo $produit->description; ?></textarea>
			<h3>Prix HT        : </h3><input type="text" name="price" value="<?php echo $produit->price; ?>">
			<h3>Taux TVA       : </h3><input type="text" name="tva" value="<?php echo $produit->tva; ?>">
			<h3>Promotion      : </h3><select name="promotion" >
				<?php
					$select=$db->query("SELECT * FROM promotions");
					while ($s = $select->fetch(PDO::FETCH_OBJ)) {

						if ($s->label == $promo_label){
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
			<h3>Catégorie      : </h3><select name="category">
				<?php
					$select=$db->query("SELECT * FROM categories");
					while ($s = $select->fetch(PDO::FETCH_OBJ)) {
						if ($s->label == $cat_label){
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
				?>

			</select>
			<h3>Photo          : </h3><input type="file" name="img" value="<?php echo 'imgs/'.$produit->nom_img.'.jpg'; ?>">

			<input type="submit" name="submit" value = "Modifier">

		</form>
		<?php
		// on récupère les données passées en POST et on les utilise pour faire l'update en base du produit
		if (isset($_POST['submit'])) {

			// on récupère dans des variables les informations produit
			$label       = $_POST['label'];
			$description = $_POST['description'];
			$price       = $_POST['price'];
			$category    = $_POST['category'];
			$promotion   = $_POST['promotion'];
			$tva         = $_POST['tva'];

			$img         = $_FILES['img']['name'];
			$img_tmp     = $_FILES['img']['tmp_name'];


				// Si il y a une image, on la reformate et on l'enregistre
				if(!empty($img_tmp)){
				$img= explode('.', $img);
				$image_ext= end($img);


				if(in_array(strtolower($image_ext), array('png', 'jpg', 'jpeg'))===false){
					//on vérifie que le fichier à la bonne extension
					echo "Veuillez rentrer une image ayant pour extension : png, jpg ou jpeg";
				}
				else{
					// on con
					$image_size=getimagesize($img_tmp);
					if($image_size['mime']=='image/jpeg'){
						$image_src = imagecreatefromjpeg($img_tmp);
					}
					else if($image_size['mime']=='image/png'){
						$image_src = imagecreatefrompng($img_tmp);
					}
					else {
						$image_src = false;
						echo"Veuillez rentrer une image valide";
					}

					if ($image_src!==false){
						$image_width=200;
						if($image_size[0]==$image_width){
							$image_finale = $image_src;
						}
						else{
							$new_width[0] = $image_width;
							$new_height[1] = 200 ;
							$image_finale = imagecreatetruecolor($new_width[0], $new_height[1]);

							imagecopyresampled($image_finale, $image_src, 0, 0, 0, 0, $new_width[0], $new_height[1], $image_size[0], $image_size[1]);
						}

						imagejpeg($image_finale, 'imgs/'.$timestamp.'.jpg');
					}
				}



			}
			//else{
			//	echo'Veuillez rentrer une image';
			//}

			// on vérifie que toutes les informations ont bien été renseignées
			if($label&&$description&&$price){
				//permet de récupérer l'id de la categorie puis de la promotion afin de pouvoir alimenter la base
				$select_cat = $db->prepare("SELECT * FROM categories where label = '$category'");
				$select_cat->execute();
				$cat_id = $select_cat->fetch(PDO::FETCH_OBJ);
				$cat_id = $cat_id->id;

				$select_promo = $db->prepare("SELECT * FROM promotions where label = '$promotion'");
				$select_promo->execute();
				$promo_id = $select_promo->fetch(PDO::FETCH_OBJ);
				$promo_id = $promo_id->id;

				$update = $db->prepare("UPDATE products SET label='$label', description='$description', price='$price', id_promotion='$promo_id', id_category='$cat_id', tva='$tva', nom_img ='$timestamp' WHERE id=$id");
				$update->execute();

				?><meta http-equiv="refresh" content="1;url=admin.php?action=modify"/><?php

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
				$del = $db->prepare("DELETE FROM products WHERE id=$id");
				$del->execute();

				?><meta http-equiv="refresh" content="1;url=admin.php?action=modify"/><?php

	}

//************************************************************CATEGORIES************************************************************
//************************************************************AJOUTER***************************************************************
	else if ($_GET['action']=='addcat'){

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
		<h2>Ajout d'une categorie</h2>
		<form action="" method="post">
		<h3>Nom catégorie :</h3><input type="text" name="label">
		<input type="submit" name="submit" value="Ajouter">
		</form>

		<?php

	}
//************************************************************CATEGORIES************************************************************
//************************************************************AFFICHER***************************************************************
	else if ($_GET['action']=='modifycat'){

				// ce qu'il va se passer lorsque l'on va cliquer sur supprimer/modifier une catégorie
				// on commence par afficher la liste
				?>

				<?php
				$select = $db->prepare("SELECT * FROM categories");
				$select->execute();
				?>
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

				<form action="" method="POST">

					<h3>Nom catégorie :</h3><input type="text" name="label" value="<?php echo $categorie->label; ?>">
					<input type="submit" name="submit" value = "Modifier">

				</form>
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
//************************************************************SUPPRESSION************************************************************
	else if ($_GET['action']=='delcat'){
		// ce qu'il va se passer lorsque l'on va cliquer sur X
				$id = $_GET['id'];
				$del = $db->prepare("DELETE FROM categories WHERE id=$id");
				$del->execute();

				?><meta http-equiv="refresh" content="1;url=admin.php?action=modifycat"/><?php

	}

//************************************************************PROMOTIONS************************************************************
//************************************************************AJOUTER***************************************************************
	else if ($_GET['action']=='addpromo'){

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
		<h2>Ajout d'une promotion</h2>
		<form action="" method="post">
		<h3>Pourcentage de réduction :</h3><input type="text" name="label">
		<input type="submit" name="submit" value="Ajouter">
		</form>

		<?php


	}
//************************************************************PROMOTIONS************************************************************
//************************************************************AFFICHER***************************************************************
	else if ($_GET['action']=='modifypromo'){

						// ce qu'il va se passer lorsque l'on va cliquer sur supprimer/modifier une promotion
				// on commence par afficher la liste
				?>
				<h5> Catégories</h5>
				<?php
				$select = $db->prepare("SELECT * FROM promotions ORDER BY label");
				$select->execute();
				?>
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
					<td><a href="?action=modpromo&amp;id=<?php echo $s->id; ?>">Modifier</a></td>
					<td><a href="?action=delpromo&amp;id=<?php echo $s->id; ?>">X</a></td>
				</tr>
					<?php

				}
				?>
					</table>
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
				<form action="" method="POST">

					<h3>Pourcentage promotion :</h3><input type="text" name="label" value="<?php echo $promo->label; ?>">
					<input type="submit" name="submit" value = "Modifier">

				</form>
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


				?><meta http-equiv="refresh" content="1;url=admin.php?action=modifypromo"/><?php

	}

//************************************************************MODIFICATION STOCK***************************************************************
	else if ($_GET['action']=='modstock'){

		$id=$_GET['id'];
				// on récupère les données du produit dans une variable
				$select = $db->prepare("SELECT * FROM products WHERE id = $id");
				$select->execute();

				$prod = $select->fetch(PDO::FETCH_OBJ);

				// on affiche ces données dans les champs
				?>
				<form action="" method="POST">

					<h3>Stock :</h3><input type="text" name="stock" value="<?php echo $prod->stock; ?>">
					<input type="submit" name="submit" value = "Modifier stock">

				</form>
		<?php
		// on récupère les données passées en POST et on les utilise pour faire l'update en base du produit
		if (isset($_POST['submit'])) {

				$stock = $_POST['stock'];

				$update = $db->prepare("UPDATE products SET stock='$stock' WHERE id=$id");
				$update->execute();
				?><meta http-equiv="refresh" content="1;url=admin.php?action=modify"/><?php

		}

	}


//************************************************************ERREUR***************************************************************
	else {
		die('une erreure s \'est produite : action inconnue');
	}



}
}
else{
	header('Location: admin/index.php');
}

?>



<br>
<a href="?action=add">Ajouter un produit</a><br>
<a href="?action=modify">Modifier / Supprimer un produit</a><br>
<br>
<a href="?action=addcat">Ajouter une catégorie</a><br>
<a href="?action=modifycat">Modifier / Supprimer une catégorie</a><br>
<br>
<a href="?action=addpromo">Ajouter une promotion</a><br>
<a href="?action=modifypromo">Modifier / Supprimer une promotion</a><br>
</body>
