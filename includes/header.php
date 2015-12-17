<header id="header">
    <div id="header_title">
        <h1><a href="index.php" title="Viens pécho !">My_Meetic</a></h1>
        <h2>(Better than Meetic !)</h2>
    </div>
    <div id="header_navlog">
        <nav>
            <ul>
                <?php
                if (isset($_SESSION["id"]))
                {
                    ?>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="profil.php?id=<?php echo $_SESSION["id"]; ?>">Profil</a></li>
                    <li><a href="account.php">Mon compte</a></li>
                    <li><a href="recherche.php">Recherche</a></li>
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
                    <li><a href="index.php?mode=deconnexion">Déconnexion</a></li>
                </ul>
                <?php
            }
            ?>
        </div>
    </div>
</header>