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

    protected $_rue;
    protected $_ville;
    protected $_departement;
    protected $_region;
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
                        WHERE users.id = :id";
            $queryLogin = $this->_db->prepare($sql);
            $queryLogin->bindParam(":id", $this->_id, PDO::PARAM_INT);
            $queryLogin->execute();
            $dataUser = $queryLogin->fetch();

            $this->_login = $dataUser["user_login"];
        }
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

    public function register()
    {
        $sql = "INSERT INTO users (user_login, user_email, user_password, user_firstname, user_lastname, user_birthdate, user_gender, user_activation_token)
        VALUES (:login, :email, :password, :firstname, :lastname, :birthdate, :gender, :activation_token)";
        $queryRegister = $this->_db->prepare($sql);
        $queryRegister->bindParam(":login", $this->_login, PDO::PARAM_STR);
        $queryRegister->bindParam(":email", $this->_email, PDO::PARAM_STR);
        $queryRegister->bindParam(":password", $this->_password, PDO::PARAM_STR);
        $queryRegister->bindParam(":firstname", $this->_firstname, PDO::PARAM_STR);
        $queryRegister->bindParam(":lastname", $this->_lastname, PDO::PARAM_STR);
        $queryRegister->bindParam(":birthdate", $this->_birthdate, PDO::PARAM_STR);
        $queryRegister->bindParam(":gender", $this->_gender, PDO::PARAM_INT);
        $queryRegister->bindParam(":activation_token", $this->_activation_token, PDO::PARAM_STR);

        $queryRegister->execute();

        return true;
    }

    public function activateUser($token)
    {
        if ($token === $this->_activation_token)
        {
            $sql = "UPDATE users
            SET users.user_status = 1
            WHERE user.id = :id";
            $queryActivate = $this->_db->prepare($sql);
            $queryActivate->bindParam(":id", $this->_id, PDO::PARAM_INT);
            $queryActivate->execute();

            return true;
        }
    }

}
?>