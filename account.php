<?php
session_start();
require_once("includes/database.php");
require_once("includes/User.php");

if ($_GET["action"] === "activate")
{
    $user= new User($db, intval($_GET["id"]));
    $user->activateUser(htmlspecialchars($_GET["token"]));

    echo $_SESSION["INFOS"];
    unset($_SESSION["INFOS"]);
}
?>