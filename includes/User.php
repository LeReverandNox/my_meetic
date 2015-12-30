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
    protected $_age;
    protected $_gender;
    protected $_orientation;
    protected $_avatar;
    protected $_bio;
    protected $_status;
    protected $_activation_token;
    protected $_disabled;
    protected $_register_date;

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

    protected $_new_login;
    protected $_new_email;
    protected $_new_password;

    protected $_validator;

    public function __construct($db, $id = null)
    {
        $this->_db = $db;
        $this->_validator = new FormValidator($db);

        if ($id)
        {
            $this->_id = $id;

            $sql = "SELECT u.user_login, u.user_email, u.user_password, u.user_firstname, u.user_lastname, u.user_birthdate, u.user_gender, u.user_orientation, u.user_avatar, u.user_bio, u.user_status, u.user_activation_token, u.user_disabled, DATE_FORMAT(u.user_register_date, '%d/%m/%Y') AS register_date, u.id_adresse, a.adresse_rue, a.id_ville, v.ville_nom, v.ville_code_postal, v.id_departement, d.departement_nom, d.departement_num, d.id_region, r.region_nom, r.id_pays, p.pays_nom
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
            $this->_password = $dataUser["user_password"];
            $this->_firstname = $dataUser["user_firstname"];
            $this->_lastname = $dataUser["user_lastname"];
            $this->_birthdate = $dataUser["user_birthdate"];
            $this->calculAge();
            $this->_gender = $dataUser["user_gender"];
            $this->_orientation = $dataUser["user_orientation"];
            $this->_avatar = $dataUser["user_avatar"];
            $this->_bio = $dataUser["user_bio"];
            $this->_activation_token = $dataUser["user_activation_token"];
            $this->_status = $dataUser["user_status"];
            $this->_disabled = $dataUser["user_disabled"];
            $this->_id_adresse = $dataUser["id_adresse"];
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
            $this->_register_date = $dataUser["register_date"];
        }
    }

    public function __destruct()
    {
        $this->_db = null;
    }

    public function getId()
    {
        return $this->_id;
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

    public function getAge()
    {
        return $this->_age;
    }
    public function calculAge()
    {
        // on décortique la date d'aujourd'hui (jour,mois et année):
        $an_now=date("Y");
        $mois_now=date("m");
        $jour_now=date("d");

        //on décortique la date de naissance (jour,mois et année):
        $an=substr($this->_birthdate, 0, 4);
        $mois=substr($this->_birthdate, 5, 2);
        $jour=substr($this->_birthdate, 8, 2);

        //on soustrait l'année de naissance de l'année actuelle :
        $age=$an_now-$an;

        //si le jour de naissance n'est pas encore passé, on retire une année :
        if( ($mois>$mois_now) or (($mois==$mois_now) and ($jour>$jour_now)) )
        {
           $age=$age-1;
        }
        $this->_age = $age;
    }

    public function getGender()
    {
        return $this->_gender;
    }

    public function getGenderText()
    {
        if ($this->_gender == 0)
        {
            return "Homme";
        }
        elseif ($this->_gender == 1)
        {
            return "Femme";
        }
        elseif ($this->_gender == 2)
        {
            return "Alien";
        }
    }

    public function setGender($gender)
    {
        $this->_gender = $gender;
    }

    public function getOrientation()
    {
        return $this->_orientation;
    }
    public function getOrientationText()
    {
        if ($this->_orientation == 0)
        {
            return "hommes";
        }
        elseif ($this->_orientation == 1)
        {
            return "femmes";
        }
        elseif ($this->_orientation == 2)
        {
            return "aliens";
        }
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

    public function getRegisterDate()
    {
        return $this->_register_date;
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

    public function setNewLogin($new_login)
    {
        $this->_new_login = $new_login;
    }
    public function setNewEmail($new_email)
    {
        $this->_new_email = $new_email;
    }
    public function setNewPassword($new_password)
    {
        $this->_new_password = $new_password;
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

        if ($this->_gender == 0)
        {
            $this->setAvatar("images/avatars/default_male.png");
        }
        elseif ($this->_gender == 1)
        {
            $this->setAvatar("images/avatars/default_female.png");
        }
        elseif ($this->_gender == 2)
        {
            $this->setAvatar("images/avatars/default_alien.png");
        }


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

            $sql = "INSERT INTO users (user_login, user_email, user_password, user_firstname, user_lastname, user_birthdate, user_gender, user_orientation, user_avatar, id_adresse, user_activation_token, user_register_date)
            VALUES (:login, :email, :password, :firstname, :lastname, :birthdate, :gender, :orientation, :avatar, :id_address, :activation_token, NOW())";
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
            $queryRegister->bindParam(":avatar", $this->_avatar, PDO::PARAM_STR);
            $queryRegister->bindParam(":activation_token", $this->_activation_token, PDO::PARAM_STR);
            $queryRegister->execute();

            $this->_id = $this->_db->lastInsertId();
            $this->sendActivationMail();

            $_SESSION["INFOS"] = "Votre compte a été créé. Cependant, il doit être activé. Une clé d’activation vous a été envoyée par e-mail. Vérifiez vos e-mails pour plus d’informations.";
            $_POST = [];

            return true;
        }
        return false;
    }

    public function sendActivationMail()
    {
        $from = "lereverandnox@gmail.com";
        $to = $this->_email;
        $activation_link = "http://localhost/S1%20-%20PHP/PHP_my_meetic/activation.php?action=activate&id=" . $this->_id . "&token=" . $this->_activation_token;

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
        $message .= "<p>L'équipe <a href='http://localhost/S1%20-%20PHP/PHP_my_meetic/'>My_Meetic</a></p>";
        $message .= "</body></html>";

        mail($to, $subject, $message, $headers, "-r $from");

        return true;
    }

    public function sendResetPwdMail()
    {
        $from = "lereverandnox@gmail.com";
        $to = $this->_email;

        $headers = "From: <" . $from . ">\r\n";
        $headers .= "Reply-To: <" . $from . ">\r\n";
        $headers .= "Return-Path:  < " . $from . " >\r\n";
        $headers .= "Sender: <" . $from . ">\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Date: <" . date("r", time()). ">\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

        $subject = "Réinitialisation de votre mot de passe";
        $message ="<html><body>";
        $message .= "<p>Bonjour " . $this->_login. ", vous recevez cette notification car vous avez (ou quelqu’un qui prétend
                                être vous) demandé qu’un nouveau mot de passe vous soit envoyé pour votre
                                compte sur « <a href='http://localhost/S1%20-%20PHP/PHP_my_meetic/'>My_Meetic</a>».</p>";
        $message .= "<p>Voici votre nouveau mot de passe : " . $this->_password . "</p>";
        $message .= "Une fois connecté, vous pourrez le modifier depuis la rubique Mon Compte.";
        $message .= "<p>A très vite parmis nous !</p>";
        $message .= "<p>L'équipe <a href='http://localhost/S1%20-%20PHP/PHP_my_meetic/'>My_Meetic</a></p>";
        $message .= "</body></html>";

        mail($to, $subject, $message, $headers, "-r $from");

        return true;
    }
    public function existUserReset()
    {
        $sql = "SELECT * FROM users AS u WHERE u.user_login = :login AND u.user_email = :email";
        $queryPrepareReset = $this->_db->prepare($sql);
        $queryPrepareReset->bindParam(":login", $this->_login, PDO::PARAM_STR);
        $queryPrepareReset->bindParam(":email", $this->_email, PDO::PARAM_STR);
        $queryPrepareReset->execute();
        $data = $queryPrepareReset->fetch();
        $queryPrepareReset->closeCursor();

        if (!$data)
        {
            $_SESSION["ERROR"]["reset"] = "Cet utilisateur n'existe pas";
        }
    }

    public function prepareReset()
    {
        $this->setLogin($this->_validator->validateLogLogin());
        $this->setEmail($this->_validator->validateLogEmail());

        if (empty($_SESSION["ERROR"]))
        {
            $this->existUserReset();
        }

        if (empty($_SESSION["ERROR"]))
        {
            return true;
        }
    }

    public function resetPassword()
    {
        if ($this->prepareReset())
        {
            $new_password = substr(md5(time()), 0, 8);
            $new_password_hash = md5($new_password);

            $this->setPassword($new_password);

            $sql = "UPDATE users AS u SET u.user_password = :password WHERE u.user_login = :login";
            $queryResetPassword = $this->_db->prepare($sql);
            $queryResetPassword->bindParam(":password", $new_password_hash, PDO::PARAM_STR);
            $queryResetPassword->bindParam(":login", $this->_login, PDO::PARAM_STR);
            $queryResetPassword->execute();

            $this->sendResetPwdMail();
            $_SESSION["INFOS"] = "Un nouveau mot de passe vous a été envoyé par email.";
            return true;
        }
        else
        {
            return false;
        }
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
        $sql = "UPDATE users AS u
        SET u.user_disabled = 1
        WHERE u.id = :id";
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

    public function prepareUpdate()
    {
        $this->setNewLogin($this->_validator->validateLoginUpdate($this->_login));
        $this->setNewEmail($this->_validator->validateEmailUpdate($this->_email));
        $this->setNewPassword($this->_validator->validatePasswordUpdate($this->_password));

        $this->setFirstname($this->_validator->validateFirstname());
        $this->setLastname($this->_validator->validateLastname());
        $this->setBirthdate($this->_validator->validateBirthdate());
        $this->setGender($this->_validator->validateGender());
        $this->setOrientation($this->_validator->validateOrientation());
        $this->setStreet($this->_validator->validateStreet());
        $this->setCityId($this->_validator->validateCity());
        $this->setDepartementId($this->_validator->validateDepartement());
        $this->setRegionId($this->_validator->validateRegion());
        $this->setBio($this->_validator->validateBio());

        if ($avatar = $this->_validator->validateAvatar($this->_login))
        {
            $this->_avatar = $avatar;
        }

        $this->_validator->validateAddress($this->_city_id, $this->_departement_id, $this->_region_id);

        if (empty($_SESSION["ERROR"]))
        {
            return true;
        }
        return false;
    }

    public function update()
    {
        if ($this->prepareUpdate())
        {
            $sql1="UPDATE users AS u
            SET u.user_login = :login, u.user_email = :email, u.user_password = :password, u.user_firstname = :firstname, u.user_lastname = :lastname, u.user_birthdate = :birthdate, u.user_gender = :gender, u.user_orientation = :orientation, u.user_avatar = :avatar, u.user_bio = :bio
            WHERE u.id = :u_id";

            $sql2 = "UPDATE adresses AS a
            SET a.adresse_rue = :street, a.id_ville = :city_id
            WHERE a.id = :a_id";

            $queryUpdateUser = $this->_db->prepare($sql1);
            $queryUpdateUser->bindParam(":login", $this->_new_login, PDO::PARAM_STR);
            $queryUpdateUser->bindParam(":email", $this->_new_email, PDO::PARAM_STR);
            $queryUpdateUser->bindParam(":password", $this->_new_password, PDO::PARAM_STR);
            $queryUpdateUser->bindParam(":firstname", $this->_firstname, PDO::PARAM_STR);
            $queryUpdateUser->bindParam(":lastname", $this->_lastname, PDO::PARAM_STR);
            $queryUpdateUser->bindParam(":birthdate", $this->_birthdate, PDO::PARAM_STR);
            $queryUpdateUser->bindParam(":gender", $this->_gender, PDO::PARAM_INT);
            $queryUpdateUser->bindParam(":orientation", $this->_orientation, PDO::PARAM_INT);
            $queryUpdateUser->bindParam(":avatar", $this->_avatar, PDO::PARAM_STR);
            $queryUpdateUser->bindParam(":bio", $this->_bio, PDO::PARAM_STR);
            $queryUpdateUser->bindParam(":u_id", $this->_id, PDO::PARAM_INT);
            $queryUpdateUser->execute();

            $queryUpdateAddress = $this->_db->prepare($sql2);
            $queryUpdateAddress->bindParam(":street", $this->_street, PDO::PARAM_STR);
            $queryUpdateAddress->bindParam(":city_id", $this->_city_id, PDO::PARAM_INT);
            $queryUpdateAddress->bindParam(":a_id", $this->_id_adresse, PDO::PARAM_INT);
            $queryUpdateAddress->execute();

            $_SESSION["INFOS"] = "Vos informations ont été mise à jour.";
            return true;
        }
    }

    public static function deconnexion()
    {
        $_SESSION = array();
        session_destroy();
    }
}
?>