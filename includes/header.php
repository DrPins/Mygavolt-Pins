<?php
session_start();


try{
  $db = new PDO('sqlsrv:Server=wserver.area42.fr;Database=mygavoltpins', 'mygavolt', 'k2Y*bswsaFyss3j7*Hsf',array(
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8',
    PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING
  ));

}catch(PDOException $e){
  die('<h1>Impossible de se connecter</h1>');
}

/*
        try{
            $db = new PDO('mysql:host=127.0.0.1;dbname=mygavolt', 'root','root',array(
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8',
                PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING
            ));

        }catch(PDOException $e){
            die('<h1>Impossible de se connecter</h1>');
        }

*/
        ?>



        <!DOCTYPE html>
        <html>
        <head>
          <meta charset="utf-8" />
          <!-- Librairie bootstrap + jquery -->
          <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
          <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
          <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
          <link rel="stylesheet" type="text/css" href="style/style.css">


          <title>MYGAVOLT - matériel éléctrique</title>
        </head>

        <nav class="navbar navbar-inverse navbar-fixed-top">
          <div class="container-fluid">
            <div class="navbar-header">
              <a class="navbar-brand">MYGAVOLT</a>
            </div>
            <ul class="nav navbar-nav">
              <li ><a href="index.php">Home</a></li>
              <li><a href="boutique.php">Produits</a></li>
              <li><a href="panier.php">Panier</a></li>
              <?php
              if(!isset($_SESSION['user_id'])){
                ?><li><a href="register.php">Inscription</a></li>
                <li><a href="connect.php">Connexion</a></li><?php
              }
              else{
                ?>
                <li><a href="myaccount.php?action=infoPerso">Mon Compte</a></li>
                <li><a href="disconnect.php">Deconnexion</a></li>
                <?php
              }
              ?>
            </ul>
          </div>
        </nav>

        <body>

