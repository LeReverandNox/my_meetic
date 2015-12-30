<header id="header">
    <div id="header_title">
        <h1><a href="index.php" title="Viens pécho !"><img src="style/logo.png" alt="Le logo de My_Meetic"></a></h1>
        <h2>(Better than Meetic !)</h2>
    </div>
    <div id="header_navlog">
        <nav>
            <ul>
                <?php
                if (isset($_SESSION["id"]))
                {
                    ?>
                    <li><a href="index.php">Accueil</a> |</li>
                    <li><a href="profil.php?id=<?php echo $_SESSION["id"]; ?>">Mon profil</a> |</li>
                    <li><a href="account.php">Mon compte</a> |</li>
                    <li><a href="recherche.php">Recherche</a> |</li>
                    <li><a href="messagerie.php">Messagerie
                    <?php if ($nb = Message::countNewMessages($db, $user->getId())): ?>
                        <?php echo "(" . $nb . ")"; ?>
                    <?php endif ?>
                    </a></li>
                    <?php
                }
                ?>
            </ul>
        </nav>

        <div id="header_user">
            <?php
            if (isset($_SESSION["id"]))
            {
                ?>
                <p id="header_username">Bienvenue <?php echo $user->getLogin(); ?> !</p>
                <ul>
                    <li><a href="index.php?mode=deconnexion" class="deco_link">Déconnexion</a></li>
                </ul>
                <?php
            }
            ?>
        </div>
    </div>
</header>