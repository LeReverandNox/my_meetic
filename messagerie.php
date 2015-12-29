<?php
session_start();
require_once("includes/database.php");
require_once("includes/User.php");
require_once("includes/FormValidator.php");
require_once("includes/Display.php");
require_once("includes/Message.php");

if (!isset($_SESSION["id"]))
{
    header("Location: index.php");
}

$user = new User($db, $_SESSION["id"]);
$messages = new Message($db);

// ECRITURE
if (isset($_GET["mode"]) && $_GET["mode"] === "write")
{
    if (isset($_GET["to"]))
    {
        $recipient = new User($db, intval($_GET["to"]));
        $_POST["recipient"] = $recipient->getLogin();
    }
}

// LECTURE
if (isset($_GET["mode"]) && $_GET["mode"] === "read" && isset($_GET["id"]))
{
    $read_msg = Message::readMessage($db, $user->getId(), $_GET["id"]);

    if (empty($_SESSION["ERROR"]))
    {
        $author = new User($db, $read_msg->getAuthorId());
        $recipient = new User($db, $read_msg->getRecipientId());
    }
    else
    {
        header("Location: messagerie.php");
        return false;
    }
}

// MESSAGES ENVOYES
if (isset($_GET["mode"]) && $_GET["mode"] === "sentbox")
{
    $sent_messages = Message::getSentMessages($db, $user->getId());
}

// MESSAGES SUPPRIMES
if (isset($_GET["mode"]) && $_GET["mode"] === "trashbox")
{
    $deleted_messages = Message::getDeletedMessages($db, $user->getId());
}

// ENVOI D'UN MESSAGE
if (isset($_POST["send"]))
{
    if ($messages->sendMessage($user->getId()))
    {
        header("Location: messagerie.php");
        return true;
    }
}

// SUPPRESSION
if (isset($_GET["action"]) && $_GET["action"] === "delete" && isset($_GET["id"]))
{
    $msg_to_delete = Message::prepareMove($db, $user->getId(), $_GET["id"]);
    if (empty($_SESSION["ERROR"]))
    {
        $msg_to_delete->deleteMessage();
        header("Location: messagerie.php");
        return true;
    }
}

// RESTAURATION
if (isset($_GET["action"]) && $_GET["action"] === "restore" && isset($_GET["id"]))
{
    $msg_to_restore = Message::prepareMove($db, $user->getId(), $_GET["id"]);
    if (empty($_SESSION["ERROR"]))
    {
        $msg_to_restore->restoreMessage();
        header("Location: messagerie.php?mode=trashbox");
        return true;
    }
}

// INBOX
$inbox = Message::getReceivedMessages($db, $user->getId());

require_once("views/messagerie.html");
?>