<?php
session_start();
require_once("includes/database.php");
require_once("includes/User.php");
require_once("includes/FormValidator.php");
require_once("includes/Address.php");
require_once("includes/Display.php");

if (!isset($_GET["id"]) || !isset($_SESSION["id"]))
{
    header("Location: index.php");
}

$user = new User($db, $_GET["id"]);

if (empty($user->getLogin()))
{
    header("Location: index.php");
}
require_once("views/profil.html");
?>