<?php
session_start();
require_once("includes/database.php");
require_once("includes/User.php");
require_once("includes/FormValidator.php");

if (isset($_POST["registration"]))
{
    $user = new User($db);
    $validator = new FormValidator($db);

    $validator->validateLogin($_POST["login"]);
    $user->setLogin($validator->getLogin());

    $validator->validateEmail($_POST["email1"], $_POST["email2"]);
    $user->setEmail($validator->getEmail());

    $validator->validatePassword($_POST["password1"], $_POST["password2"]);
    $user->setPassword($validator->getPassword());

    $validator->validateFirstname($_POST["firstname"]);
    $user->setFirstname($validator->getFirstname());

    $validator->validateLastname($_POST["lastname"]);
    $user->setLastname($validator->getLastname());

    $validator->validateBirthdate($_POST["birthdate"]);
    $user->setBirthdate($validator->getBirthdate());

    $validator->validateGender($_POST["gender"]);
    $user->setGender($validator->getGender());

    $validator->validateStreet($_POST["ad_number"], $_POST["street"]);
    $user->setStreet($validator->getStreet());

    $validator->validateCity($_POST["city"]);
    $user->setCityId($validator->getCityId());

    $validator->validateDepartement($_POST["departement"]);
    $user->setDepartementId($validator->getDepartementId());

    $validator->validateRegion($_POST["region"]);
    $user->setRegionId($validator->getRegionId());

    if (empty($_SESSION["ERROR"]))
    {
        $user->register();
    }
}

if (isset($_POST["connexion"]))
{
    $user = new User($db);
    $validator = new FormValidator($db);

    $validator->validateLogLogin($_POST["connexion_login"]);
    $user->setLogin($validator->getLogin());
    $validator->validateLogPassword($_POST["connexion_password"]);
    $user->setPassword($validator->getPassword());;

    if (empty($_SESSION["ERROR"]))
    {
        $user->connexion();
    }
}

if (isset($_POST["deconnexion"]))
{
    unset($_SESSION["id"]);
}

if (isset($_SESSION["id"]))
{
    echo "On est connecté<br />";
    $user = new User($db, $_SESSION["id"]);
    echo $user->getLogin();
}



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