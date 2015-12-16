<!DOCTYPE html>
<html>
<head>
    <title>My_Meetic - Profil</title>
    <link rel="stylesheet" type="text/css" href="style/style.css" />
</head>
<body>
    <div id="main_wrapper">
        <?php require_once("includes/header.php"); ?>

        <section id="profil_section">
            <div id="profil_block">
                <h2>Profil de <?php echo $user->getFirstname(); ?></h2>
                <div id="profil_avatar"><img src="<?php echo $user->getAvatar(); ?>" alt="<?php echo $user->getLogin(); ?>"></div>

                <ul id="profil_details">
                    <li><span class="details">Pseudo : </span><?php echo $user->getLogin(); ?></li>
                    <li><span class="details">Sexe : </span><?php echo $user->getGenderText(); ?></li>
                    <li><span class="details">Je recherche : </span>des <?php echo $user->getOrientationText(); ?></li>
                    <li><span class="details">Age : </span><?php echo $user->getAge(); ?> ans</li>
                    <li><span class="details">Ville : </span><?php echo $user->getCity(); ?></li>
                    <li><span class="details">Département : </span><?php echo $user->getDepartement(); ?></li>
                    <li><span class="details">Région : </span><?php echo $user->getRegion(); ?></li>
                    <li><span class="details">Pays : </span><?php echo $user->getPays(); ?></li>
                    <li><span class="details">Biographie : </span><?php echo $user->getBio(); ?></li>
                </ul>
                <a href="messagerie.php?to=<?php echo $user->getId(); ?>" id="contact_link">Envoyer un message à <?php echo $user->getFirstname(); ?></a>
            </div>
        </section>

        <?php require_once("includes/footer.php"); ?>
    </div>
</body>
</html>