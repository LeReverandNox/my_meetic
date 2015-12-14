<?php
session_start();
require_once("includes/database.php");
require_once("includes/User.php");
require_once("includes/FormValidator.php");

if (isset($_GET["action"]) && $_GET["action"] === "activate")
{
    if (!empty($_GET["id"]))
    {
        $user= new User($db, intval($_GET["id"]));
        $user->activateUser();

        echo $_SESSION["INFOS"];
        unset($_SESSION["INFOS"]);
    }
    else
    {
        header("Location: ../index.php");
    }
}
?>