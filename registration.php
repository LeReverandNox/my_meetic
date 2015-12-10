<?php
session_start();
if (isset($_SESSION["id"]))
{
    header("Location: index.php");
}

require_once("includes/database.php");
require_once("includes/User.php");

if (isset($_POST["registration"]))
{
    $user = new User($db);

    $user->setLogin("Looool");
    $user->setEmail("trololo@gmail.com");
    $user->generateToken();
    $user->register();

    // echo "Bonjour, je m'apelle " . $user->getLogin() . " et mon token est le suivant : " . $user->getActivationToken();
    // echo "On va s'inscrire";
}



require_once("views/registration.html");
?>