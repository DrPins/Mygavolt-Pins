
<?php
session_start();

$user = 'Admin';
$pw = 'Admin';
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

<div class="formulaire_admin_index">
  <h1> Mygavolt Administration - Connexion </h1>
  <form action="" method="POST">
   <h3>Login</h3>	<input type="text" name="username" class="form-control">
   <h3>Password</h3>	<input type="Password" name="password" class="form-control"><br>
   <input type="submit" name="submit">

 </form>
</div>
