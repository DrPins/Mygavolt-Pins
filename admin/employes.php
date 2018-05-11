
<?php

require_once('header.php');?>


<a href="?action=add" class="btn btn-warning" role="button">Ajouter</a>

<a href="?action=modify" class="btn btn-warning" role="button">Modifier / Supprimer</a><br>


<?php

if(!isset($_GET['action'])){
  ?>
  <div class="full_cart">
    <?php






    ?>
  </div>

  <?php
}


//echo $_POST['submit'];

// vérification qu'on a bien rentré un username
if(isset($_SESSION['username'])){
  // vérification si il y a un code action
  if(isset($_GET['action'])){
    // si le code action est en mode 'ajout'

      if ($_GET['action']=='add'){

    if(isset($_POST['submit'])){
      // on récupère dans des variables les informations produit
      $firstname       = $_POST['firstname'];
      $lastname        = $_POST['lastname'];
      $SSN             = $_POST['SSN'];
      $email           = $_POST['email'];
      $phone           = $_POST['phone'];
      $mobile          = $_POST['mobile'];
      $marital_status  = $_POST['marital_status'];
      $birthday        = $_POST['birthday'];
      $arrival_date    = $_POST['arrival_date'];
      $bank_account    = $_POST['bank_account'];
      $street_number   = $_POST['street_number'];
      $street_name     = $_POST['street_name'];
      $zipcode         = $_POST['zipcode'];
      $city            = $_POST['city'];
      $country         = $_POST['country'];
      $img             = $_FILES['img']['name'];
      $img_tmp         = $_FILES['img']['tmp_name'];
      $pwd             = $_POST['pwd'];
      $is_active       = $_POST['is_active'];


      if(!empty($img_tmp)){
        $img= explode('.', $img);
        $image_ext= end($img);
        print_r($image_ext);

        //on vérifie que le fichier à la bonne extension
        if(in_array(strtolower($image_ext), array('png', 'jpg', 'jpeg'))===false){

          echo "Veuillez rentrer une image ayant pour extension : png, jpg ou jpeg";
        }
        else{
          // on con
          $image_size=getimagesize($img_tmp);
          if($image_size['mime']=='image/jpeg'){
            $image_src = imagecreatefromjpeg($img_tmp);
          }
          else if($image_size['mime']=='image/png'){
            $image_src = imagecreatefrompng($img_tmp);
          }
          else {
            $image_src = false;
            echo"Veuillez rentrer une image valide";
          }

          if ($image_src!==false){
            $image_width=200;
            if($image_size[0]==$image_width){
              $image_finale = $image_src;
            }
            else{
              $new_width[0] = $image_width;
              $new_height[1] = 200 ;
              $image_finale = imagecreatetruecolor($new_width[0], $new_height[1]);

              imagecopyresampled($image_finale, $image_src, 0, 0, 0, 0, $new_width[0], $new_height[1], $image_size[0], $image_size[1]);
            }

            imagejpeg($image_finale, 'imgs/'.$timestamp.'.jpg');
          }
        }

      }
      else{
        echo'Veuilliez rentrer une image';
      }

      // on vérifie que toutes les informations ont bien été renseignées
      if($lastname&&$pwd){

        $insert = $db->prepare("INSERT INTO employees (firstname, lastname, social_security_number, email,
         phone, mobile, marital_status, birthday, arrival_date, bank_account, street_number, street_name, zipcode, city,
         country, picture, pwd, is_active) VALUES ('$firstname', '$lastname', '$SSN', '$email',
         '$phone', '$mobile', '$marital_status', '$birthday', '$arrival_date', '$bank_account', '$street_number', '$street_name', '$zipcode', '$city', '$country', '$timestamp', '$pwd', '$is_active')");
        $insert->execute();

      }
      else{
        echo "Veuillez remplir tous les champs";
      }
    }

    ?>

    <form action="" method="POST" enctype="multipart/form-data">

      <h4>Nom :                          </h4><input type="text" class="form-control"  name="lastname">
      <h4>Prénom :                       </h4><input type="text" class="form-control"  name="firstname">
      <h4>Numéro de sécurité sociale :   </h4><input type="text" class="form-control"  name="SSN">
      <h4>Email :                        </h4><input type="email" class="form-control"  name="email">
      <h4>Téléphone fixe :               </h4><input type="text" class="form-control"  name="phone">
      <h4>Téléphone mobile :             </h4><input type="text" class="form-control"  name="mobile">
      <h4>Status marital :               </h4><input type="text" class="form-control"  name="marital_status">
      <h4>Date de naissance :            </h4><input type="Date" class="form-control"  name="birthday">
      <h4>Date d'arrivée :               </h4><input type="Date" class="form-control"  name="arrival_date">
      <h4>Numéro de compte bancaire :    </h4><input type="text" class="form-control"  name="bank_account">
      <h4>Numéro :                       </h4><input type="text" class="form-control"  name="street_number">
      <h4>Rue :                          </h4><input type="text" class="form-control"  name="street_name">
      <h4>Ville :                        </h4><input type="text" class="form-control"  name="city">
      <h4>Code postal :                  </h4><input type="text" class="form-control"  name="zipcode">
      <h4>Pays :                         </h4><input type="text" class="form-control"  name="country">
      <h4>Mot de passe :                 </h4><input type="password" class="form-control"  name="pwd">
      <h4>Actif :                        </h4>
      <input class="form-control" type="radio" name="is_active" value="1" checked> Oui<br>
      <input class="form-control" type="radio" name="is_active" value="0"> Non

      <h4>Photo          : </h4><input type="file" name="img" class="custom-file-input"><br>

      <input type="submit" name="submit" class="btn btn-warning" role="button">

    </form>

  <?php

  }
  //************************************************************AFFICHAGE************************************************************
  else if ($_GET['action']=='modify'){
    // ce qu'il va se passer lorsque l'on va cliquer sur supprimer/modifier un produit
        ?>
        <br>
        <?php
        $select = $db->prepare("SELECT * FROM employees");
        $select->execute();
        ?>
        <div class="full_cart">
        <table class="table">
          <tr>
            <th>Nom                           </th>
            <th>Prénom :                       </th>
            <th>Email                         </th>
            <th>Téléphone fixe                </th>
            <th>Téléphone mobile              </th>
            <th>Date d'arrivée                </th>
            <th>Actif                         </th>
            <th></th>

          </tr>

          <?php
        while($s=$select->fetch(PDO::FETCH_OBJ)){?>

          <tr>
            <td><?php echo $s->firstname;?></td>
            <td><?php echo $s->lastname;?></td>
            <td><?php echo $s->email;?></td>
            <td><?php echo $s->phone;?></td>
            <td><?php echo $s->mobile;?></td>
            <td><?php echo $s->arrival_date;?></td>
            <td><?php
            if ($s->is_active==1){
              echo "oui";
            }else{
              echo "non";
            }
            ?></td>

            <td><a href="?action=mod&amp;id=<?php echo $s->id; ?>">Modifier</a></td>
          </tr>



          <?php

        }

        ?></table>
        </div><?php

  }
  //************************************************************MODIFICATION************************************************************
  else if ($_GET['action']=='mod'){
    // ce qu'il va se passer lorsque l'on va cliquer sur modifier


        $id=$_GET['id'];
        // on récupère les données du produit dans une variable
        $select = $db->prepare("SELECT * FROM employees WHERE id = $id");
        $select->execute();

        $employee = $select->fetch(PDO::FETCH_OBJ);

        // on affiche ces données dans les champs
      ?>


    <form action="" method="POST" enctype="multipart/form-data">

      <h4>Nom :                          </h4><input type="text" class="form-control"  name="lastname"       value="<?php echo $employee->lastname; ?>">
      <h4>Prénom :                       </h4><input type="text" class="form-control"  name="firstname"      value="<?php echo $employee->firstname; ?>">
      <h4>Numéro de sécurité sociale :   </h4><input type="text" class="form-control"  name="SSN"            value="<?php echo $employee->social_security_number; ?>">
      <h4>Email :                        </h4><input type="email" class="form-control" name="email"          value="<?php echo $employee->email; ?>">
      <h4>Téléphone fixe :               </h4><input type="text" class="form-control"  name="phone"          value="<?php echo $employee->phone; ?>">
      <h4>Téléphone mobile :             </h4><input type="text" class="form-control"  name="mobile"         value="<?php echo $employee->mobile; ?>">
      <h4>Status marital :               </h4><input type="text" class="form-control"  name="marital_status" value="<?php echo $employee->marital_status; ?>">
      <h4>Date de naissance :            </h4><input type="Date" class="form-control"  name="birthday"       value="<?php echo $employee->birthday; ?>">
      <h4>Date d'arrivée :               </h4><input type="Date" class="form-control"  name="arrival_date"   value="<?php echo $employee->arrival_date; ?>">
      <h4>Numéro de compte bancaire :    </h4><input type="text" class="form-control"  name="bank_account"   value="<?php echo $employee->bank_account; ?>">
      <h4>Numéro :                       </h4><input type="text" class="form-control"  name="street_number"  value="<?php echo $employee->street_number; ?>">
      <h4>Rue :                          </h4><input type="text" class="form-control"  name="street_name"    value="<?php echo $employee->street_name; ?>">
      <h4>Ville :                        </h4><input type="text" class="form-control"  name="city"           value="<?php echo $employee->city; ?>">
      <h4>Code postal :                  </h4><input type="text" class="form-control"  name="zipcode"        value="<?php echo $employee->zipcode; ?>">
      <h4>Pays :                         </h4><input type="text" class="form-control"  name="country"        value="<?php echo $employee->country; ?>">
      <h4>Mot de passe :                 </h4><input type="password" class="form-control"  name="pwd"        value="<?php echo $employee->pwd; ?>">
      <h4>Actif :                        </h4>
      <?php if($employee->is_active == 1){?>
        <input class="form-control" type="radio" name="is_active" value="1" checked> Oui
        <input class="form-control" type="radio" name="is_active" value="0"> Non
      <?php
      }else{?>
        <input class="form-control" type="radio" name="is_active" value="1" > Oui
        <input class="form-control" type="radio" name="is_active" value="0" checked> Non
        <?php
      }?>

      <input type="submit" name="submit" class="btn btn

      <h4>Photo          : </h4><input type="file" name="img" class="custom-file-input"><br>
-warning" role="button">
    </form>

    <?php
    // on récupère les données passées en POST et on les utilise pour faire l'update en base du produit
    if (isset($_POST['submit'])) {
      echo "check";
      // on récupère dans des variables les informations produit
      $firstname       = $_POST['firstname'];
      $lastname        = $_POST['lastname'];
      $SSN             = $_POST['SSN'];
      $email           = $_POST['email'];
      $phone           = $_POST['phone'];
      $mobile          = $_POST['mobile'];
      $marital_status  = $_POST['marital_status'];
      $birthday        = $_POST['birthday'];
      $arrival_date    = $_POST['arrival_date'];
      $bank_account    = $_POST['bank_account'];
      $street_number   = $_POST['street_number'];
      $street_name     = $_POST['street_name'];
      $zipcode         = $_POST['zipcode'];
      $city            = $_POST['city'];
      $country         = $_POST['country'];
      $img             = $_FILES['img']['name'];
      $img_tmp         = $_FILES['img']['tmp_name'];
      $pwd             = $_POST['pwd'];
      $is_active       = $_POST['is_active'];


        // Si il y a une image, on la reformate et on l'enregistre
        if(!empty($img_tmp)){
          $img= explode('.', $img);
          $image_ext= end($img);


          if(in_array(strtolower($image_ext), array('png', 'jpg', 'jpeg'))===false){
            //on vérifie que le fichier à la bonne extension
            echo "Veuillez rentrer une image ayant pour extension : png, jpg ou jpeg";
          }
          else{
          // on con
          $image_size=getimagesize($img_tmp);
          if($image_size['mime']=='image/jpeg'){
            $image_src = imagecreatefromjpeg($img_tmp);
          }
          else if($image_size['mime']=='image/png'){
            $image_src = imagecreatefrompng($img_tmp);
          }
          else {
            $image_src = false;
            echo"Veuillez rentrer une image valide";
          }

          if ($image_src!==false){
            $image_width=200;
            if($image_size[0]==$image_width){
              $image_finale = $image_src;
            }
            else{
              $new_width[0] = $image_width;
              $new_height[1] = 200 ;
              $image_finale = imagecreatetruecolor($new_width[0], $new_height[1]);

              imagecopyresampled($image_finale, $image_src, 0, 0, 0, 0, $new_width[0], $new_height[1], $image_size[0], $image_size[1]);
            }

            imagejpeg($image_finale, 'imgs/'.$timestamp.'.jpg');
          }
        }



      }
      //else{
      //  echo'Veuillez rentrer une image';
      //}

      // on vérifie que toutes les informations ont bien été renseignées
      if($lastname&&$pwd){
        echo "ok";

        $update = $db->prepare("UPDATE employees SET  firstname='$firstname', lastname='$lastname', social_security_number='$SSN', email='$email',
         phone='$phone', mobile='$mobile', marital_status='$marital_status', birthday='birthday', arrival_date='$arrival_date', bank_account='$bank_account', street_number='$street_number', street_name='$street_name', zipcode='$zipcode', city='$city', country='$country', picture='$timestamp', pwd='$pwd', is_active='$is_active'
          WHERE id=$id");
        $update->execute();

        ?><meta http-equiv="refresh" content="1;url=employes.php?action=modify"/><?php

      }
      else{
        echo "Veuillez remplir tous les champs";
      }



    }?>

<?php
  }
  //************************************************************SUPPRESSION************************************************************
  else if ($_GET['action']=='del'){
    // ce qu'il va se passer lorsque l'on va cliquer sur X
        $id = $_GET['id'];
        $del = $db->prepare("DELETE FROM employees WHERE id=$id");
        $del->execute();

        ?><meta http-equiv="refresh" content="1;url=employees.php?action=modify"/><?php

  }


  }
}

