<?php
Class Search
{
    protected $_where_or;
    protected $_big_where;
    protected $_db;
    protected $_validator;

    public function __construct($db)
    {
        $this->_db = $db;
        $this->_validator = new FormValidator($db);

        $this->_where_or = [];
        $this->_big_where = [];
    }

    private function stockSessionPOST()
    {
        $_SESSION["POST"] = $_POST;
        unset($_SESSION["POST"]["recherche"]);
    }

    public function cleanSessionPOST()
    {
        unset($_SESSION["POST"]);
    }

    private function generateWhereGender()
    {
        $arr_where_gender = [];
        $where_gender = "";

        if (isset($_POST["gender_male"]))
        {
            array_push($arr_where_gender, "u.user_gender = 0");
        }
        if (isset($_POST["gender_female"]))
        {
            array_push($arr_where_gender, "u.user_gender = 1");
        }
        if (isset($_POST["gender_alien"]))
        {
            array_push($arr_where_gender, "u.user_gender = 2");
        }

        if (!empty($arr_where_gender))
        {
            $where_gender = "(" . implode(" OR ", $arr_where_gender) . ")";
            array_push($this->_where_or, $where_gender);
        }
    }

    private function generateWhereOrientation()
    {
        $arr_where_orientation = [];
        $where_orientation = "";

        if (isset($_POST["orientation_male"]))
        {
            array_push($arr_where_orientation, "u.user_orientation = 0");
        }
        if (isset($_POST["orientation_female"]))
        {
            array_push($arr_where_orientation, "u.user_orientation = 1");
        }
        if (isset($_POST["orientation_alien"]))
        {
            array_push($arr_where_orientation, "u.user_orientation = 2");
        }

        if (!empty($arr_where_orientation))
        {
            $where_orientation = "(" . implode(" OR ", $arr_where_orientation) . ")";
            array_push($this->_where_or, $where_orientation);
        }
    }

    private function generateWhereCity()
    {
        $arr_where_city = [];
        $where_city = "";

        if (!empty($_POST["villes"]))
        {
            $villes = htmlspecialchars($_POST["villes"]);
            $villes = explode(", ", $villes);

            foreach ($villes as $ville)
            {
                $_POST["city"] = $ville;
                $ville = $this->_validator->validateCitySearch();

                $cp = substr($ville, 0, 5);
                $ville_nom = substr($ville, 8);
                array_push($arr_where_city, "v.ville_nom = '$ville_nom' AND v.ville_code_postal = '$cp'");
            }

            if(!empty($arr_where_city))
            {
                $where_city = "(" . implode(" OR ", $arr_where_city) . ")";
                array_push($this->_where_or, $where_city);
            }
        }
    }

    private function generateWhereAge()
    {
        $arr_where_age = [];
        $where_age = "";

        if (isset($_POST["18-25"]))
        {
            array_push($arr_where_age, "(YEAR(NOW()) - YEAR(u.user_birthdate)) BETWEEN 18 AND 25");
        }
        if (isset($_POST["25-35"]))
        {
            array_push($arr_where_age, "(YEAR(NOW()) - YEAR(u.user_birthdate)) BETWEEN 25 AND 35");
        }
        if (isset($_POST["35-45"]))
        {
            array_push($arr_where_age, "(YEAR(NOW()) - YEAR(u.user_birthdate)) BETWEEN 35 AND 45");
        }
        if (isset($_POST["45"]))
        {
            array_push($arr_where_age, "(YEAR(NOW()) - YEAR(u.user_birthdate)) >= 45");
        }

        if (!empty($arr_where_age))
        {
            $where_age = "(" . implode(" OR ", $arr_where_age) . ")";
            array_push($this->_where_or, $where_age);
        }
    }

    private function generateWhereOr()
    {
        $arr_where_or = [];
        $where_or = "";

        if (!empty($this->_where_or))
        {
            $where_or = implode(" AND ", $this->_where_or);
            array_push($this->_big_where, $where_or);
        }
    }

    private function generateWhereAnd()
    {
        $arr_where_and = [];
        $where_and = "";

        if (!empty($_POST["pays"]) && $_POST["pays"] !== "")
        {
            array_push($arr_where_and, "p.id = " . intval($_POST["pays"]));
        }
        if (!empty($_POST["region"]) && $_POST["region"] !== "")
        {
            array_push($arr_where_and, "r.id = " . intval($_POST["region"]));
        }
        if (!empty($_POST["departement"]) && $_POST["departement"] !== "")
        {
            array_push($arr_where_and, "d.id = " . intval($_POST["departement"]));
        }

        if (!empty($arr_where_and))
        {
            $where_and = "(" . implode(" AND ", $arr_where_and) . ")"   ;
            array_push($this->_big_where, $where_and);
        }
    }

    private function generateBigWhere()
    {
        array_push($this->_big_where, "u.id <> :user_id");
        $arr_big_where = [];
        $big_where = "";

        if (!empty($this->_big_where))
        {
            $big_where= "WHERE " . implode(" AND ", $this->_big_where);
        }

        $this->_big_where = $big_where;
    }

    public  function searchUsers($user_id)
    {
        $this->stockSessionPOST();

        $this->generateWhereGender();
        $this->generateWhereOrientation();
        $this->generateWhereAge();
        $this->generateWhereCity();

        $this->generateWhereOr();
        $this->generateWhereAnd();
        $this->generateBigWhere();

        $sql = "SELECT u.id
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
        ON r.id_pays = p.id " . $this->_big_where;

        $querySearch = $this->_db->prepare($sql);
        $querySearch->bindParam(":user_id", $user_id, PDO::PARAM_INT);
        $querySearch->execute();

        $data = $querySearch->fetchAll(PDO::FETCH_ASSOC);
        $querySearch->closeCursor();

        $users = [];
        foreach ($data as $id)
        {
            $user = new User($this->_db, $id["id"]);
            array_push($users, $user);
        }

        return $users;
    }
}
?>