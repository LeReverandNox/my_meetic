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

if (isset($_POST["reset"]))
{
    $user = new User($db);
    if ($user->resetPassword())
    {
        header("Location: inscription.php");
        return true;
    }
}

if (isset($_SESSION["id"]))
{
    header("Location: index.php");
}

$address = new Address($db);

$cities = $address->getCities();
$departements = $address->getDepartements();
$regions = $address->getRegions();
$pays = $address->getPays();

require_once("views/inscription.php");
?>