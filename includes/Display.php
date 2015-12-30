<?php
abstract class Display
{
    public static function INFOS()
    {
        if (isset($_SESSION["INFOS"]))
        {
            echo "<div class=\"display_holder\">";
            echo "<ul class=\"infos_list\">";
            echo "<li>" . $_SESSION["INFOS"] . "</li>";
            echo "</ul>";
            echo "</div>";
            unset($_SESSION["INFOS"]);
        }
    }

    public static function ERROR()
    {
        if (isset($_SESSION["ERROR"]))
        {
            echo "<div class=\"display_holder\">";
            echo "<ul class=\"errors_list\">";
            foreach ($_SESSION["ERROR"] as $error)
            {
                echo "<li>" . $error . "</li>";
            }
            echo "</ul>";
            echo "</div>";
            unset($_SESSION["ERROR"]);
        }
    }
}
?>