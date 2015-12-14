<?php
session_start();
require_once("includes/database.php");
require_once("includes/User.php");
require_once("includes/FormValidator.php");
require_once("includes/Address.php");

if (isset($_POST["registration"]))
{
    $user = new User($db);
    $user->register();
}

if (isset($_POST["connexion"]))
{
    $user = new User($db);
    $user->connexion();
}

if (isset($_POST["deconnexion"]))
{
    unset($_SESSION["id"]);
    $_SESSION["INFOS"] = "Vous êtes à présent déconnecté.";
}

if (isset($_SESSION["id"]))
{
    $user = new User($db, $_SESSION["id"]);
    // var_dump($user->getLogin());
    // var_dump($user->getEmail());
    // var_dump($user->getFirstname());
    // var_dump($user->getLastname());
    // var_dump($user->getBirthdate());
    // var_dump($user->getGender());
    // var_dump($user->getOrientation());
    // var_dump($user->getAvatar());
    // var_dump($user->getBio());
    // var_dump($user->getActivationToken());
    // var_dump($user->getStreet());
    // var_dump($user->getCity());
    // var_dump($user->getCityId());
    // var_dump($user->getCityCP());
    // var_dump($user->getDepartementId());
    // var_dump($user->getDepartementNum());
    // var_dump($user->getDepartement());
    // var_dump($user->getRegionId());
    // var_dump($user->getRegion());
    // var_dump($user->getPaysId());
    // var_dump($user->getPays());
}


// ERREURS ET INFOS
if (isset($_SESSION["ERROR"]))
{
    foreach ($_SESSION["ERROR"] as $error)
    {
        echo $error . "<br />";
    }
    unset($_SESSION["ERROR"]);
}

if (isset($_SESSION["INFOS"]))
{
    echo $_SESSION["INFOS"];
    unset($_SESSION["INFOS"]);
}

$address = new Address($db);

$cities = $address->getCities();
$departements = $address->getDepartements();
$regions = $address->getRegions();
$pays = $address->getPays();

require_once("views/registration.html");
?>