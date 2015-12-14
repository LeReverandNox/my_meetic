<?php
Class FormValidator
{
    protected $_db;
    protected $_login;
    protected $_email;
    protected $_password;
    protected $_firstname;
    protected $_lastname;
    protected $_birthdate;
    protected $_gender;

    protected $_street;
    protected $_city_id;
    protected $_departement_id;
    protected $_region_id;

    public function __construct($db)
    {
        $this->_db = $db;
    }

    public function __destruct()
    {
        $this->_db = null;
    }

    public function getLogin()
    {
        return $this->_login;
    }

    public function getEmail()
    {
        return $this->_email;
    }

    public function getPassword()
    {
        return $this->_password;
    }

    public function getFirstname()
    {
        return $this->_firstname;
    }

    public function getLastname()
    {
        return $this->_lastname;
    }

    public function getBirthdate()
    {
        return $this->_birthdate;
    }

    public function getGender()
    {
        return $this->_gender;
    }

    public function getStreet()
    {
        return $this->_street;
    }

    public function getCityId()
    {
        return $this->_city_id;
    }

    public function getDepartementId()
    {
        return $this->_departement_id;
    }

    public function getRegionId()
    {
        return $this->_region_id;
    }

    public function validateLogin($login)
    {
        $login = htmlspecialchars(strtolower($login));

        if (!empty($login) && strlen($login) >= 3)
        {
            if (strlen($login) < 21)
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
                    $this->_login = $login;
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

    public function validateEmail($email1, $email2)
    {
        $email1 = htmlspecialchars(strtolower($email1));
        $email2 = htmlspecialchars(strtolower($email2));

        if (!empty($email1) && strlen($email1) >= 3)
        {
            if (!empty($email2) && strlen($email2) >= 3)
            {
                if (strcmp($email1, $email2) == 0)
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
                        $this->_email = $email1;
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

    public function validatePassword($password1, $password2)
    {
        if (!empty($password1) && strlen($password1) >= 6)
        {
            if (!empty($password2) && strlen($password2) >= 6)
            {
                if (strcmp($password1, $password2) != 0)
                {
                    $_SESSION["ERROR"]["password_match"] = "Le mot de passe de confirmation indiqué ne correspond pas.";
                }
                else
                {
                    $this->_password = md5($password1);
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

    public function validateFirstname($firstname)
    {
        $firstname = htmlspecialchars(ucwords($firstname, " -"));
        if (empty($firstname))
        {
            $_SESSION["ERROR"]["firstname"] = "Le prénom indiqué est trop court.";
        }
        else
        {
            $this->_firstname = $firstname;
        }
    }

    public function validateLastname($lastname)
    {
        $lastname = htmlspecialchars(ucwords($lastname, " -"));
        if (empty($lastname))
        {
            $_SESSION["ERROR"]["lastname"] = "Le nom indiqué est trop court.";
        }
        else
        {
            $this->_lastname = $lastname;
        }
    }

    public function validateBirthdate($birthdate)
    {
        $birthdate = htmlspecialchars($birthdate);
        $regDate = "/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/";

        if (!empty($birthdate))
        {
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
                    $this->_birthdate = $birthdate;
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

    public function validateGender($gender)
    {
        $gender = intval($gender);

        if ($gender == 0 || $gender == 1)
        {
            $this->_gender = $gender;
        }
        else
        {
            $_SESSION["ERROR"]["gender"] = "Veuillez indiquer votre sexe";
        }
    }

    public function validateStreet($number, $street)
    {
        $number = htmlspecialchars($number);
        $street = htmlspecialchars($street);

        if (!empty($number) && !empty($street))
        {
            $this->_street = $number . " " . $street;
        }
        elseif (empty($number))
        {
            $_SESSION["ERROR"]["ad_number"] = "Veuillez indiquer un numéro de rue.";
        }
        elseif (empty($street))
        {
            $_SESSION["ERROR"]["street"] = "Veuillez indiquer une rue.";
        }
    }

    public function validateCity($city)
    {
        $city = htmlspecialchars($city);

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
            $this->_city_id = $data["id"];
        }
        else
        {
            $_SESSION["ERROR"]["city"] = "Veuillez choisir une des villes de la liste.";
        }
    }

    public function validateDepartement($departement)
    {
        $departement = htmlspecialchars($departement);
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
            $this->_departement_id = $data["id"];
        }
        else
        {
            $_SESSION["ERROR"]["departement"] = "Veuillez choisir un des départements de la liste.";
        }
    }

    public function validateRegion($region)
    {
        $region = htmlspecialchars($region);

        $sql = "SELECT r.id AS id FROM regions AS r WHERE r.region_nom = :r_nom";
        $queryRegion = $this->_db->prepare($sql);
        $queryRegion->bindParam(":r_nom", $region, PDO::PARAM_STR);
        $queryRegion->execute();
        $data = $queryRegion->fetch();
        $queryRegion->closeCursor();

        if ($data)
        {
            $this->_region_id = $data["id"];
        }
        else
        {
            $_SESSION["ERROR"]["region"] = "Veuillez choisir une des régions de la liste.";
        }
    }

    public function validateLogLogin($login)
    {
        $login = htmlspecialchars($login);

        if (!empty($login))
        {
            $this->_login = $login;
        }
        else
        {
            $_SESSION["ERROR"]["login"] = "Veuillez remplir le champ Pseudo.";
        }
    }

    public function validateLogPassword($password)
    {
        $password = htmlspecialchars($password);

        if (!empty($password))
        {
            $this->_password = md5($password);
        }
        else
        {
            $_SESSION["ERROR"]["password"] = "Veuillez remplir le champ Mot de passe.";
        }
    }
}
?>