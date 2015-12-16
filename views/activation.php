<!DOCTYPE html>
<html>
<head>
    <title>My_Meetic - Activation</title>
    <link rel="stylesheet" type="text/css" href="style/style.css" />
</head>
<body>
    <div id="main_wrapper">
        <?php require_once("includes/header.php"); ?>

        <section id="profil_section">
            <p>
                <?php
                    echo $_SESSION["INFOS"];
                    unset($_SESSION["INFOS"]);
                ?>
            </p>
        </section>

        <?php require_once("includes/footer.php"); ?>
    </div>
</body>
</html>