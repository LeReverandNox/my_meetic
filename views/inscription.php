<!DOCTYPE html>
<html>
<head>
    <title>My_Meetic - Inscription</title>
    <link rel="stylesheet" type="text/css" href="style/style.css" />
</head>
<body>
    <div id="main_wrapper">
        <?php require_once("includes/header.php"); ?>

        <section>
         <?php if (isset($_SESSION["INFOS"]))
         { ?>
         <ul>
            <li><?php echo $_SESSION["INFOS"]; ?></li>
        </ul>
        <?php }
        unset($_SESSION["INFOS"]);
        ?>
        <?php if (isset($_SESSION["ERROR"]))
        { ?>
        <ul id="errors">
            <?php foreach ($_SESSION["ERROR"] as $error)
            { ?>
            <li><?php echo $error ?></li>
            <?php }
            unset($_SESSION["ERROR"]); ?>
        </ul>
        <?php } ?>

        <?php if (isset($_GET["mode"]) && $_GET["mode"] === "reset"): ?>

            <div class="registration_block">
                <h2>Réinitialisation du mot de passe</h2>
                <p>Si vous avez oublié votre mot de passe, vous pouvez demander à en recevoir un nouveau par email.</p>

                <form method="POST" action="">
                    <ul>
                        <li>
                            <label for="connexion_login">Pseudo : </label>
                            <input type="text" name="connexion_login" id="connexion_login" />
                        </li>
                        <li>
                            <label for="connexion_email">Email :</label>
                            <input type="email" name="connexion_email" id="connexion_email" />
                        </li>
                        <li>
                            <input type="hidden" name="reset" />
                            <input type="submit" value="Réinitialiser" />
                        </li>
                    </ul>
                </form>
            </div>

        <?php else: ?>
            <div class="registration_block">
                <h2>Connexion</h2>
                <form method="POST" action="index.php">
                    <label for="connexion_login">Pseudo : </label>
                    <input type="text" name="connexion_login" id="connexion_login" />

                    <label for="connexion_password">Mot de passe : </label>
                    <input type="password" name="connexion_password" id="connexion_password" />

                    <input type="hidden" name="connexion" />
                    <input type="submit" value="Connexion" />
                </form>
                <a href="inscription.php?mode=reset">Mot de passe oublié ?</a>
            </div>

            <div id="registration_block">
                <h2>Inscrivez-vous !</h2>
                <form method="POST" action="#" id="registration_form">
                    <ul>
                        <li>
                            <label for="login">Pseudo : </label>
                            <input type="text" name="login" id="login" placeholder="Ex: Anthony, Mathias..." value="<?php if (isset($_POST["login"])) echo htmlspecialchars($_POST["login"]); ?>" />
                        </li>

                        <li>
                            <label for="email1">Email : </label>
                            <input type="email" name="email1" id="email1" placeholder="Ex: mathias@epitech.eu" value="<?php if (isset($_POST["email1"])) echo htmlspecialchars($_POST["email1"]); ?>" />
                        </li>

                        <li>
                            <label for="email2">Confirmation d'email : </label>
                            <input type="email" name="email2" id="email2" value="<?php if (isset($_POST["email2"])) echo htmlspecialchars($_POST["email2"]); ?>" />
                        </li>

                        <li>
                            <label for="password1">Mot de passe : </label>
                            <input type="password" name="password1" id="password1" placeholder="6 caractères minimum" value="<?php if (isset($_POST["password1"])) echo htmlspecialchars($_POST["password1"]); ?>" />
                        </li>

                        <li>
                            <label for="password2">Confirmation du mot de passe : </label>
                            <input type="password" name="password2" id="password2" value="<?php if (isset($_POST["password2"])) echo htmlspecialchars($_POST["password2"]); ?>" />
                        </li>

                        <li>
                            <label for="firstname">Prénom : </label>
                            <input type="text" name="firstname" id="firstname" value="<?php if (isset($_POST["firstname"])) echo htmlspecialchars($_POST["firstname"]); ?>" />
                        </li>

                        <li>
                            <label for="lastname">Nom : </label>
                            <input type="text" name="lastname" id="lastname" value="<?php if (isset($_POST["lastname"])) echo htmlspecialchars($_POST["lastname"]); ?>" />
                        </li>

                        <li>
                            <label for="birthdate">Date de naissance : </label>
                            <input type="date" name="birthdate" id="birthdate" placeholder="AAAA/MM/JJ" value="<?php if (isset($_POST["birthdate"])) echo htmlspecialchars($_POST["birthdate"]); ?>" />
                        </li>

                        <li>
                            <label for="gender">Je suis : </label>
                            <select name="gender" id="gender">
                                <option value="">Sélectionner dans la liste</option>
                                <option value="0" <?php if (isset($_POST["gender"]) && $_POST["gender"] == "0") echo "selected"; ?>>un homme</option>
                                <option value="1" <?php if (isset($_POST["gender"]) && $_POST["gender"] == "1") echo "selected"; ?>>une femme</option>
                            </select>
                        </li>

                        <li>
                            <label for="orientation">Et je recherche : </label>
                            <select name="orientation" id="orientation">
                                <option value="">Sélectionner dans la liste</option>
                                <option value="0" <?php if (isset($_POST["orientation"]) && $_POST["orientation"] == "0") echo "selected"; ?>>un homme</option>
                                <option value="1" <?php if (isset($_POST["orientation"]) && $_POST["orientation"] == "1") echo "selected"; ?>>une femme</option>
                            </select>
                        </li>

                        <li>
                            <label>Adresse : </label>
                            <input type="text" name="street" id="street" placeholder="Ex: Rue Fructidor" value="<?php if (isset($_POST["street"])) echo htmlspecialchars($_POST["street"]); ?>" />
                        </li>

                        <li>
                            <label for="city">Ville : </label>
                            <input type="text" list="cities" name="city" placeholder="Ex: 93400 - Saint-Ouen" value="<?php if (isset($_POST["city"])) echo htmlspecialchars($_POST["city"]); ?>" />
                            <datalist id="cities">
                                <?php foreach ($cities as $city): ?>

                                    <option value="<?php echo $city["CP"] ?> - <?php echo $city["nom"] ?>"></option>

                                <?php endforeach ?>
                            </datalist>
                        </li>

                        <li>
                            <label for="departement">Departement : </label>
                            <input type="text" list="departements" name="departement" placeholder="Ex: 93 - Seine-Saint-Denis" value="<?php if (isset($_POST["departement"])) echo htmlspecialchars($_POST["departement"]); ?>" />
                            <datalist id="departements">
                                <?php foreach ($departements as $departement): ?>
                                    <option value="<?php echo $departement["num"] ?> - <?php echo $departement["nom"] ?>"></option>
                                <?php endforeach ?>
                            </datalist>
                        </li>

                        <li>
                            <label for="regions">Region : </label>
                            <input type="text" list="regions" name="region" placeholder="Ex: Île-de-France" value="<?php if (isset($_POST["region"])) echo htmlspecialchars($_POST["region"]); ?>" />
                            <datalist id="regions">
                                <?php foreach ($regions as $region): ?>
                                    <option value="<?php echo $region["nom"] ?>"></option>
                                <?php endforeach ?>
                            </datalist>
                        </li>

                        <li id="submit">
                            <input type="hidden" name="registration" />
                            <input type="submit" value="S'inscrire" />
                        </li>
                    </ul>
                </form>
            </div>
        <?php endif ?>
    </section>

    <?php require_once("includes/footer.php"); ?>
</div>
</body>
</html>