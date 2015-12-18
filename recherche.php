<?php
session_start();
require_once("includes/database.php");
require_once("includes/User.php");
require_once("includes/FormValidator.php");
require_once("includes/Address.php");
require_once("includes/Display.php");
require_once("includes/Search.php");

if (!isset($_SESSION["id"]))
{
    header("Location: index.php");
}

$user = new User($db, $_SESSION["id"]);


$address = new Address($db);

$cities = $address->getCities();
$departements = $address->getDepartements();
$regions = $address->getRegions();
$pays = $address->getPays();

if (isset($_POST["recherche"]))
{
    $search = new Search($db);
    $users = $search->searchUsers();
    foreach ($users as $resultUser)
    {
        // echo "<br />" . $resultUser->getLogin() . "<br />" ;
        // echo $resultUser->getEmail();
        echo "<img src='" . $resultUser->getAvatar() . "'/>";
    }
}

require_once("views/recherche.html");
?>