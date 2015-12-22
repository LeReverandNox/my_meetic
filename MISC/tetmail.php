<?php

        $from = "lereverandnox@gmail.com";
        $to = "lybrys@gmail.com";
        $activation_link = "http://localhost/S1%20-%20PHP/PHP_my_meetic/activation.php?action=activate&id=" . "lol" . "&token=" . "loool";

        $headers = "From: <" . $from . ">\r\n";
        $headers .= "Reply-To: <" . $from . ">\r\n";
        $headers .= "Return-Path:  < " . $from . " >\r\n";
        $headers .= "Sender: <" . $from . ">\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Date: <" . date("r", time()). ">\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

        $subject = "Activation de votre compte My_Meetic";
        $message ="<html><body>";
        $message .= "<p>Bonjour, merci de vous être inscrit sur <a href='http://localhost/S1%20-%20PHP/PHP_my_meetic/'>My_Meetic</a> !</p>";
        $message .= "<p>Conservez cet e-mail dans vos archives. Les informations de votre compte sont les suivantes :<br />";
        $message .= "Nom d'utilisateur : " . "bobby" . "<br />";
        $message .= "----------------------------<br />";
        $message .= "Veuillez cliquer sur le lien suivant afin d'activer votre compte :<br />";
        $message .= "<a href='" . $activation_link . "''>" . $activation_link ."</a></p>";
        $message .= "<p>Votre mot de passe est encrypté dans notre base de donnée et ne pourra pas être récuperé.<br />";
        $message .= "En cas d'oubli, vous pourrez le réinitialiser depuis notre site grâce à votre adresse e-mail</p>";
        $message .= "<p>A très vite parmis nous !</p>";
        $message .= "</body></html>";

        mail($to, $subject, $message, $headers, "-r $from");

 ?>