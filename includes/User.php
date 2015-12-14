<?php
Class User
{
    protected $_db;

    protected $_id;
    protected $_login;
    protected $_email;
    protected $_password;
    protected $_firstname;
    protected $_lastname;
    protected $_birthdate;
    protected $_gender;
    protected $_status;
    protected $_activation_token;

    protected $_street;
    protected $_city;
    protected $_city_id;
    protected $_cp;
    protected $_departement;
    protected $_departement_num;
    protected $_departement_id;
    protected $_region;
    protected $_region_id;
    protected $_pays;

    public function __construct($db, $id = null)
    {
        $this->_db = $db;
        if ($id)
        {
            $this->_id = $id;

            $sql = "SELECT u.user_login, u.user_email, u.user_firstname, u.user_lastname, u.user_birthdate, u.user_gender, u.user_status, u.user_activation_token, u.user_disabled
            FROM users AS u
            LEFT JOIN adresses AS a
            ON u.id_adresse = a.id
            WHERE u.id = :id";
            $queryLogin = $this->_db->prepare($sql);
            $queryLogin->bindParam(":id", $this->_id, PDO::PARAM_INT);
            $queryLogin->execute();
            $dataUser = $queryLogin->fetch();
            $queryLogin->closeCursor();

            $this->_login = $dataUser["user_login"];
            $this->_email = $dataUser["user_email"];
            $this->_status = $dataUser["user_status"];
            $this->_activation_token = $dataUser["user_activation_token"];
        }
    }

    public function __destruct()
    {
        $this->_db = null;
    }

    public function getLogin()
    {
        return $this->_login;
    }
    public function setLogin($login)
    {
        $this->_login = $login;
    }

    public function getEmail()
    {
        return $this->_email;
    }

    public function setEmail($email)
    {
        $this->_email = $email;
    }

    public function setPassword($password)
    {
        $this->_password = $password;
    }
    public function getFirstname()
    {
        return $this->_firstname;
    }
    public function setFirstname($firstname)
    {
        $this->_firstname = $firstname;
    }

    public function getLastname()
    {
        return $this->_lastname;
    }
    public function setLastname($lastname)
    {
        $this->_lastname = $lastname;
    }

    public function getBirthdate()
    {
        return $this->_birthdate;
    }
    public function setBirthdate($birthdate)
    {
        $this->_birthdate = $birthdate;
    }

    public function getGender()
    {
        return $this->_gender;
    }
    public function setGender($gender)
    {
        $this->_gender = $gender;
    }

    public function getActivationToken()
    {
        return $this->_activation_token;
    }

    public function generateToken()
    {
        $this->_activation_token = md5($this->_email);
    }

    public function getStreet()
    {
        return $this->_street;
    }

    public function setStreet($street)
    {
        $this->_street = $street;
    }

    public function getCity()
    {
        return $this->_city;
    }

    public function getCityId()
    {
        return $this->_city_id;
    }
    public function setCityId($city_id)
    {
        $this->_city_id = $city_id;
    }

    public function getDepartement()
    {
        return $this->_departement;
    }
    public function getDepartementId()
    {
        return $this->_departement_id;
    }
    public function setDepartementId($departement_id)
    {
        $this->_departement_id = $departement_id;
    }
    public function getDepartementNum()
    {
        return  $this->_departement_num;
    }

    public function getRegion()
    {
        return $this->_region;
    }
    public function getRegionId()
    {
        return $this->_region_id;
    }
    public function setRegionId($region_id)
    {
        $this->_region_id = $region_id;
    }

    public function register()
    {
        $this->generateToken();

        $sql = "INSERT INTO adresses (adresse_rue, id_ville) VALUES (:street, :city_id)";
        $queryAddress = $this->_db->prepare($sql);
        $queryAddress->bindParam(":street", $this->_street, PDO::PARAM_STR);
        $queryAddress->bindParam(":city_id", $this->_city_id, PDO::PARAM_INT);
        $queryAddress->execute();
        $id_address = $this->_db->lastInsertId();

        $sql = "INSERT INTO users (user_login, user_email, user_password, user_firstname, user_lastname, user_birthdate, user_gender, id_adresse, user_activation_token)
        VALUES (:login, :email, :password, :firstname, :lastname, :birthdate, :gender, :id_address, :activation_token)";
        $queryRegister = $this->_db->prepare($sql);
        $queryRegister->bindParam(":login", $this->_login, PDO::PARAM_STR);
        $queryRegister->bindParam(":email", $this->_email, PDO::PARAM_STR);
        $queryRegister->bindParam(":password", $this->_password, PDO::PARAM_STR);
        $queryRegister->bindParam(":firstname", $this->_firstname, PDO::PARAM_STR);
        $queryRegister->bindParam(":lastname", $this->_lastname, PDO::PARAM_STR);
        $queryRegister->bindParam(":birthdate", $this->_birthdate, PDO::PARAM_STR);
        $queryRegister->bindParam(":gender", $this->_gender, PDO::PARAM_INT);
        $queryRegister->bindParam(":id_address", $id_address, PDO::PARAM_INT);
        $queryRegister->bindParam(":activation_token", $this->_activation_token, PDO::PARAM_STR);
        $queryRegister->execute();

        $this->_id = $this->_db->lastInsertId();
        $this->sendActivationMail();

        $_SESSION["INFOS"] = "Votre compte a été créé. Cependant, il doit être activé. Une clé d’activation vous a été envoyée par e-mail. Vérifiez vos e-mails pour plus d’informations.";
        return true;
    }

    public function sendActivationMail()
    {
        $from = "lereverandnox@gmail.com";
        $to = $this->_email;
        $activation_link = "http://localhost/S1%20-%20PHP/PHP_my_meetic/account.php/?action=activate&id=" . $this->_id . "&token=" . $this->_activation_token;

        $headers = "From: <" . $from . ">\r\n";
        $headers .= "Reply-To: <" . $from . ">\r\n";
        $headers .= "Return-Path:  < " . $from . " >\r\n";
        $headers .= "Sender: <" . $from . ">\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Date: <" . date("r", time()). ">\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

        $subject = "Activation de votre compte My_Meetic";
        $message ="<html><body>";
        $message .= "<p>Bonjour, merci de vous être inscrit sur <a href='http://localhost/S1%20-%20PHP/PHP_my_meetic/'>My_Meetic</a> !</p>";
        $message .= "<p>Conservez cet e-mail dans vos archives. Les informations de votre compte sont les suivantes :<br />";
        $message .= "Nom d'utilisateur : " . $this->_login . "<br />";
        $message .= "----------------------------<br />";
        $message .= "Veuillez cliquer sur le lien suivant afin d'activer votre compte :<br />";
        $message .= "<a href='" . $activation_link . "''>" . $activation_link ."</a></p>";
        $message .= "<p>Votre mot de passe est encrypté dans notre base de donnée et ne pourra pas être récuperé.<br />";
        $message .= "En cas d'oubli, vous pourrez le réinitialiser depuis notre site grâce à votre adresse e-mail</p>";
        $message .= "<p>A très vite parmis nous !</p>";
        $message .= "</body></html>";

        mail($to, $subject, $message, $headers, "-r $from");

        return true;
    }

    public function activateUser($token)
    {
        if ($this->_status == 1)
        {
            $_SESSION["INFOS"] = "Votre compte est déjà activé";
            return false;
        }
        elseif ($token === $this->_activation_token)
        {
            $sql = "UPDATE users AS u
            SET u.user_status = 1
            WHERE u.id = :id";
            $queryActivate = $this->_db->prepare($sql);
            $queryActivate->bindParam(":id", $this->_id, PDO::PARAM_INT);
            $queryActivate->execute();

            $_SESSION["INFOS"] = "Votre compte a bien été activé.";
            return true;
        }
        else
        {
            $_SESSION["INFOS"] = "Erreur lors de l'activation du compte";
            return false;
        }
    }

    public function disableUser()
    {
        $sql = "UPDATE users
        SET users.user_disabled = 1
        WHERE user.id = :id";
        $queryActivate = $this->_db->prepare($sql);
        $queryActivate->bindParam(":id", $this->_id, PDO::PARAM_INT);
        $queryActivate->execute();

        return true;
    }

    public function connexion()
    {
        $sql = "SELECT u.id, u.user_status, u.user_disabled
                    FROM users AS u
                    WHERE u.user_login = :login AND u.user_password = :password";
        $queryLogin = $this->_db->prepare($sql);
        $queryLogin->bindParam(":login", $this->_login, PDO::PARAM_STR);
        $queryLogin->bindParam(":password", $this->_password, PDO::PARAM_STR);
        $queryLogin->execute();
        $dataLogin = $queryLogin->fetch();
        $queryLogin->closeCursor();

        if (!$dataLogin)
        {
            $_SESSION["ERROR"]["connexion_fail"] = "Mot de passe incorrect";
            return false;
        }
        elseif($dataLogin["user_status"] == 0)
        {
            $_SESSION["ERROR"]["inactive_account"] = "Votre compte n'est pas encore activé";
            return false;
        }
        elseif($dataLogin["user_disabled"] == 1)
        {
            $_SESSION["ERROR"]["disabled_account"] = "Votre compte est supprimé";
            return false;
        }

        $_SESSION["id"] = $dataLogin["id"];
    }

}
?>