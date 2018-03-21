      
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

      	$user = 'Toto';
      	$pw = 'Pins';
      	if(isset($_POST['submit'])){
      		$username =  $_POST['username'];
      		$password =  $_POST['password'];

      		if($username&&$password){
      			
      			if($username == $user && $password == $pw){
      				

      				$_SESSION['username'] = $username;
      				header('Location: admin.php');
      			}
      			else{
      				echo "identifiants éronnés";
      			}

      		}
      		else{
      			echo "veuillez remplir tous les champs";
      		}
      	}

      ?>


      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	  <link rel="stylesheet" type="text/css" href="../style/style.css">
	  <h1>Administration - Connexion </h1>

	  <form action="" method="POST">
	  <h3>Pseudo</h3>	<input type="text" name="username">
	  <h3>Password</h3>	<input type="Password" name="password"><br><br><br>
	  <input type="submit" name="submit">

	  </form>