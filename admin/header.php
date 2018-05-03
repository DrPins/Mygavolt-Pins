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
    <link rel="stylesheet" type="text/css" href="../style/style.css">


  <title>MYGAVOLT-Administration</title>
</head>

    <nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="admin.php">MYGAVOLT ADMIN PANEL</a>
    </div>
    <ul class="nav navbar-nav">
      <li><a href="produits.php?action=modify">Produits</a></li>
      <li><a href="categories.php?action=modifycat">Catégories</a></li>
      <li><a href="promotions.php?action=modifypromo">Promotions</a></li>
      <li><a href="clients.php">Clients</a></li>
      <li><a href="employes.php">Employés</a></li>
      <li><a href="interventions.php?action=modify">Interventions</a></li>
      <li><a href="motifs.php?action=modifymotif">Motifs</a></li>

    </ul>
  </div>
</nav>

<body >

