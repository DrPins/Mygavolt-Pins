<?php

require_once('includes/header.php');


$user_id = $_SESSION['user_id'];

$select = $db->query("SELECT * FROM clients WHERE id ='$user_id'");

?>


<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="myaccount.php">Mon Compte</a>
    </div>
    <ul class="nav navbar-nav">
      <li><a href="?action=infoPerso">Mes informations</a></li>
      <li><a href="?action=infoCommandes">Mes commandes</a></li>
      <li><a href="?action=infoModif">Modification du compte</a></li>

    </ul>
  </div>
</nav>

<?php
if(!isset($_SESSION['user_id'])){

	header('Location:index.php');
}
	else{

		if(isset($_GET['action'])){
					$s = $select->fetch(PDO::FETCH_OBJ);

					if ($_GET['action']=='infoPerso'){

					?>
					<div class="account_home">
						<h2>Mes Informations</h2>
						<table class="table">
						  <tr>
						    <td>nom</td>
						    <td><?php echo $s->firstname;?></td>
						  </tr>
						  <tr>
						    <td>prenom</td>
						    <td><?php echo $s->lastname;?></td>
						  </tr>
						  <tr>
						    <td>Entreprise</td>
						    <td><?php echo $s->company;?></td>
						  </tr>
						  <tr>
						    <td>SIRET</td>
						    <td><?php echo $s->SIRET;?></td>
						  </tr>
						  <tr>
						    <td>email</td>
						    <td><?php echo $s->email;?></td>
						  </tr>
						  <tr>
						    <td>Adresse</td>
						    <td><?php echo $s->address1.' '.$s->address2;?></td>
						  </tr>
						  <tr>
						    <td>Ville</td>
						    <td><?php echo $s->city;?></td>
						  </tr>
						  <tr>
						    <td>Code Postal</td>
						    <td><?php echo $s->zipcode;?></td>
						  </tr>

						</table>
					</div>
						<?php
						}

					else if ($_GET['action']=='infoCommandes'){
							?>
							<div class="full_cart">
						<h2>Mes Commandes</h2><?php

						$select = $db->query("SELECT * FROM transactions WHERE user_id ='$user_id'");
						?>
						<table class="table">
							<tr>
								<th># de commande</th>
								<th>Date</th>
								<th>Montant</th>
								<th>Frais de port</th>
								<th></th>
							</tr>
						<?php
						while ($s = $select->fetch(PDO::FETCH_OBJ)) {
							?><tr>
								<td><?php echo $s->id; ?></td>
								<td><?php echo $s->date_achat; ?></td>
								<td><?php echo $s->amount.$s->currency_code; ?></td>
								<td><?php echo $s->shipping.$s->currency_code; ?></td>
								<!--<td><a href="?action=details&amp;id=<?php echo $s->id; ?>">Détails</a></td>-->


							</tr>
						<?php
						}
						?>
						</table>
					</div>
						<?php
					}
					else if($_GET['action']=='details'){?>

						<div class="full_cart">
							<?php
							$id = $_GET['id'];

							echo $id;
							?>

						</div>
						<?php
					}

					else if ($_GET['action']=='infoModif'){



						if(isset($_POST['submit'])){

							echo "submit ok";

							$firstName = $_POST['inputFirstname'];
							$lastName = $_POST['inputLastName'];
							$company = $_POST['inputCompany'];
							$siret = $_POST['inputSIRET'];
							$email = $_POST['inputEmail'];
							$pwd = password_hash($_POST['inputPassword'], PASSWORD_DEFAULT, ['cost' =>12]);
						  $address1 = $_POST['inputAddress'];
							$address2 = $_POST['inputAddress2'];
							$city = $_POST['inputCity'];
							$zipcode = $_POST['inputZip'];
							$id = $s->id;

							if($email&&$pwd){
								$insert = $db->prepare("UPDATE clients set firstname = '$firstName', lastname = '$lastName', company = '$company', SIRET = '$siret', email = '$email', pwd = '$pwd',	address1 = '$address1', address2 = '$address2', city = '$city', zipcode = '$zipcode' where id ='$id'  ");

							$insert->execute();

							//header('Location: myaccount.php?action=infoPerso');


							}
							else{
								echo '<br><h1>tous les champs ne sont pas remplis</h1>';
							}
						}

						?>


						<div class="index_home">
							<h2>Modifier le Compte</h2>
							<form action="" method="POST" class="form_inscription">
								<div class="form-row">
							    <div class="form-group col-md-6">
							      <label for="inputFirstname">Prénom</label>
							      <input type="text" class="form-control" id="inputFirstname" placeholder="Prénom" name="inputFirstname" value="<?php echo $s->firstname; ?>">
							    </div>
							    <div class="form-group col-md-6">
							      <label for="inputLastName">Nom</label>
							      <input type="text" class="form-control" id="inputLastName" placeholder="Nom" name="inputLastName" value="<?php echo $s->lastname; ?>">
							    </div>
							  </div>

							  <div class="form-row">
							    <div class="form-group col-md-6">
							      <label for="inputCompany">Entreprise</label>
							      <input type="text" class="form-control" id="inputCompany" placeholder="Entreprise" name="inputCompany" value="<?php echo $s->company; ?>">
							    </div>
							    <div class="form-group col-md-6">
							      <label for="inputSIRET">SIRET</label>
							      <input type="text" pattern="[0-99999999999999]" class="form-control" id="inputSIRET" placeholder="SIRET" name="inputSIRET" value="<?php echo $s->SIRET; ?>">
							    </div>
							  </div>

							  <div class="form-row">
							    <div class="form-group col-md-6">
							      <label for="inputEmail">Email</label>
							      <input type="email" class="form-control" id="inputEmail" placeholder="Email" name="inputEmail" value="<?php echo $s->email; ?>">
							    </div>
							    <div class="form-group col-md-6">
							      <label for="inputPassword">Password</label>
							      <input type="password" class="form-control" id="inputPassword" placeholder="Password" name="inputPassword" >
							    </div>
							  </div>
							  <div class="form-group">
							    <label for="inputAddress">Address</label>
							    <input type="text" class="form-control" id="inputAddress" placeholder="numéro, rue" name="inputAddress" value="<?php echo $s->address1; ?>">
							  </div>
							  <div class="form-group">
							    <label for="inputAddress2">Address 2</label>
							    <input type="text" class="form-control" id="inputAddress2" placeholder="Appartement, étage,..." name="inputAddress2" value="<?php echo $s->address2; ?>">
							  </div>
							  <div class="form-row">
							    <div class="form-group col-md-6">
							      <label for="inputCity">City</label>
							      <input type="text" class="form-control" id="inputCity" name="inputCity" value="<?php echo $s->city; ?>">
							    </div>
							    <div class="form-group col-md-4">

							    </div>
							    <div class="form-group col-md-2">
							      <label for="inputZip">Zip</label>
							      <input type="text" class="form-control" id="inputZip" name="inputZip" value="<?php echo $s->zipcode; ?>">
							    </div>
							  </div>

							  <button type="submit" class="btn1" name="submit">Mettre à jour</button>
							</form>
							</div>


					<?php
					}
						else{
							header('Location:index.php');
						}
	}
}


require_once('includes/footer.php');



?>
