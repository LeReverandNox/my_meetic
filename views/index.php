<!DOCTYPE html>
<html>
<head>
    <title>My_Meetic - Index</title>
    <link rel="stylesheet" type="text/css" href="style/style.css" />
</head>
<body>
<?php require_once("includes/header.php"); ?>
<section>
<?php if (isset($_SESSION["INFOS"])): ?>
    <?php echo $_SESSION["INFOS"];
    unset($_SESSION["INFOS"]); ?>
<?php endif ?>
<p>Voila l'index</p>
</section>
<?php require_once("includes/footer.php"); ?>
</body>
</html>