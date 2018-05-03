<?php
function creationPanier()
{
    try {
        $db = new PDO('sqlsrv:Server=wserver.area42.fr;Database=mygavoltpins', 'mygavolt', 'k2Y*bswsaFyss3j7*Hsf', array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8',
            PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING,
        ));
    } catch (PDOException $e) {
        die('<h1>Impossible de se connecter porut</h1>');
    }
    if (!isset($_SESSION['panier'])) {
        $_SESSION['panier'] = array();
        $_SESSION['panier']['id_prod'] = array();
        $_SESSION['panier']['lib_prod'] = array();
        $_SESSION['panier']['qte_prod'] = array();
        $_SESSION['panier']['price_prod'] = array();
        $_SESSION['panier']['tva_prod'] = array();
        $_SESSION['panier']['lock'] = false;
    }
    return true;
}
function ajouterProduit($id_prod, $lib_prod, $price_prod, $tva_prod, $qte_prod)
{
    //unset($_SESSION['panier']);
    //echo creationPanier();
    if (creationPanier() && !isVerrouille()) {
        //$_SESSION['panier']['id_prod'] = "";
        //    echo '$_SESSION["panier"]["id_prod"] : <pre>';
        //    var_dump($_SESSION['panier']['id_prod']);
        //    echo '</pre>';
        //$_SESSION['panier'] = unserialize(str)
        $position_produit = array_search($id_prod, $_SESSION['panier']['id_prod']);
        //echo '<br> position produit : '.$position_produit;
        if ($position_produit !== false) {
            // si le produit a déjà été ajouté, on incremente la quantité
            $_SESSION['panier']['qte_prod'][$position_produit] += $qte_prod;
        } else {
            //echo 'test';
            array_push($_SESSION['panier']['id_prod'], $id_prod);
            array_push($_SESSION['panier']['lib_prod'], $lib_prod);
            array_push($_SESSION['panier']['price_prod'], $price_prod);
            array_push($_SESSION['panier']['tva_prod'], $tva_prod);
            array_push($_SESSION['panier']['qte_prod'], $qte_prod);
        }
    } else {
        echo "Erreur, Contactez l'administrateur";
    }
    //echo '$_SESSION["panier"]["id_prod"] : <pre>';
    //    var_dump($_SESSION['panier']['id_prod']);
    //    echo '</pre>';
}
function modifierQteProd($id_prod, $qte_prod)
{
    //echo 'modifier';
    //si le panier exite
    if (creationPanier() && !isVerrouille()) {
        // si la quantité est positive on modifie sinon on supprime l'article
        if ($qte_prod > 0) {
            // recherche du produit dans le panier
            $position_produit = array_search($id_prod, $_SESSION['panier']['id_prod']);
            if ($position_produit !== false) {
                $_SESSION['panier']['qte_prod'][$position_produit] = $qte_prod;
            }
        } else {
            supprimerProd($id_prod);
        }
    } else {
        echo "Erreur, Contactez l'administrateur";
    }
}
function supprimerProd($id_prod)
{
    //echo "supprimer article<br>";
    if (creationPanier() && !isVerrouille()) {
        // Si le panier est bien créé, on crée un array temporaire où mettre la liste de produit sans celui que l'on veut supprimer
        $tmp = array();
        $tmp['id_prod'] = array();
        $tmp['lib_prod'] = array();
        $tmp['qte_prod'] = array();
        $tmp['price_prod'] = array();
        $tmp['tva_prod'] = array();
        $tmp['lock'] = $_SESSION['panier']['lock'];
        // Pour chaque article dans mon panier, je vais copier le produit dans la liste temporaire sauf si c'est celui que l'on veut supprimer
        for ($i = 0; $i < count($_SESSION['panier']['id_prod']); $i++) {
           //echo '<br>produit ' . $i;
            if ($_SESSION['panier']['id_prod'][$i] !== $id_prod) {
                array_push($tmp['id_prod'], $_SESSION['panier']['id_prod'][$i]);
                array_push($tmp['lib_prod'], $_SESSION['panier']['lib_prod'][$i]);
                array_push($tmp['price_prod'], $_SESSION['panier']['price_prod'][$i]);
                array_push($tmp['tva_prod'], $_SESSION['panier']['tva_prod'][$i]);
                array_push($tmp['qte_prod'], $_SESSION['panier']['qte_prod'][$i]);
            }
        }
       // var_dump($tmp);
        // on copie le panier temporaire dans le nouveau panier
        $_SESSION['panier'] = $tmp;
        // on efface le panier temporaire
        unset($tmp);
    } else {
        echo "Erreur, Contactez l'administrateur";
    }
}
function montantGlobal()
{
    $total = 0;
    for ($i = 0; $i < count($_SESSION['panier']['id_prod']); $i++) {
        $total += $_SESSION['panier']['qte_prod'][$i] * $_SESSION['panier']['price_prod'][$i];
    }
    $total = round($total, 2);
    return $total;
}
function montantGlobalTTC()
{
    $total = 0;

    /*echo count($_SESSION['panier']['id_prod']);
    echo '<pre>';
    var_dump($_SESSION['panier']);
    echo '</pre>';*/

    for ($i = 0; $i < count($_SESSION['panier']['id_prod']); $i++) {
        $total += ($_SESSION['panier']['qte_prod'][$i] * $_SESSION['panier']['price_prod'][$i]) * (100 + $_SESSION['panier']['tva_prod'][$i]) / 100;
    }

    $total = round($total, 2);
    return $total;
}
function supprimePanier()
{


    // POUR SUPPRIMER LE PANIER SESSION
    unset($_SESSION['panier']);
    // POUR SUPPRIMER LE PANIER ENREGISTRE EN BASE
    //1. si une session est ouverte
    //4. update dans la table client du champ panier à vide
    try {
        $db = new PDO('sqlsrv:Server=wserver.area42.fr;Database=mygavoltpins', 'mygavolt', 'k2Y*bswsaFyss3j7*Hsf', array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8',
            PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING,
        ));
    } catch (PDOException $e) {
        die('<h1>Impossible de se connecter</h1>');
    }
    $_SESSION['panier'] = array();
    $_SESSION['panier']['id_prod'] = array();
    $_SESSION['panier']['lib_prod'] = array();
    $_SESSION['panier']['qte_prod'] = array();
    $_SESSION['panier']['price_prod'] = array();
    $_SESSION['panier']['tva_prod'] = array();
    $_SESSION['panier']['lock'] = false;
    if (isset($_SESSION['user_id'])) {
        if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
            $panier = $_SESSION['panier'];
            echo $user_id;
            $insert = $db->prepare("UPDATE clients SET panier = NULL WHERE id = :user_id");
            $insert->execute([':user_id' => $user_id]);
        }
    }
}
function compterPanier()
{
    if (isset($_SESSION['panier'])) {
        return count($_SESSION['panier']['id_prod']);
    } else {
        return 0;
    }
}
function isVerrouille()
{
    if (isset($_SESSION['panier']) && $_SESSION['panier']['lock']) {
        return true;
    } else {
        return false;
    }
}
