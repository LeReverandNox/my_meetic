<?php
Class FormValidator
{
    public function __construct($db)
    {
        $this->_db = $db;
    }

    public function __destruct()
    {
        $this->_db = null;
    }

    public function validateLogin()
    {
        if (!empty($_POST["login"]) && strlen($_POST["login"]) >= 3)
        {
            if (strlen($_POST["login"]) < 30)
            {
                $login = htmlspecialchars(strtolower($_POST["login"]));

                $sql = "SELECT u.user_login FROM users AS u WHERE u.user_login = :login";
                $queryLogin = $this->_db->prepare($sql);
                $queryLogin->bindParam(":login", $login, PDO::PARAM_STR);
                $queryLogin->execute();
                $data = $queryLogin->fetch();
                $queryLogin->closeCursor();

                if ($data)
                {
                    $_SESSION["ERROR"]["login"] = "Ce pseudo est déja utilisé.";
                }
                else
                {
                    return $login;
                }
            }
            else
            {
                $_SESSION["ERROR"]["login"] = "Le pseudo indiqué est trop long.";
            }
        }
        else
        {
            $_SESSION["ERROR"]["login"] = "Le pseudo indiqué est trop court.";
        }
    }

    public function validateEmail()
    {
        if (!empty($_POST["email1"]) && strlen($_POST["email1"]) >= 3)
        {
            if (!empty($_POST["email2"]) && strlen($_POST["email2"]) >= 3)
            {
                $email1 = htmlspecialchars(strtolower($_POST["email1"]));
                $email2 = htmlspecialchars(strtolower($_POST["email2"]));

                if (strcmp($email1, $email2) == 0)
                {
                    if (strlen($email1 > 255))
                    {
                            $_SESSION["ERROR"]["email_long"] = "L'adresse e-mail indiquée est trop longue";
                    }
                    else
                    {
                        $sql = "SELECT u.user_email FROM users AS u WHERE u.user_email = :email";
                        $queryMail = $this->_db->prepare($sql);
                        $queryMail->bindValue(":email", $email1, PDO::PARAM_STR);
                        $queryMail->execute();
                        $data = $queryMail->fetch();
                        $queryMail->closeCursor();

                        if ($data)
                        {
                            $_SESSION["ERROR"]["email_exist"] = "L'adresse e-mail indiquée est déjà utilisée.";
                        }
                        else
                        {
                           return $email1;
                        }
                    }
                }
                else
                {
                    $_SESSION["ERROR"]["email_match"] = "L’adresse e-mail de confirmation indiquée ne correspond pas.";
                }
            }
            else
            {
                $_SESSION["ERROR"]["email2"] = "L’adresse e-mail de confirmation indiquée est trop courte.";
            }
        }
        else
        {
            $_SESSION["ERROR"]["email1"] = "L’adresse e-mail indiquée est trop courte.";
        }
    }

    public function validatePassword()
    {
        if (!empty($_POST["password1"]) && strlen($_POST["password1"]) >= 6)
        {
            if (!empty($_POST["password1"]) && strlen($_POST["password1"]) >= 6)
            {
                $password1 = $_POST["password1"];
                $password2 = $_POST["password2"];

                if (strcmp($password1, $password2) != 0)
                {
                    $_SESSION["ERROR"]["password_match"] = "Le mot de passe de confirmation indiqué ne correspond pas.";
                }
                else
                {
                    if (strlen($password1) > 50)
                    {
                        $_SESSION["ERROR"]["password_long"] = "Le mot de passe indiqué est trop long";
                    }
                    else
                    {
                        return md5($password1);
                    }
                }
            }
            else
            {
                $_SESSION["ERROR"]["password2"] = "Le mot de passe de confirmation indiqué est trop court.";
            }
        }
        else
        {
            $_SESSION["ERROR"]["password1"] = "Le mot de passe indiqué est trop court.";
        }
    }

    public function validateFirstname()
    {
        if (empty($_POST["firstname"]))
        {
            $_SESSION["ERROR"]["firstname"] = "Le prénom indiqué est trop court.";
        }
        else
        {
            $firstname = htmlspecialchars(ucwords($_POST["firstname"], " -"));
            if (strlen($firstname) > 255)
            {
                $_SESSION["ERROR"]["firstname_long"] = "Le prénom indiqué est trop long.";
            }
            else
            {
                return $firstname;
            }
        }
    }

    public function validateLastname()
    {
        if (empty($_POST["lastname"]))
        {
            $_SESSION["ERROR"]["lastname"] = "Le nom indiqué est trop court.";
        }
        else
        {
            $lastname = htmlspecialchars(ucwords($_POST["lastname"], " -"));
            if (strlen($lastname) > 255)
            {
                $_SESSION["ERROR"]["lastname_long"] = "Le nom indiqué est trop long.";
            }
            else
            {
                return $lastname;
            }
        }
    }

    public function validateBirthdate()
    {
        if (!empty($_POST["birthdate"]))
        {
            $regDate = "/^[0-9]{4}[-\/](0[1-9]|1[0-2])[-\/](0[1-9]|[1-2][0-9]|3[0-1])$/";
            $birthdate = htmlspecialchars($_POST["birthdate"]);
            if (preg_match($regDate, $birthdate))
            {
                $now = strtotime($birthdate);
                $now =  strtotime("+18 years", $now);

                if ($now >time())
                {
                    $_SESSION["ERROR"]["birthdate"] = "Vous devez avoir 18 ans ou plus pous vous inscrire sur My_Meetic.";
                }
                else
                {
                    return $birthdate;
                }
            }
            else
            {
                $_SESSION["ERROR"]["birthdate"] = "La date de naissance indiquée n'est pas au bon format (YYYY/MM/DD).";
            }
        }
        else
        {
            $_SESSION["ERROR"]["birthdate"] = "La date de naissance indiquée est trop courte.";
        }
    }

    public function validateGender()
    {
        if (isset($_POST["gender"]) && ($_POST["gender"] == "0" || $_POST["gender"] == "1" || $_POST["gender"] == "2"))
        {
            $gender = intval($_POST["gender"]);
            return $gender;
        }
        else
        {
            $_SESSION["ERROR"]["gender"] = "Veuillez indiquer votre sexe";
        }
    }

    public function validateOrientation()
    {
        if (isset($_POST["orientation"]) && ($_POST["orientation"] == "0" || $_POST["orientation"] == "1" || $_POST["orientation"] == "2"))
        {
            $orientation = intval($_POST["orientation"]);
            return $orientation;
        }
        else
        {
            $_SESSION["ERROR"]["orientation"] = "Veuillez indiquer votre orientation";
        }
    }

    public function validateStreet()
    {

        if (!empty($_POST["street"]))
        {
            $street = htmlspecialchars($_POST["street"]);
            if (strlen($street) >255)
            {
                $_SESSION["ERROR"]["street"] = "La rue indiquée est trop longue.";
            }
            else
            {
                return $street;
            }
        }
        if (empty($_POST["street"]))
        {
            $_SESSION["ERROR"]["street"] = "Veuillez indiquer une rue.";
        }
    }

    public function validateCity()
    {
        if (isset($_POST["city"]) && $_POST["city"] !== "")
        {
            $city = htmlspecialchars($_POST["city"]);

            $cp = substr($city, 0, 5);
            $city = substr($city, 8);

            $sql = "SELECT v.id AS id FROM villes AS v WHERE v.ville_nom = :v_nom AND v.ville_code_postal = :v_cp";
            $queryCity = $this->_db->prepare($sql);
            $queryCity->bindParam(":v_nom", $city, PDO::PARAM_STR);
            $queryCity->bindParam(":v_cp", $cp, PDO::PARAM_INT);
            $queryCity->execute();
            $data = $queryCity->fetch();
            $queryCity->closeCursor();

            if ($data)
            {
                return $data["id"];
            }
            else
            {
                $_SESSION["ERROR"]["city"] = "Veuillez choisir une des villes de la liste.";
            }
        }
        else
        {
                $_SESSION["ERROR"]["city"] = "Veuillez spécifier une ville.";
        }

    }

    public function validateDepartement()
    {
        if (isset($_POST["departement"]))
        {
            $departement = htmlspecialchars($_POST["departement"]);
            $departement_num = substr($departement, 0, 2);
            $departement = substr($departement, 5);

            $sql = "SELECT d.id AS id FROM departements AS d WHERE d.departement_nom = :d_nom AND d.departement_num = :d_num";
            $queryDep = $this->_db->prepare($sql);
            $queryDep->bindParam(":d_nom", $departement, PDO::PARAM_STR);
            $queryDep->bindParam(":d_num", $departement_num, PDO::PARAM_INT);
            $queryDep->execute();
            $data = $queryDep->fetch();
            $queryDep->closeCursor();

            if ($data)
            {
                return $data["id"];
            }
            else
            {
                $_SESSION["ERROR"]["departement"] = "Veuillez choisir un des départements de la liste.";
            }
        }
        else
        {
            $_SESSION["ERROR"]["departement"] = "Veuillez spécifier un département.";
        }
    }

    public function validateRegion()
    {
        if (isset($_POST["region"]))
        {
            $region = htmlspecialchars($_POST["region"]);

            $sql = "SELECT r.id AS id FROM regions AS r WHERE r.region_nom = :r_nom";
            $queryRegion = $this->_db->prepare($sql);
            $queryRegion->bindParam(":r_nom", $region, PDO::PARAM_STR);
            $queryRegion->execute();
            $data = $queryRegion->fetch();
            $queryRegion->closeCursor();

            if ($data)
            {
                return $data["id"];
            }
            else
            {
                $_SESSION["ERROR"]["region"] = "Veuillez choisir une des régions de la liste.";
            }
        }
        else
        {
            $_SESSION["ERROR"]["region"] = "Veuillez spécifier une région.";
        }
    }

    public function validateAddress($city_id, $departement_id, $region_id)
    {
        $sql = "SELECT * FROM villes AS v
        INNER JOIN departements AS d
        ON v.id_departement = d.id
        INNER JOIN regions AS r
        ON d.id_region = r.id
        WHERE v.id = :v_id AND d.id = :d_id AND r.id = :r_id";
        $queryAddress = $this->_db->prepare($sql);
        $queryAddress->bindParam(":v_id", $city_id, PDO::PARAM_INT);
        $queryAddress->bindParam(":d_id", $departement_id, PDO::PARAM_INT);
        $queryAddress->bindParam(":r_id", $region_id, PDO::PARAM_INT);
        $queryAddress->execute();
        $data = $queryAddress->fetch();
        $queryAddress->closeCursor();

        if ($data)
        {
            return true;
        }
        else
        {
            $_SESSION["ERROR"]["address"] = "L'adresse indiquée n'existe pas.";
        }
    }

    public function validateLogLogin()
    {

        if (!empty($_POST["connexion_login"]))
        {
            $login = htmlspecialchars($_POST["connexion_login"]);
            return $login;
        }
        else
        {
            $_SESSION["ERROR"]["login"] = "Veuillez remplir le champ Pseudo.";
        }
    }
    public function validateLogEmail()
    {
        if (!empty($_POST["connexion_email"]))
        {
            $email = htmlspecialchars($_POST["connexion_email"]);
            return $email;
        }
        else
        {
            $_SESSION["ERROR"]["email"] = "Veuillez remplir le champ Email.";
        }
    }

    public function validateLogPassword()
    {

        if (!empty($_POST["connexion_password"]))
        {
            $password = md5(htmlspecialchars($_POST["connexion_password"]));
            return $password;
        }
        else
        {
            $_SESSION["ERROR"]["password"] = "Veuillez remplir le champ Mot de passe.";
        }
    }

    public function validateLoginUpdate($current_login)
    {
        if (!empty($_POST["login"]) && strlen($_POST["login"]) >= 3)
        {
            if (strlen($_POST["login"]) < 21)
            {
                $login = htmlspecialchars(strtolower($_POST["login"]));
                if (strcmp($login, $current_login) !==   0)
                {
                    $sql = "SELECT u.user_login FROM users AS u WHERE u.user_login = :login";
                    $queryLogin = $this->_db->prepare($sql);
                    $queryLogin->bindParam(":login", $login, PDO::PARAM_STR);
                    $queryLogin->execute();
                    $data = $queryLogin->fetch();
                    $queryLogin->closeCursor();

                    if ($data)
                    {
                        $_SESSION["ERROR"]["login"] = "Ce pseudo est déja utilisé.";
                    }
                    else
                    {
                        return $login;
                    }
                }
                else
                {
                    return $login;
                }
            }
            else
            {
                $_SESSION["ERROR"]["login"] = "Le pseudo indiqué est trop long.";
            }
        }
        else
        {
            $_SESSION["ERROR"]["login"] = "Le pseudo indiqué est trop court.";
        }
    }

    public function validateEmailUpdate($current_email)
    {
        if (strcmp($_POST["email1"], $current_email) !== 0)
        {
            if (!empty($_POST["email1"]) && strlen($_POST["email1"]) >= 3)
            {
                if (!empty($_POST["email2"]) && strlen($_POST["email2"]) >= 3)
                {
                    $email1 = htmlspecialchars(strtolower($_POST["email1"]));
                    $email2 = htmlspecialchars(strtolower($_POST["email2"]));

                    if (strcmp($email1, $email2) == 0)
                    {
                        if (strlen($email1 > 255))
                        {
                            $_SESSION["ERROR"]["email_long"] = "L'adresse e-mail indiquée est trop longue";
                        }
                        else
                        {
                            $sql = "SELECT u.user_email FROM users AS u WHERE u.user_email = :email";
                            $queryMail = $this->_db->prepare($sql);
                            $queryMail->bindValue(":email", $email1, PDO::PARAM_STR);
                            $queryMail->execute();
                            $data = $queryMail->fetch();
                            $queryMail->closeCursor();

                            if ($data)
                            {
                                $_SESSION["ERROR"]["email_exist"] = "L'adresse e-mail indiquée est déjà utilisée.";
                            }
                            else
                            {
                               return $email1;
                            }
                        }
                    }
                    else
                    {
                        $_SESSION["ERROR"]["email_match"] = "L’adresse e-mail de confirmation indiquée ne correspond pas.";
                    }
                }
                else
                {
                    $_SESSION["ERROR"]["email2"] = "L’adresse e-mail de confirmation indiquée est trop courte.";
                }
            }
            else
            {
                $_SESSION["ERROR"]["email1"] = "L’adresse e-mail indiquée est trop courte.";
            }
        }
        else
        {
            return $current_email;
        }
    }
    public function validatePasswordUpdate($current_password)
    {
        if (empty($_POST["password1"]))
        {
            return $current_password;
        }

        if (!empty($_POST["password1"]) && strlen($_POST["password1"]) >= 6)
        {
            if (!empty($_POST["password1"]) && strlen($_POST["password1"]) >= 6)
            {
                $password1 = $_POST["password1"];
                $password2 = $_POST["password2"];

                if (strcmp($password1, $password2) != 0)
                {
                    $_SESSION["ERROR"]["password_match"] = "Le mot de passe de confirmation indiqué ne correspond pas.";
                }
                else
                {
                    if (strlen($password1) > 50)
                    {
                        $_SESSION["ERROR"]["password_long"] = "Le mot de passe indiqué est trop long.";
                    }
                    else
                    {
                        return md5($password1);
                    }
                }
            }
            else
            {
                $_SESSION["ERROR"]["password2"] = "Le mot de passe de confirmation indiqué est trop court.";
            }
        }
        else
        {
            $_SESSION["ERROR"]["password1"] = "Le mot de passe indiqué est trop court.";
        }
    }

    public function validateBio()
    {
        if (!empty($_POST["bio"]))
        {
            $bio = htmlspecialchars($_POST["bio"]);
            $bio = nl2br($bio);
            return $bio;
        }
        else
        {
            return "";
        }
    }

    public function  validateAvatar($login)
    {
        if (!empty($_FILES["avatar"]["name"]))
        {
            if ($_FILES["avatar"]["error"] > 0)
            {
                $_SESSION["ERROR"]["fail"] ="L'envoi a échoué";
            }
            else
            {
                if ($_FILES["avatar"]["type"] !== "image/jpeg" && $_FILES["avatar"]["type"] !== "image/png")
                {
                    $_SESSION["ERROR"]["wrong_type"] ="Votre image n'est pas au bon format";
                }
                else
                {
                    $extension = $_FILES["avatar"]["type"];
                    $extension = substr($extension, strpos($extension, "/") + 1);
                    if ($_FILES["avatar"]["size"] > 1000000)
                    {
                        $_SESSION["ERROR"]["too_heavy"] ="L'image est trop grosse (1 MB max.) !";
                    }
                    else
                    {
                        $image_size = getimagesize($_FILES["avatar"]["tmp_name"]);
                        if ($image_size[0] > 150 || $image_size[1] > 150)
                        {
                            $_SESSION["ERROR"]["oversize"] ="L'image est trop grande (150*150 px max.) !";
                        }
                        else
                        {
                            $curr_path = $_FILES["avatar"]["tmp_name"];
                            $destination_path = "images/avatars/upload/" . $login . "." . $extension;
                            if (strlen($destination_path) > 255)
                            {
                                $_SESSION["ERROR"]["toolong"] ="Le nom de l'image  est trop long.";
                            }
                            else
                            {
                                move_uploaded_file($curr_path, $destination_path);
                                return $destination_path;
                            }
                        }
                    }
                }
            }
        }
        return false;
    }

    public function validateCitySearch()
    {
        if (isset($_POST["city"]) && $_POST["city"] !== "")
        {
            $city = htmlspecialchars($_POST["city"]);

            $cp = substr($city, 0, 5);
            $city_name = substr($city, 8);

            $sql = "SELECT v.id AS id FROM villes AS v WHERE v.ville_nom = :v_nom AND v.ville_code_postal = :v_cp";
            $queryCity = $this->_db->prepare($sql);
            $queryCity->bindParam(":v_nom", $city_name, PDO::PARAM_STR);
            $queryCity->bindParam(":v_cp", $cp, PDO::PARAM_INT);
            $queryCity->execute();
            $data = $queryCity->fetch();
            $queryCity->closeCursor();

            if ($data)
            {
                return $city;
            }
            else
            {
                $_SESSION["ERROR"]["city"] = "Ville inconnue.";
            }
        }
    }

    public function validateMessageRecipient($author_id)
    {
        if (isset($_POST["recipient"]) && $_POST["recipient"] !== "")
        {
            $recipient = htmlspecialchars($_POST["recipient"]);

            $sql = "SELECT u.id FROM users AS u WHERE u.user_login = :recipient AND u.user_disabled = 0";
            $queryRecipient = $this->_db->prepare($sql);
            $queryRecipient->bindParam(":recipient", $recipient, PDO::PARAM_STR);
            $queryRecipient->execute();
            $data = $queryRecipient->fetch();
            $queryRecipient->closeCursor();

            if ($data)
            {
                if ($data["id"] !== $author_id)
                {
                    return $data["id"];
                }
                else
                {
                    $_SESSION["ERROR"]["recipient"] = "Vous ne pouvez pas vous envoyer de message...";
                }
            }
            else
            {
                $_SESSION["ERROR"]["recipient"] = "L'utilisateur demandé n'éxiste pas.";
            }
        }
        else
        {
            $_SESSION["ERROR"]["recipient"] = "Veuillez remplir le champs Destinataire.";
        }
    }

    public function validateMessageTitle()
    {
        if (isset($_POST["title"]) && $_POST["title"] !== "")
        {
            $title = htmlspecialchars($_POST["title"]);
            return $title;
        }
        else
        {
            $_SESSION["ERROR"]["title"] = "Veuillez remplir le champs Sujet";
        }
    }

    public function validateMessageContent()
    {
        if (isset($_POST["content"]) && $_POST["content"] !== "")
        {
            $content = htmlspecialchars($_POST["content"]);
            $content = nl2br($content);
            return $content;
        }
        else
        {
            $_SESSION["ERROR"]["content"] = "Veuillez remplir le champs Message";
        }
    }
}
?>