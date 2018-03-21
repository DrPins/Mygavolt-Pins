<?php


header('Content-Type: application/json');
//$_POST['id_intervention']= 299998;
//$_POST['id_client']= 299998;
//$_POST['id_motive']= 299998;

try{
    $db = new PDO('sqlsrv:Server=wserver.area42.fr;Database=mygavoltpins', 'mygavolt', 'k2Y*bswsaFyss3j7*Hsf',array(
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8',
        PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING
    ));
    $retour["success"] = true;
    $retour["message"] = "Connexion à la base";

}catch(PDOException $e){
    $retour["success"] = false;
    $retour["message"] = "Connexion à la base de donnée impossible";
}





//##############################################################Interventions#####################################################################

if(!empty($_POST['id_intervention'])){
  $id = $_POST['id_intervention'];
  $requete  = $db->prepare("SELECT * FROM interventions where id ='$id' order by date_inter");
  $requete->execute();

}
else{
  $requete = $db->prepare("SELECT * FROM interventions order by date_inter");
$requete->execute();
}

$retour["interventions"]["nb"] = count($requete->fetchAll());
$requete->execute();
$retour["interventions"]["categories"] = $requete->fetchAll();

//##############################################################Clients#####################################################################

if(!empty($_POST['id_client'])){
  $id = $_POST['id_client'];
  $requete  = $db->prepare("SELECT * FROM clients where id ='$id'");
  $requete->execute();

}
else{
  $requete = $db->prepare("SELECT * FROM clients");
$requete->execute();
}

$retour["clients"]["nb"] = count($requete->fetchAll());
$requete->execute();
$retour["clients"]["categories"] = $requete->fetchAll();

//##############################################################Motives#####################################################################

if(!empty($_POST['id_motive'])){
  $id = $_POST['id_motive'];
  $requete  = $db->prepare("SELECT * FROM motives where id ='$id'");
  $requete->execute();

}
else{
  $requete = $db->prepare("SELECT * FROM motives");
$requete->execute();
}

$retour["motifs"]["nb"] = count($requete->fetchAll());
$requete->execute();
$retour["motifs"]["categories"] = $requete->fetchAll();








echo json_encode($retour);


