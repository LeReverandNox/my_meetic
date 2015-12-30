<?php
session_start();
require_once("includes/database.php");
require_once("includes/User.php");
require_once("includes/FormValidator.php");
require_once("includes/Display.php");
require_once("includes/Message.php");

if (isset($_GET["action"]) && $_GET["action"] === "activate")
{
    if (!empty($_GET["id"]))
    {
        $user= new User($db, intval($_GET["id"]));
        $user->activateUser();
    }
    else
    {
        header("Location: index.php");
    }
}
else
{
    header("Location: index.php");
}

require_once("views/activation.html");
?>