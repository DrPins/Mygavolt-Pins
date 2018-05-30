

<?php


require_once('header.php');

//session_start();
$timestamp   = time();

try{
  $db = new PDO('sqlsrv:Server=wserver.area42.fr;Database=mygavoltpins', 'mygavolt', 'k2Y*bswsaFyss3j7*Hsf',array(
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8',
    PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING
  ));

}catch(PDOException $e){
  die('<h1>Impossible de se connecter</h1>');
}?>

<div class="admin_home">Bienvenue <?php echo $_SESSION['username'] ?></div>


</body>
