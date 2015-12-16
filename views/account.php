<!DOCTYPE html>
<html>
<head>
    <title>My_Meetic - Mon compte</title>
    <link rel="stylesheet" type="text/css" href="style/style.css" />
</head>
<body>
    <div id="main_wrapper">

        <?php require_once("includes/header.php"); ?>

        <section id="profil_section">
            <div id="profil_block">

                <h2>Mon compte</h2>
                <?php if (isset($_SESSION["INFOS"])): ?>
                    <ul>
                        <li><?php echo $_SESSION["INFOS"]; ?></li>
                    </ul>
                    <?php unset($_SESSION["INFOS"]); ?>
                <?php endif ?>

                <?php if (isset($_GET["mode"]) && $_GET["mode"] === "edit"): ?>

                    <div id="account_edit">

                        <?php if (isset($_SESSION["ERROR"])): ?>
                            <ul>
                                <?php foreach ($_SESSION["ERROR"] as $error): ?>
                                    <li><?php echo $error; ?></li>
                                <?php endforeach ?>
                            </ul>
                            <?php unset($_SESSION["ERROR"]); ?>
                        <?php endif ?>
                        <form action="" method="POST" id="edit_profile" enctype="multipart/form-data">
                            <div id="avatar_upload">
                                <img src="<?php echo $user->getAvatar(); ?>" alt="<?php echo $dataUser["firstname"]; ?>'s avatar" id="edit_profile_avatar">

                                <ul>
                                    <li>Max. dimensions : 150*150px</li>
                                    <li>Max. weight : 1MB</li>
                                    <li>Type : jpeg, png</li>
                                    <li>
                                        <input type="file" accept="image/*" name="avatar" id="avatar" />
                                    </li>
                                </ul>

                            </div>
                            <ul>
                                <li>
                                    <label for="login">Pseudo : </label>
                                    <input type="text" name="login" id="login" placeholder="Ex: Anthony, Mathias..." value="<?php echo $user->getLogin(); ?>" />
                                </li>

                                <li>
                                    <label for="email1">Email : </label>
                                    <input type="email" name="email1" id="email1" placeholder="Ex: mathias@epitech.eu" value="<?php echo $user->getEmail(); ?>" />
                                </li>

                                <li>
                                    <label for="email2">Confirmation d'email : </label>
                                    <input type="email" name="email2" id="email2" placeholder="A remplir uniquement en cas de changement" />
                                </li>

                                <li>
                                    <label for="password1">Nouveau mot de passe : </label>
                                    <input type="password" name="password1" id="password1" placeholder="6 caractères minimum" />
                                </li>

                                <li>
                                    <label for="password2">Confirmation du mot de passe : </label>
                                    <input type="password" name="password2" id="password2"  />
                                </li>

                                <li>
                                    <label for="firstname">Prénom : </label>
                                    <input type="text" name="firstname" id="firstname" value="<?php echo $user->getFirstname(); ?>" />
                                </li>

                                <li>
                                    <label for="lastname">Nom : </label>
                                    <input type="text" name="lastname" id="lastname" value="<?php echo $user->getLastname(); ?>" />
                                </li>

                                <li>
                                    <label for="birthdate">Date de naissance : </label>
                                    <input type="date" name="birthdate" id="birthdate" placeholder="AAAA/MM/JJ" value="<?php echo $user->getBirthdate(); ?>" />
                                </li>

                                <li>
                                    <label for="gender">Je suis : </label>
                                    <select name="gender" id="gender">
                                        <option value="">Sélectionner dans la liste</option>
                                        <option value="0" <?php if ($user->getGender() == 0) echo "selected"; ?>>un homme</option>
                                        <option value="1" <?php if ($user->getGender() == 1) echo "selected"; ?>>une femme</option>
                                    </select>
                                </li>

                                <li>
                                    <label for="orientation">Et je recherche : </label>
                                    <select name="orientation" id="orientation">
                                        <option value="">Sélectionner dans la liste</option>
                                        <option value="0" <?php if ($user->getOrientation() == 0) echo "selected"; ?>>un homme</option>
                                        <option value="1" <?php if ($user->getOrientation() == 1) echo "selected"; ?>>une femme</option>
                                    </select>
                                </li>

                                <li>
                                    <label>Adresse : </label>
                                    <input type="text" name="street" id="street" placeholder="Ex: Rue Fructidor" value="<?php echo $user->getStreet(); ?>" />
                                </li>

                                <li>
                                    <label for="city">Ville : </label>
                                    <input type="text" list="cities" name="city" placeholder="Ex: 93400 - Saint-Ouen" value="<?php echo $user->getCityCP() . " - " . $user->getCity(); ?>" />
                                    <datalist id="cities">
                                        <?php foreach ($cities as $city): ?>

                                            <option value="<?php echo $city["CP"] ?> - <?php echo $city["nom"] ?>"></option>

                                        <?php endforeach ?>
                                    </datalist>
                                </li>

                                <li>
                                    <label for="departement">Departement : </label>
                                    <input type="text" list="departements" name="departement" placeholder="Ex: 93 - Seine-Saint-Denis" value="<?php echo $user->getDepartementNum() . " - " . $user->getDepartement(); ?>" />
                                    <datalist id="departements">
                                        <?php foreach ($departements as $departement): ?>
                                            <option value="<?php echo $departement["num"] ?> - <?php echo $departement["nom"] ?>"></option>
                                        <?php endforeach ?>
                                    </datalist>
                                </li>

                                <li>
                                    <label for="regions">Region : </label>
                                    <input type="text" list="regions" name="region" placeholder="Ex: Île-de-France" value="<?php echo $user->getRegion(); ?>" />
                                    <datalist id="regions">
                                        <?php foreach ($regions as $region): ?>
                                            <option value="<?php echo $region["nom"] ?>"></option>
                                        <?php endforeach ?>
                                    </datalist>
                                </li>
                                <li>
                                    <label for="bio">Biographie : </label>
                                    <textarea name="bio" id="bio"><?php echo $user->getBio(); ?></textarea>
                                </li>

                                <li id="submit">
                                    <input type="hidden" name="edit" />
                                    <input type="submit" value="Modify" />
                                </li>
                            </ul>
                        </form>
                    </div>

                <?php elseif (isset($_GET["mode"]) && $_GET["mode"] === "delete"): ?>

                    <div id="account_delete">
                    <ul>
                        <li><p>Vous êtes sur le point de supprimer DÉFINITIVEMENT votre compte</li>
                        <li>Pas de remord ?</li>
                        <form action="" method="POST">
                            <button name="delete" value="0">Non</button>
                            <button name="delete" value="1">Oui</button>
                        </form>
                    </ul>

                    </div>

                <?php else: ?>
                    <div id="account_resume">
                        <h3><?php echo $user->getFirstname() . " " . $user->getLastname(); ?></h3>
                        <div id="account_image">
                            <img src="<?php echo $user->getAvatar(); ?>" alt="<?php echo $user->getLogin(); ?>" />
                        </div>
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
                            <li><span class="details">Date d'inscription : </span><?php echo $user->getRegisterDate(); ?></li>
                        </ul>
                        <a href="account.php?mode=edit" id="edit_link">Editer vos informations</a>
                        <a href="account.php?mode=delete" id="delete_link">Supprimer votre compte</a>
                    </div>
                    <div id="account_edit">
                    <?php endif ?>

                </div>
            </div>
        </section>
        <?php require_once("includes/footer.php"); ?>
    </div>
</body>
</html>