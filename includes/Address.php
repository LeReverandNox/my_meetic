<?php
Class Address
{
    protected $_cities;
    protected $_departements;
    protected $_regions;
    protected $_pays;

    protected $_db;

    public function __construct($db)
    {
        $this->_db = $db;
        $this->setCities();
        $this->setDepartements();
        $this->setRegions();
        $this->setPays();
    }


    public function getCities()
    {
        return $this->_cities;
    }

    public function setCities()
    {
        $sql = "SELECT v.id AS id, v.ville_nom AS nom, v.ville_code_postal AS CP FROM villes AS v ORDER BY v.ville_code_postal";
        $queryCities = $this->_db->prepare($sql);
        $queryCities->execute();
        $this->_cities = $queryCities->fetchAll(PDO::FETCH_ASSOC);
        $queryCities->closeCursor();
    }

    public function getDepartements()
    {
        return $this->_departements;
    }
    public function setDepartements()
    {
        $sql = "SELECT d.id AS id,  d.departement_nom AS nom, d.departement_num AS num FROM departements AS d";
        $queryDepartements = $this->_db->prepare($sql);
        $queryDepartements->execute();
        $this->_departements = $queryDepartements->fetchAll(PDO::FETCH_ASSOC);
        $queryDepartements->closeCursor();
    }

    public function getRegions()
    {
        return $this->_regions;
    }
    public function setRegions()
    {
        $sql = "SELECT r.id, r.region_nom AS nom FROM regions AS r";
        $queryRegions = $this->_db->prepare($sql);
        $queryRegions->execute();
        $this->_regions = $queryRegions->fetchAll(PDO::FETCH_ASSOC);
        $queryRegions->closeCursor();
    }

    public function getPays()
    {
        return $this->_pays;
    }
    public function setPays()
    {
        $sql = "SELECT p.id, p.pays_nom FROM pays AS p";
        $queryPays = $this->_db->prepare($sql);
        $queryPays->execute();
        $this->_pays = $queryPays->fetchAll(PDO::FETCH_ASSOC);
        $queryPays->closeCursor();
    }
}
?>