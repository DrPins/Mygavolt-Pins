<?php

require_once('includes/header.php');


$select = $db->query("SELECT *  FROM categories");
?>
<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="#">CATEGORIES</a>
    </div>
    <ul class="nav navbar-nav">
    	<?php while($s = $select->fetch(PDO::FETCH_OBJ)){ ?>
      <li ><a href="?category=<?php echo $s->id;?> "><?php echo $s->label;?></a></li>
      <?php } ?>
    </ul>
  </div>
</nav>





<?php


//####################################################################DETAIL PRODUIT######################################################################################
// dans le cas où l'on veut voir les détails du produit
if(isset($_GET['show'])){
	// récupération de l'ID du produit
	$prod_id = $_GET['show'];


	// Récupération des données du produit en base
	$select = $db->prepare("SELECT * FROM products where id = '$prod_id'");
	$select->execute();
	$s = $select->fetch(PDO::FETCH_OBJ);

	// récuprétaion en varaible d'id du  pourcentage de promo
	$pourcentage_id = $s->promotion;

	// récupération de l'objet promo
	$promo = $db->prepare("SELECT * FROM promotions where id = '$pourcentage_id'");
	$promo->execute();
	$p = $promo->fetch(PDO::FETCH_OBJ);?>

	<div class="produit-box">
		<!-- Affichage d'un produit dans une div -->
		<h1><?php echo $s->label;?></h1>
		<?php
		//Si l'id de promotion du produit est différent de 0 alors on calcule la remise
		//on barre le prix initial et on affiche le %age de réduction
		if ($p->label != 0){
			$prix_a_afficher = number_format($s->price *(100 - $p->label)/100, 2, ',', ' ');
			?><h5 class="prix_barre"><?php echo $s->price;?>€HT</h5>
			<h4 class="promotion_couleur"> - <?php echo $p->label;?>%</h4>
			<?php
		}
		// sinon on affiche juste le prix
		else
		{
			$prix_a_afficher = $s->price;
		}?>

		<h4><?php echo $prix_a_afficher;?>€ HT</h4>
		<?php
		// Description complète (le 2ème paramètre represente le nombre de caractère avant d'aller à la ligne)
		$final_description = wordwrap($s->description,50, '<br/>', true);?>
		<p><?php echo $final_description;?></p>

		<?php
		// Vérification du stock, si le stock est positif on peut mettre le produit dans le panier
		if($s->stock != 0){
			?><a href="panier.php?action=ajoutProd&amp;i=<?php echo $s->id;?>&amp;l=<?php echo $s->label;?>&amp;p=<?php echo $prix_a_afficher;?>&amp;t=<?php echo $s->tva;?>&amp;q=1"><input type="button" value="Ajouter au panier"/></a><?php
		}
		// Si il est négatif, on affiche la rupture de stock sans lien vers le panier
		else{
			?><h4>Rupture de Stock</h4><?php
			}
			?>

		<img src="admin/imgs/<?php echo $s->nom_img?>.jpg" class="img-fluid" alt="Responsive image">
	</div>
		<?php
//####################################################################FIN - DETAIL PRODUIT######################################################################################
}

else{

	//####################################################################PRODUITS PAR CATEGORIE####################################################################################
	// Si une catégories est selectionnée, on affiche les produits correspondant
	if(isset($_GET['category'])){

	 // Affiche tous les produits

		// nombre de caratère dans la description courte
		$lenght = 60;

		// récupération de l'id de la catégorie
		$cat = $_GET['category'];
		// récupération de tous les produit de la catégorie selectionnée
		$select = $db->prepare("SELECT * FROM products where id_category = '$cat'");
		$select->execute();

			//tant qu'il y a des objets dans ce qui a été récupéré par la requete
			//on affiche la meme chose que pour le détail produit
		?>
		<div class="container">
		    <div id="products" class="row list-group"><?php
						while($s=$select->fetch(PDO::FETCH_OBJ)){?>

						<?php


						$pourcentage_id = $s->id_promotion;

						$promo = $db->prepare("SELECT * FROM promotions where id = '$pourcentage_id'");
						$promo->execute();

						$p = $promo->fetch(PDO::FETCH_OBJ);

						// Récupération de la descritpion raccourcie
						$short_description = substr($s->description, 0, $lenght);
						// à la ligne automatiquement après n caractères
						$final_description = wordwrap($short_description,25, '<br/>', true);
						?>

		        <div class="item  col-xs-4 col-lg-4">
		            <div class="thumbnail">
		                <img class="group list-group-image" src="admin/imgs/<?php echo $s->nom_img?>.jpg"  alt="<?php echo $s->label?>" />
		                <div class="caption">
		                    <h4 class="group inner list-group-item-heading">
		                        <?php echo $s->label;?></h4>
		                    <p class="group inner list-group-item-text">
		                        <?php echo $final_description;?><a href="?show=<?php echo $s->id;?>"> plus ...</a></p>
		                    <div class="row">
		                        <div class="col-xs-12 col-md-6">
		                            <p class="lead">
		                                <?php

																			if ($p->label != 0){
																				$prix_a_afficher = number_format($s->price *(100 - $p->label)/100, 2, ',', ' ');
																				?><h5 class="prix_barre"><?php echo $s->price;?>€ HT</h5>
																				<h4 class="promotion_couleur"> - <?php echo $p->label;?>%</h4>
																				<?php
																			}
																			else
																			{
																				$prix_a_afficher = $s->price;
																		}?>
																	<?php echo $prix_a_afficher;?>€ HT
																</p>
		                        </div>
		                        <div class="col-xs-12 col-md-6">
		                            <?php
																		if($s->stock != 0){
																			?><a  class="btn btn-success" href="panier.php?action=ajoutProd&amp;i=<?php echo $s->id;?>&amp;l=<?php echo $s->label;?>&amp;p=<?php echo $prix_a_afficher;?>&amp;t=<?php echo $s->tva;?>&amp;q=1">Ajouter au panier</a>	<?php
																		}
																		else{
																			?><h4>Rupture de Stock</h4><?php
																		}
																?>
		                        </div>
		                    </div>
		                </div>
		            </div>
		        </div>
<?php

						}// fin de la boucle while?>

			   </div>
			</div>

<?php

	}

	//####################################################################FIN PRODUITS PAR CATEGORIE####################################################################################

	//####################################################################CATEGORIE#####################################################################################################
		// si aucun get
			else{

	 // Affiche tous les produits

		// nombre de caratère dans la description courte
		$lenght = 60;

		// récupération de l'id de la catégorie

		// récupération de tous les produit de la catégorie selectionnée
		$select = $db->prepare("SELECT * FROM products ");
		$select->execute();
		?>
		<div class="container">
		    <div id="products" class="row list-group"><?php
						while($s=$select->fetch(PDO::FETCH_OBJ)){?>

						<?php
						$pourcentage_id = $s->id_promotion;

						$promo = $db->prepare("SELECT * FROM promotions where id = '$pourcentage_id'");
						$promo->execute();

						$p = $promo->fetch(PDO::FETCH_OBJ);

						// Récupération de la descritpion raccourcie
						$short_description = substr($s->description, 0, $lenght);
						// à la ligne automatiquement après n caractères
						$final_description = wordwrap($short_description,25, '<br/>', true);
						?>

		        <div class="item  col-xs-4 col-lg-4">
		            <div class="thumbnail">
		                <img class="group list-group-image" src="admin/imgs/<?php echo $s->nom_img?>.jpg"  alt="<?php echo $s->label?>" />
		                <div class="caption">
		                    <h4 class="group inner list-group-item-heading">
		                        <?php echo $s->label;?></h4>
		                    <p class="group inner list-group-item-text">
		                        <?php echo $final_description;?><a href="?show=<?php echo $s->id;?>"> plus ...</a></p>
		                    <div class="row">
		                        <div class="col-xs-12 col-md-6">
		                            <p class="lead">
		                                <?php
																			if ($p->label != 0){
																				$prix_a_afficher = number_format($s->price *(100 - $p->label)/100, 2, ',', ' ');
																				?><h5 class="prix_barre"><?php echo $s->price;?>€ HT</h5>
																				<h4 class="promotion_couleur"> - <?php echo $p->label;?>%</h4>
																				<?php
																			}
																			else
																			{
																				$prix_a_afficher = $s->price;
																		}?>
																	<?php echo $prix_a_afficher;?>€ HT
																</p>
		                        </div>
		                        <div class="col-xs-12 col-md-6">
		                            <?php
																		if($s->stock != 0){
																			?><a  class="btn btn-success" href="panier.php?action=ajoutProd&amp;i=<?php echo $s->id;?>&amp;l=<?php echo $s->label;?>&amp;p=<?php echo $prix_a_afficher;?>&amp;t=<?php echo $s->tva;?>&amp;q=1">Ajouter au panier</a>	<?php
																		}
																		else{
																			?><h4>Rupture de Stock</h4><?php
																		}
																?>
		                        </div>
		                    </div>
		                </div>
		            </div>
		        </div>
<?php

						}// fin de la boucle while?>

			   </div>
			</div><?php
			}
	//####################################################################FIN CATEGORIE#################################################################################################
	}

/*

*/
require_once('includes/sidebar.php');
require_once('includes/footer.php');

?>
