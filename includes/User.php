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
    protected $_orientation;
    protected $_avatar;
    protected $_bio;
    protected $_status;
    protected $_activation_token;
    protected $_disabled;

    protected $_id_adresse;
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
    protected $_pays_id;

    protected $_validator;

    public function __construct($db, $id = null)
    {
        $this->_db = $db;
        $this->_validator = new FormValidator($db);

        if ($id)
        {
            $this->_id = $id;

            $sql = "SELECT u.user_login, u.user_email, u.user_firstname, u.user_lastname, u.user_birthdate, u.user_gender, u.user_orientation, u.user_avatar, u.user_bio, u.user_status, u.user_activation_token, u.user_disabled, u.id_adresse, a.adresse_rue, a.id_ville, v.ville_nom, v.ville_code_postal, v.id_departement, d.departement_nom, d.departement_num, d.id_region, r.region_nom, r.id_pays, p.pays_nom
            FROM users AS u
            INNER JOIN adresses AS a
            ON u.id_adresse = a.id
            INNER JOIN villes AS v
            ON a.id_ville = v.id
            INNER JOIN departements AS d
            ON v.id_departement = d.id
            INNER JOIN regions AS r
            ON d.id_region = r.id
            INNER JOIN pays AS p
            ON r.id_pays = p.id
            WHERE u.id = :id";
            $queryLogin = $this->_db->prepare($sql);
            $queryLogin->bindParam(":id", $this->_id, PDO::PARAM_INT);
            $queryLogin->execute();
            $dataUser = $queryLogin->fetch();
            $queryLogin->closeCursor();

            $this->_login = $dataUser["user_login"];
            $this->_email = $dataUser["user_email"];
            $this->_firstname = $dataUser["user_firstname"];
            $this->_lastname = $dataUser["user_lastname"];
            $this->_birthdate = $dataUser["user_birthdate"];
            $this->_gender = $dataUser["user_gender"];
            $this->_orientation = $dataUser["user_orientation"];
            $this->_avatar = $dataUser["user_avatar"];
            $this->_bio = $dataUser["user_bio"];
            $this->_activation_token = $dataUser["user_activation_token"];
            $this->_status = $dataUser["user_status"];
            $this->_disabled = $dataUser["user_disabled"];
            $this->_id_adresse = $dataUser["id_ville"];
            $this->_street = $dataUser["adresse_rue"];
            $this->_city_id = $dataUser["id_ville"];
            $this->_city = $dataUser["ville_nom"];
            $this->_city_cp = $dataUser["ville_code_postal"];
            $this->_departement_id = $dataUser["id_departement"];
            $this->_departement_num = $dataUser["departement_num"];
            $this->_departement = $dataUser["departement_nom"];
            $this->_region_id = $dataUser["id_region"];
            $this->_region = $dataUser["region_nom"];
            $this->_pays_id = $dataUser["id_pays"];
            $this->_pays = $dataUser["pays_nom"];
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

    public function getOrientation()
    {
        return $this->_orientation;
    }

    public function setOrientation($orientation)
    {
        $this->_orientation = $orientation;
    }

    public function getAvatar()
    {
        return $this->_avatar;
    }
    public function setAvatar($avatar)
    {
        $this->_avatar = $avatar;
    }

    public function getBio()
    {
        return $this->_bio;
    }
    public function setBio($bio)
    {
        $this->_bio = $bio;
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

    public function getCityCP()
    {
        return $this->_city_cp;
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

    public function getPaysId()
    {
        return $this->_pays_id;
    }
    public function getPays()
    {
        return $this->_pays;
    }

    public function prepareRegister()
    {
        $this->setLogin($this->_validator->validateLogin());
        $this->setEmail($this->_validator->validateEmail());
        $this->setPassword($this->_validator->validatePassword());
        $this->setFirstname($this->_validator->validateFirstname());
        $this->setLastname($this->_validator->validateLastname());
        $this->setBirthdate($this->_validator->validateBirthdate());
        $this->setGender($this->_validator->validateGender());
        $this->setOrientation($this->_validator->validateOrientation());
        $this->setStreet($this->_validator->validateStreet());
        $this->setCityId($this->_validator->validateCity());
        $this->setDepartementId($this->_validator->validateDepartement());
        $this->setRegionId($this->_validator->validateRegion());

        $this->_validator->validateAddress($this->_city_id, $this->_departement_id, $this->_region_id);

        if (empty($_SESSION["ERROR"]))
        {
            return true;
        }
        return false;
    }

    public function register()
    {
        if ($this->prepareRegister())
        {
            $this->generateToken();

            $sql = "INSERT INTO adresses (adresse_rue, id_ville) VALUES (:street, :city_id)";
            $queryAddress = $this->_db->prepare($sql);
            $queryAddress->bindParam(":street", $this->_street, PDO::PARAM_STR);
            $queryAddress->bindParam(":city_id", $this->_city_id, PDO::PARAM_INT);
            $queryAddress->execute();
            $id_address = $this->_db->lastInsertId();

            $sql = "INSERT INTO users (user_login, user_email, user_password, user_firstname, user_lastname, user_birthdate, user_gender, user_orientation, id_adresse, user_activation_token)
            VALUES (:login, :email, :password, :firstname, :lastname, :birthdate, :gender, :orientation, :id_address, :activation_token)";
            $queryRegister = $this->_db->prepare($sql);
            $queryRegister->bindParam(":login", $this->_login, PDO::PARAM_STR);
            $queryRegister->bindParam(":email", $this->_email, PDO::PARAM_STR);
            $queryRegister->bindParam(":password", $this->_password, PDO::PARAM_STR);
            $queryRegister->bindParam(":firstname", $this->_firstname, PDO::PARAM_STR);
            $queryRegister->bindParam(":lastname", $this->_lastname, PDO::PARAM_STR);
            $queryRegister->bindParam(":birthdate", $this->_birthdate, PDO::PARAM_STR);
            $queryRegister->bindParam(":gender", $this->_gender, PDO::PARAM_INT);
            $queryRegister->bindParam(":orientation", $this->_orientation, PDO::PARAM_INT);
            $queryRegister->bindParam(":id_address", $id_address, PDO::PARAM_INT);
            $queryRegister->bindParam(":activation_token", $this->_activation_token, PDO::PARAM_STR);
            $queryRegister->execute();

            $this->_id = $this->_db->lastInsertId();
            $this->sendActivationMail();

            $_SESSION["INFOS"] = "Votre compte a été créé. Cependant, il doit être activé. Une clé d’activation vous a été envoyée par e-mail. Vérifiez vos e-mails pour plus d’informations.";
            return true;
        }
        return false;
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

    public function activateUser()
    {
        if (isset($_GET["token"]))
        {
            $token = htmlspecialchars($_GET["token"]);
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

    public function prepareConnexion()
    {
        $this->setLogin($this->_validator->validateLogLogin());
        $this->setPassword($this->_validator->validateLogPassword());

        if (empty($_SESSION["ERROR"]))
        {
            return true;
        }
        return false;
    }

    public function connexion()
    {
        if ($this->prepareConnexion())
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
            $_SESSION["INFOS"] = "Vous êtes à présent connecté.";
            return true;
        }
        return false;
    }

}
?>