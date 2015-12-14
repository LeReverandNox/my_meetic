<?php
        try {
            $db = new PDO('mysql:host=localhost;dbname=laidet_r_my_meetic;charset=utf8', 'laidet_r', '94Wq6E3Q8DzbSWR401NO', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        }
        catch (Exception $e) {
            Die ('Erreur : ' . $e->getMessage());
        }
 ?>
