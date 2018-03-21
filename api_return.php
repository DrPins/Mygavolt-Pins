<?php


header('Content-Type: application/json');
$_POST['report']  = "blablabal balb  balbalbla bla blabla ";
$_POST['duration']= "01:00:00";
//$_POST['pending'] = 1;
$_POST['action']  = 'fin';
$_POST['id_inter']= '100000';


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

if(!empty($_POST['action']) && !empty($_POST['id_inter'])){

  $id_inter=$_POST['id_inter'];

  if($_POST['action'] == 'debut'){
    // si l'intervention commence, on passe pending à 0
    $retour["passage"]= 'pas glop';
     $requete  = $db->prepare("UPDATE interventions SET pending = 0 where id = '$id_inter'");
     $requete->execute();

    $retour["modif_intervention"]= 'debut';
  }
  else if ($_POST['action'] == 'fin'){
    $retour["passage"]= 'ok';
    if(!empty($_POST['report']) && !empty($_POST['duration'])){

      // si l'intervention fini, on ajouter en base le temps et le rapport et on passe pending à 1
      $duration = $_POST['duration'];
      $report  = $_POST['report'];
      $requete = $db->prepare("UPDATE interventions SET pending = 1, report ='$report', duration='$duration'  where id = '$id_inter'");
      $requete->execute();

      $retour["modif_intervention"]= 'fin';
    }
    else{
      $retour["modif_intervention"]= false;
    }


  }
  else{
    $retour["modif_intervention"]= false;
  }





}
else{
  $retour["modif_intervention"]= false;
}



echo json_encode($retour);


