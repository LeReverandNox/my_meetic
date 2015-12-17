<?php
abstract class Display
{
    public static function INFOS()
    {
        if (isset($_SESSION["INFOS"]))
        {
            echo "<ul class=\"infos_list\">";
            echo "<li>" . $_SESSION["INFOS"] . "</li>";
            echo "</ul>";
            unset($_SESSION["INFOS"]);
        }
    }

    public static function ERROR()
    {
        if (isset($_SESSION["ERROR"]))
        {
            echo "<ul class=\"errors_list\">";
            foreach ($_SESSION["ERROR"] as $error)
            {
                echo "<li>" . $error . "</li>";
            }
            echo "</ul>";
            unset($_SESSION["ERROR"]);
        }
    }
}
?>