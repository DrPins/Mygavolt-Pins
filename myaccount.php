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
      <li><a href="?action=infoModif">Paramètres du compte</a></li>
      
    </ul>
  </div>
</nav>

<?php
if(!isset($_SESSION['user_id'])){

	header('Location:index.php');
}
	else{

		if(isset($_GET['action'])){

					if ($_GET['action']=='infoPerso'){

					?>
					<h2>Mes Informations</h2>

					<?php

						while ($s = $select->fetch(PDO::FETCH_OBJ)) {

						?>
							<h4>nom:         <?php echo $s->firstname;?></h4>
							<h4>prenom:      <?php echo $s->lastname;?></h4>
							<h4>Entreprise:  <?php echo $s->company;?></h4>
							<h4>SIRET:       <?php echo $s->SIRET;?></h4>
							<h4>email:       <?php echo $s->email;?></h4>
							<h4>Adresse:     <?php echo $s->address1;?></h4>
							<h4>Adresse2:    <?php echo $s->address2;?></h4>
							<h4>Ville:       <?php echo $s->city;?></h4>
							<h4>Code Postal: <?php echo $s->zipcode;?></h4>

						<?php
						}

						}
					else if ($_GET['action']=='infoCommandes'){
							?>
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
								<td><a href="?action=details&amp;id=<?php echo $s->id; ?>">Détails</a></td>


							</tr>	
						<?php		
						}
						?>
						</table>
						<?php
					}
					else if($_GET['action']=='details'){

						$id = $_GET['id'];

						echo $id;
					}

					else if ($_GET['action']=='infoModif'){
						?>
					<h2>Paramètres du Compte</h2>

					<?php
					}
						else{
							header('Location:index.php');
						}
	}
}


require_once('includes/footer.php');



?>