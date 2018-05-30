<?php

require_once('includes/header.php');

if(!isset($_SESSION['user_id'])){

  if(isset($_POST['submit'])){
   $firstName = $_POST['inputFirstname'];
   $lastName = $_POST['inputLastName'];
   $company = $_POST['inputCompany'];
   $siret = $_POST['inputSIRET'];
   $email = $_POST['inputEmail'];
   $pwd = password_hash($_POST['inputPassword'], PASSWORD_DEFAULT, ['cost' =>12]);
   $address1 = $_POST['inputAddress'];
   $address2 = $_POST['inputAddress2'];
   $city = $_POST['inputCity'];
   $zipcode = $_POST['inputZip'];

   if($email&&$pwd){
    $insert = $db->prepare("INSERT INTO clients (firstname, lastname, company, SIRET, email, pwd,	address1, address2, city, zipcode)
      VALUES('$firstName', '$lastName', '$company', '$siret', '$email', '$pwd', '$address1', '$address2', '$city', '$zipcode') ");

    $insert->execute();

    header('Location: connect.php');


  }
  else{
    echo '<br><h1>tous les champs ne sont pas remplis</h1>';
  }
}

?>

<div class="index_home">
  <form action="" method="POST" class="form_inscription">
   <div class="form-row">
    <div class="form-group col-md-6">
      <label for="inputFirstname">Prénom</label>
      <input type="text" pattern="[a-zA-Z\u00C0-\u017F\][^'\x22]+$" class="form-control" id="inputFirstname" placeholder="Prénom" name="inputFirstname">
    </div>
    <div class="form-group col-md-6">
      <label for="inputLastName">Nom</label>
      <input type="text" class="form-control" pattern="[a-zA-Z\u00C0-\u017F\][^'\x22]+$" id="inputLastName" placeholder="Nom" name="inputLastName">
    </div>
  </div>

  <div class="form-row">
    <div class="form-group col-md-6">
      <label for="inputCompany">Entreprise</label>
      <input type="text" class="form-control" id="inputCompany" pattern="[a-zA-Z\u00C0-\u017F\][^'\x22]+$" placeholder="Entreprise" name="inputCompany">
    </div>
    <div class="form-group col-md-6">
      <label for="inputSIRET">SIRET</label>
      <input type="text" pattern="[0-9]{3}[ \.\-]?[0-9]{3}[ \.\-]?[0-9]{3}[ \.\-]?[0-9]{5}" class="form-control" id="inputSIRET" placeholder="SIRET" name="inputSIRET">
    </div>
  </div>

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
  <div class="form-group">
    <label for="inputAddress">Address</label>
    <input type="text" class="form-control" id="inputAddress" placeholder="numéro, rue" name="inputAddress">
  </div>
  <div class="form-group">
    <label for="inputAddress2">Address 2</label>
    <input type="text" class="form-control" id="inputAddress2" placeholder="Appartement, étage,..." name="inputAddress2">
  </div>
  <div class="form-row">
    <div class="form-group col-md-6">
      <label for="inputCity">City</label>
      <input type="text" class="form-control" id="inputCity" name="inputCity">
    </div>
    <div class="form-group col-md-4">

    </div>
    <div class="form-group col-md-2">
      <label for="inputZip">Zip</label>
      <input type="text"   class="form-control" id="inputZip" name="inputZip">
    </div>
  </div>

  <button type="submit" class="btn1" name="submit">S'inscrire</button>
</form>
</div>
<?php

}

else{
	header('Location:myaccount.php');
}
require_once('includes/footer.php');
?>
