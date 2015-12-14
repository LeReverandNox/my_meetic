<?php
session_start();
require_once("includes/database.php");
require_once("includes/User.php");
require_once("includes/FormValidator.php");

if (isset($_POST["registration"]))
{
    $user = new User($db);
    $user->register();
}

if (isset($_POST["connexion"]))
{
    $user = new User($db);
    $user->connexion();
    $_SESSION["INFOS"] = "Vous êtes à présent connecté.";
}

if (isset($_POST["deconnexion"]))
{
    unset($_SESSION["id"]);
    $_SESSION["INFOS"] = "Vous êtes à présent déconnecté.";
}

if (isset($_SESSION["id"]))
{
    $user = new User($db, $_SESSION["id"]);

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

require_once("views/registration.html");
?>