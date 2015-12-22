<?php
Class Message
{
    protected $_db;
    protected $_validator;

    public function __construct($db)
    {
        $this->_db = $db;
        $this->_validator = new FormValidator($db);
    }

    public function getSentMessages()
    {
        $sql = "SELECT * FROM priv";
    }

    public function getReceivedMessages()
    {

    }

    public function sendMessage()
    {

    }

    public function deleteMessage()
    {

    }
}
?>