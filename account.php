<?php
session_start();
require_once("includes/database.php");
require_once("includes/User.php");
require_once("includes/FormValidator.php");
require_once("includes/Address.php");

if (!isset($_SESSION["id"]))
{
    header("Location: index.php");
}

$user = new User($db, $_SESSION["id"]);

if (isset($_GET["mode"]) && $_GET["mode"] === "edit")
{
    $address = new Address($db);

    $cities = $address->getCities();
    $departements = $address->getDepartements();
    $regions = $address->getRegions();
    $pays = $address->getPays();

    if (isset($_POST["edit"]))
    {
        $user->update();
        $user = new User($db, $_SESSION["id"]);

        if (isset($_SESSION["INFOS"]))
        {
            header("Location: account.php");
            return true;
        }
    }
}

if (isset($_POST["delete"]))
{
    if ($_POST["delete"] == 1)
    {
        $user->disableUser();
        unset($_SESSION["id"]);
        header("Location: inscription.php");
        return true;
    }
    else
    {
        header("Location: account.php");
        return true;
    }
}
require_once("views/account.php");
?>