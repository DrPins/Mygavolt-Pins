<?php

require_once('includes/header.php');

if(!isset($_SESSION['user_id'])){



if(isset($_POST['submit'])){

  // récupération de la saisie mail et pwd
	$email = $_POST['inputEmail'];
	$pwdSubmitted = $_POST['inputPassword'];

  // si les 2 champs sont bien remplis
	if($email&&$pwdSubmitted){
    // on regarde en base en mettant l'adresse mail en parametre
		$select = $db->query("SELECT * FROM clients WHERE email='$email'");
    // si on récupère au moins un résultat
		if($select->fetchColumn()){

			$select = $db->query("SELECT * FROM clients WHERE email='$email'");
			$result = $select->fetch(PDO::FETCH_OBJ);
        // on vérifie que le mot de passe encrypté en base correspond bien à celui soumis
        if (password_verify($pwdSubmitted, $result->pwd )){
  			$_SESSION['user_id'] = $result->id;
  			$_SESSION['user_firstname'] = $result->firstname;
  			$_SESSION['user_email'] = $result->email;
  			//$_SESSION['user_pw'] = $result->pwd;
        }

		}
		else{
			echo '<h2>L\'identifiant n\'existe pas ou le mot de passe est incorect </h2>';
		}

	}
  // si les 2 champs ne sont pas remplis
	else{
		echo '<br><h1>tous les champs ne sont pas remplis</h1>';
	}
}

?>

<h2>Se connecter</h2>

<form action="" method="POST" class="form_inscription">

  <div class="form-row">
    <div class="form-group col-md-6">
      <label for="inputEmail">Email</label>
      <input type="email" class="form-control" id="inputEmail" placeholder="Email" name="inputEmail">
    </div>
    <div class="form-group col-md-6">
      <label for="inputPassword">Password</label>
      <input type="password" class="form-control" id="inputPassword" placeholder="Password" name="inputPassword">
    </div>
  </div>


  <button type="submit" class="btn btn-primary" name="submit">Se connecter</button>
</form>

<a href="register.php">Pas encore de compte ?
</a>


<?php
}
else{
	header('Location:myaccount.php');
}

require_once('includes/footer.php');



?>
