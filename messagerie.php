<?php
session_start();
require_once("includes/database.php");
require_once("includes/User.php");
require_once("includes/FormValidator.php");
require_once("includes/Display.php");

if (!isset($_SESSION["id"]))
{
    header("Location: index.php");
}

$user = new User($db, $_SESSION["id"]);

if (isset($_GET["action"]) && $_GET["action"] === "write")
{
    $destinataire;
}

require_once("views/messagerie.html");
?>