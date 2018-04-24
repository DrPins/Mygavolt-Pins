<?php
require_once 'includes/header.php';
require_once 'includes/functions_panier.php';
require_once 'includes/paypal.php';
?>
<!--<a href="?deletepanier=true">Supprimer le panier</a>-->
<?php
$erreur = false;
creationPanier();
// si il est existe $_POST['action'] alors $action = $_POST['action'] sinon si il existe $_GET['action'] alors il sera égal à $_GET['action']
$action = (isset($_POST['action']) ? $_POST['action'] : (isset($_GET['action']) ? $_GET['action'] : null));
if ($action !== null) {
    if (!in_array($action, array('ajoutProd', 'supprimerProd', 'rafraichir'))) {
        $erreur = true;
    }
    $i = (isset($_POST['i']) ? $_POST['i'] : (isset($_GET['i']) ? $_GET['i'] : null));
    $l = (isset($_POST['l']) ? $_POST['l'] : (isset($_GET['l']) ? $_GET['l'] : null));
    $p = (isset($_POST['p']) ? $_POST['p'] : (isset($_GET['p']) ? $_GET['p'] : null));
    $t = (isset($_POST['t']) ? $_POST['t'] : (isset($_GET['t']) ? $_GET['t'] : null));
    $q = (isset($_POST['q']) ? $_POST['q'] : (isset($_GET['q']) ? $_GET['q'] : null));
    // remplace les espaces
    $l = preg_replace('#\v#', '', $l);
    $p = floatval($p);
    if (is_array($q)) {
        $qteArt = array();
        $i = 0;
        foreach ($q as $contenu) {
            $qteArt[$i++] = intval($contenu);
        }
    } else {
        $q = intval($q);
    }
}

if (!$erreur) {

    switch ($action) {
        case 'ajoutProd':
            //echo 'Produit ajouté <br>';
            // Modifications Oral PPE
            //1. si une session est ouvert
            //2. on stock dans une variable la session panier
            //3. on serialize la variable
            //4. update dans la table client du champ panier
            ajouterProduit($i, $l, $p, $t, $q);
           // echo $i . ' ' . $l . ' ' . $q;
            echo '<br>';
            //var_dump($_SESSION['panier']);
            if (isset($_SESSION['user_id'])) {
                $user_id = $_SESSION['user_id'];
                $panier_array = $_SESSION['panier'];
                $panier_string = serialize($panier_array);
                //$_SESSION['panier']['lock']=false;
                $insert = $db->prepare("UPDATE clients SET panier = '$panier_string' WHERE id = '$user_id' ");
                $insert->execute();
            }
            break;
        case 'supprimerProd':
            supprimerProd($i);

            if (isset($_SESSION['user_id'])) {
                $user_id = $_SESSION['user_id'];
                $panier_array = $_SESSION['panier'];
                $panier_string = serialize($panier_array);
                //$_SESSION['panier']['lock']=false;
                $insert = $db->prepare("UPDATE clients SET panier = '$panier_string' WHERE id = '$user_id' ");
                $insert->execute();
            }


            break;
        case 'rafraichir':
            for ($i = 0; $i < count($qteArt); $i++) {
                // le round est là pour être sur qu'il n'y ait pas une quantité à virgule
                modifierQteProd($_SESSION['panier']['id_prod'][$i], round($qteArt[$i]));

                if (isset($_SESSION['user_id'])) {
                    $user_id = $_SESSION['user_id'];
                    $panier_array = $_SESSION['panier'];
                    $panier_string = serialize($panier_array);
                    //$_SESSION['panier']['lock']=false;
                    $insert = $db->prepare("UPDATE clients SET panier = '$panier_string' WHERE id = '$user_id' ");
                    $insert->execute();
                }
            }
            break;
        default:
            break;
    } //fin switch
} // fin de if
//Modification Oral Pour sauvegarder le panier de la session en base si on est connecté
// si une session est ouverte
//1. récupérer l'objet client
//2. mettre le panier dans une variable
//3. unserialize() la variable
//4. stocker cette variable unserializé dans la variable de session panier
if (isset($_SESSION['user_id'])) {
    if (isset($_SESSION['panier'])) {
        $user_id = $_SESSION['user_id'];
        //1.
        $select_client = $db->prepare("SELECT * FROM clients where id = '$user_id'");
        $select_client->execute();
        $panier_svg = $select_client->fetch(PDO::FETCH_OBJ);
        //2.
        $panier = $panier_svg->panier; //unserialize
        //3.
        $panier_svg_unserialize = unserialize($panier);
        //4.
        $_SESSION['panier'] = $panier_svg_unserialize;
    } else {
        creationPanier();
    }
}
// Suppresion du panier (session et en base si connecté)
if (isset($_GET['deletepanier']) && $_GET['deletepanier'] == true) {

    supprimePanier();
}
// Si la varaible $_SESSION['panier'] n'existe pas, on va la créer et la fonction creation de panier va renvoyer true
if (creationPanier()) {
    $nbProd = count($_SESSION['panier']['id_prod']);
    // si le panier est vide
    if ($nbProd <= 0) {
        ?>
        <div class="empty_cart"><?php
        echo 'Votre panier est vide';
        ?></div><?php
    }
    // si il y a des produits dans le panier
    else {
        //On récupère les totaux via des fonctions
        $totalTTC = montantGlobalTTC();
        $totalHT = montantGlobal();
        $TVA = $totalTTC - $totalHT;
        // Montant arbitraire
        $fraisDePort = 20;
        //#####################################PAYPAL#########################################
        /*$paypal = new Paypal();
        $params = array(
        'RETURNURL'=>'http://localhost/mygavolt/process.php',
        'CANCELURL'=>'http://localhost/mygavolt/cancel.php',
        'PAYMENTREQUEST_0_AMT'=> $totalTTC + $fraisDePort,
        'PAYEMENTREQUEST_0_CURRENCYCODE'=> 'EUR',
        'PAYEMENTREQUEST_0_SHIPPINGAMT'=> $fraisDePort,
        'PAYEMENTREQUEST_0_ITEMAMT'=> $totalTTC
        );
        $response = $paypal -> request('SetExpressCheckout', $params);
        if($response){
        // si tout c bien passé
        //useraction = commit -> paiement
        // token : paramètres qui vont nous rendre unique
        $paypal = 'https://sandbox.paypal.com/webscr?cmd=_express-checkout&useraction=commit&token='.$response['TOKEN'].'';
        }
        else{
        var_dump($paypal->errors);
        var_dump($response);
        die('Erreur paypal');
        }*/
        //#####################################FIN - PAYPAL#########################################
        // Affichage des produit du panier
        ?>



        <div class="full_cart">
            <h2>Votre Panier</h2><br>
		<form method="post" action="">
			<table class="table" >

				<tr>
					<th> Nom produit</th>
					<th> Prix unitaire HT</th>
					<th> TVA</th>
					<th> Quantité </th>
					<th></th>
					<th> Supprimer</th>
				</tr>

				<?php
// on regarde combien de ligne il y a dans le panier
        // on les parcourt un par un dans la boucle
        // on les affiche dans le tableau
        for ($i = 0; $i < $nbProd; $i++) {
            ?>
					<tr>
						<td><?php echo $_SESSION['panier']['lib_prod'][$i]; ?></td>
						<td><?php echo $_SESSION['panier']['price_prod'][$i]; ?>€</td>
						<td><?php echo $_SESSION['panier']['tva_prod'][$i]; ?>%</td>
						<!-- champ pour modifier la quantité (il faudra cliquer sur actualiser pour appeler la fonction mettant à jour les quantités)  -->
						<td><input name="q[]" value="<?php echo $_SESSION['panier']['qte_prod'][$i]; ?>" size="5"><td>
						<!-- Lien pour supprimer un article  -->
						<td><a href="panier.php?action=supprimerProd&amp;i=<?php echo rawurlencode($_SESSION['panier']['id_prod'][$i]); ?>">X</a></td>
					</tr><?php
}?>
								<tr>
									<td colspan="2"><br>
											<p>Total HT : <?php echo $totalHT ?> € </p>
											<p>TVA : <?php echo $TVA; ?> €</p>
											<p>Total TTC :<?php echo $totalTTC; ?> € </p><br>
											<br>

											<?php if (isset($_SESSION['user_id'])) {
            ?><a href="process.php"><input type="button" value="Valider la commande"/></a><?php
} else {?>
												<a href="connect.php"><button type="submit" class="btn btn-dark" value="se connecter">Se connecter pour passer commande</button></a>
											<?php
}?>
										<!--	<a href="<?php //echo $paypal; ?>"><input type="button" value="Payer avec Paypal Sandbox *"/></a><br>-->
									</td>
									<td></td><td></td><td></td><td></td>


								</tr>

								<tr>
									<td colspan="4">
										<input class="btn1" type="submit" value="rafraichir" name="action" />

                                        <a class="btn1" href="?deletepanier=true" >Supprimer panier</a>




									</td>
									<td></td><td></td>
								</tr>


					</table>


				</form>
                </div><?php
    }
}
?>










<?php
//require_once 'includes/sidebar.php';
require_once 'includes/footer.php';
?>
