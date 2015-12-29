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
        $message .= "Coucou princesse !";
        $message .= "</body></html>";

        mail($to, $subject, $message, $headers, "-r $from");
        echo "<p>Message sent to $to</p>";
 ?>