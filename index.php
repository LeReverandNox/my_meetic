<?php
session_start();
require_once("includes/database.php");
require_once("includes/User.php");
require_once("includes/FormValidator.php");
require_once("includes/Address.php");

if (isset($_POST["connexion"]))
{
    $user = new User($db);
    $user->connexion();
}

if (!isset($_SESSION["id"]))
{
    header("Location: inscription.php");
}

$user = new User($db, $_SESSION["id"]);

require_once("views/index.php");
?>