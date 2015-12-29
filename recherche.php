<?php
session_start();
require_once("includes/database.php");
require_once("includes/User.php");
require_once("includes/FormValidator.php");
require_once("includes/Address.php");
require_once("includes/Display.php");
require_once("includes/Search.php");
require_once("includes/Message.php");

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
    $result_users = $search->searchUsers($user->getId());
}

require_once("views/recherche.html");
?>