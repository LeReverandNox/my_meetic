<?php
        try {
            $database = new PDO('mysql:host=localhost;dbname=laidet_r_my_meetic;charset=utf8', 'root', '#W0u1d@Y0u&K1nd1y$');
        }
        catch (Exception $e) {
            Die ('Erreur : ' . $e->getMessage());
        }

        $sql = "SELECT departements.id AS 'id_departement', departements.departement_num AS 'departement_num'
        FROM departements";
        $queryDepartement = $database->prepare($sql);
        $queryDepartement->execute();
        $departements =  $queryDepartement->fetchAll(PDO::FETCH_ASSOC);

        ?>
            <pre>
                <?php
                    // print_r($departements);
                ?>
            </pre>
        <?php

        $queryUpdateVille = $database->prepare("UPDATE villes_france_free
            SET id_departement = :id_departement
            WHERE ville_departement = :departement_num");

        foreach ($departements as $dep)
        {
            $queryUpdateVille->bindParam(":id_departement", $dep["id_departement"], PDO::PARAM_INT);
            $queryUpdateVille->bindParam(":departement_num", $dep["departement_num"], PDO::PARAM_STR);
            $queryUpdateVille->execute();

            echo "Departement " .  $dep['departement_num'] . "edité <br />";
        }
 ?>