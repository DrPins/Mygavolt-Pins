<?php
require_once 'includes/header.php';
require_once 'includes/functions_panier.php';
require_once 'includes/paypal.php';
//partie pour essayer de récupérer les données sans passer par la validation paypal

$timestamp   = time();
$products = '';
$paypal = new Paypal();
$totalTTC = montantGlobalTTC();
$totalHT = montantGlobal();
$TVA = $totalTTC - $totalHT;
$fraisDePort = 20;
$currency_code = 'EUR';
$date_achat = date("Ymd");
$user_id = $_SESSION['user_id'];
for ($i = 0; $i < count($_SESSION['panier']['lib_prod']); $i++) {
    //va rajouter à l'array product l'id du produit
  $products .= $_SESSION['panier']['id_prod'][$i];
  if ($i <= 1) {
    $products .= ', ';
  }
}

// récupération de l'adresse du client

$select_client = $db->prepare("SELECT * FROM clients where id = '$user_id'");
$select_client->execute();
$client = $select_client->fetch(PDO::FETCH_OBJ);
$client_id     = $client->id;
$client_name   = $client->lastname;
$client_adress = $client->address1 ." ".$client->address2;
$client_city   = $client->city;



//a la sortie du for, la variables products va contenir tous les produits


echo "<pre>";
var_dump($_SESSION['panier']);
echo "</pre>";
// on va enregistrer en base les détails de la commande pour pouvoir en garder une trace et les exploiter
for ($i=0; $i < count($_SESSION['panier']['lib_prod']) ; $i++) {
  $order_prod_id        = $_SESSION['panier']['id_prod'][$i];
  $order_transaction_id = $timestamp;
  $order_quantity       = $_SESSION['panier']['qte_prod'][$i];
  $order_price          = $_SESSION['panier']['price_prod'][$i];
  $order_date           = $date_achat;

  $select_promotion = $db->prepare("SELECT * FROM products where id = '$order_prod_id'");
  $select_promotion->execute();
  $promo = $select_promotion->fetch(PDO::FETCH_OBJ);
  $promo = $promo->id_promotion;


  $select_promotion = $db->prepare("SELECT * FROM promotions where id = '$promo'");
  $select_promotion->execute();
  $promotion = $select_promotion->fetch(PDO::FETCH_OBJ);
  $order_promotion = $promotion->label;

  $insert = $db->prepare("INSERT INTO orders VALUES('$order_transaction_id', '$client_id ', '$order_quantity', '$order_date', '$order_price', '$order_promotion' ,'$order_prod_id' ) ");
  $insert->execute();


}

$insert = $db->prepare("INSERT INTO transactions ( name, street, city, date_achat, transaction_id, amount, shipping, currency_code, user_id, products)
  VALUES( '$client_name', '$client_adress', '$client_city', '$date_achat', $timestamp, '$totalTTC', '$fraisDePort', '$currency_code', '$user_id', '$products') ");
$insert->execute();

unset($_SESSION['panier']);
supprimePanier();
//header('Location: success.php');


//**********************************end***********************************************
/*
$response = $paypal -> request('GestExpressCheckoutDetails',array(
'TOKEN'=>$_GET['token']));
if($response){
if($response['CHECKOUTSTATUS']=='PaymentActionCompleted'){
$response2 = $paypal -> request('GestTransactionDetails',array(
'TRANSACTIONID'=>$response['PAYEMENTREQUEST_0_TRANSACTIONID']));
var_dump($response2);
die('Ce paiement a déjà été validé');
}
}
else{
var_dump($paypal->errors);
die();
}
$response = $paypal->request('DoExpressCheckoutPayment', array(
'TOKEN'=> $_GET['token'],
'PAYERID'=>$_GET['PayerID'],
'PAYMENTACTION'=>'Sale',
'PAYMENTREQUEST_0_AMT'=> $totalTTC + $fraisDePort,
'PAYEMENTREQUEST_0_CURRENCYCODE'=> 'EUR'));
if($response){
$response2 = $paypal -> request('GestTransactionDetails',array(
'TRANSACTIONID'=>$response['PAYEMENTREQUEST_0_TRANSACTIONID']));
$products = '';
for ($i=0; $i < count($_SESSION['panier']['lib_prod']); $i++) {
//va rajouter à l'array product l'id du produit
$products.=$_SESSION['panier']['id_prod'][$i];
if(count($_SESSION['panier']['lib_prod'])>1){
$products.=', ';
}
}
//a la sortie du for, la variables products va contenir tous les produits
var_dump($products);
var_dump($response2);
}
else{
var_dump($paypal->errors);
}
 */
