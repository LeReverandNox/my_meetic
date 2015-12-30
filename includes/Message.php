<?php
Class Message
{
    protected $_db;
    protected $_validator;

    protected $_id;
    protected $_recipient_name;
    protected $_recipient_id;
    protected $_author_name;
    protected $_author_id;
    protected $_title;
    protected $_content;
    protected $_date;
    protected $_status;

    public function __construct($db, $id = null)
    {
        $this->_db = $db;
        $this->_validator = new FormValidator($db);

        if ($id)
        {
            $sql = "SELECT pm.id, pm.pm_author_id, pm.pm_recipient_id, pm.pm_title, pm.pm_content, DATE_FORMAT( pm.pm_date, '%d/%m/%Y à %H:%i:%s') AS pm_date, pm.pm_status, author.user_login AS author_name, recipient.user_login AS recipient_name
            FROM privmsgs AS pm
            INNER JOIN users AS author ON pm.pm_author_id = author.id
            INNER JOIN users AS recipient ON pm.pm_recipient_id = recipient.id
            WHERE pm.id = :id";
            $queryMsg = $this->_db->prepare($sql);
            $queryMsg->bindParam(":id", $id, PDO::PARAM_INT);
            $queryMsg->execute();
            $data = $queryMsg->fetch();
            $queryMsg->closeCursor();

            $this->_id = $data["id"];
            $this->_recipient_name = $data["recipient_name"];
            $this->_recipient_id = $data["pm_recipient_id"];
            $this->_author_name = $data["author_name"];
            $this->_author_id = $data["pm_author_id"];
            $this->_title = $data["pm_title"];
            $this->_content = $data["pm_content"];
            $this->_date = $data["pm_date"];
            $this->_status = $data["pm_status"];
        }
    }

    public function getId()
    {
        return $this->_id;
    }
    public function getRecipientName()
    {
        return $this->_recipient_name;
    }
    public function getRecipientId()
    {
        return $this->_recipient_id;
    }
    public function getAuthorName()
    {
        return $this->_author_name;
    }
    public function getAuthorId()
    {
        return $this->_author_id;
    }
    public function getTitle()
    {
        return $this->_title;
    }
    public function getContent()
    {
        return $this->_content;
    }
    public function getContentBr()
    {
        return preg_replace("/<br \/>/", "", $this->_content);
    }
    public function getDate()
    {
        return $this->_date;
    }
    public function getStatus()
    {
        return $this->_status;
    }
    public function getStatusText()
    {
        if ($this->_status == 0)
        {
            return "Non-Lu";
        }
        if ($this->_status == 1)
        {
            return "Lu";
        }
        if ($this->_status == 2)
        {
            return "Supprimé";
        }
    }
    public function setRecipientName($recipient_name)
    {
        $this->_recipient_name = $recipient_name;
    }
    public function setRecipientId($recipient_id)
    {
        $this->_recipient_id = $recipient_id;
    }
    public function setAuthorName($author_name)
    {
        $this->_author_name = $author_name;
    }
    public function setAuthorId($author_id)
    {
        $this->_author_id = $author_id;
    }
    public function setTitle($title)
    {
        $this->_title = $title;
    }
    public function setContent($content)
    {
        $this->_content = $content;
    }
    public function setStatus($status)
    {
        $this->_status = $status;
    }

    public static function getSentMessages($db, $author_id)
    {
        $sql = "SELECT pm.id FROM privmsgs AS pm
        WHERE pm.pm_status <> 2 AND pm.pm_author_id = :author_id
        ORDER BY pm.pm_date DESC";
        $querySentMsgs =  $db->prepare($sql);
        $querySentMsgs->bindParam(":author_id", $author_id, PDO::PARAM_INT);
        $querySentMsgs->execute();
        $data = $querySentMsgs->fetchAll(PDO::FETCH_ASSOC);
        $querySentMsgs->closeCursor();

        $sent_messages = [];
        foreach ($data as $msg)
        {
            $message = new Message($db, $msg["id"]);
            array_push($sent_messages, $message);
        }

        return $sent_messages;
    }

    public static function getReceivedMessages($db, $recipient_id)
    {
        $sql = "SELECT pm.id FROM privmsgs AS pm
        WHERE pm.pm_status <> 2 AND pm.pm_recipient_id = :recipient_id
        ORDER BY pm.pm_date DESC";
        $queryReceivedMsgs = $db->prepare($sql);
        $queryReceivedMsgs->bindParam(":recipient_id", $recipient_id, PDO::PARAM_INT);
        $queryReceivedMsgs->execute();
        $data = $queryReceivedMsgs->fetchAll(PDO::FETCH_ASSOC);
        $queryReceivedMsgs->closeCursor();

        $messages = [];
        foreach ($data as $msg)
        {
            $message = new Message($db, $msg["id"]);
            array_push($messages, $message);
        }

        return $messages;
    }

    public static function getDeletedMessages($db, $recipient_id)
    {
        $sql = "SELECT pm.id FROM privmsgs AS pm WHERE pm.pm_status = 2 AND pm.pm_recipient_id = :recipient_id
        ORDER BY pm.pm_date DESC";
        $queryDeletedMsgs = $db->prepare($sql);
        $queryDeletedMsgs->bindParam(":recipient_id", $recipient_id, PDO::PARAM_INT);
        $queryDeletedMsgs->execute();
        $data = $queryDeletedMsgs->fetchAll(PDO::FETCH_ASSOC);
        $queryDeletedMsgs->closeCursor();

        $deleted_messages = [];
        foreach ($data as $msg)
        {
            $message = new Message($db, $msg["id"]);
            array_push($deleted_messages, $message);
        }

        return $deleted_messages;
    }

    public function prepareSend($author_id)
    {
        $this->setRecipientId($this->_validator->validateMessageRecipient($author_id));
        $this->setAuthorId($author_id);
        $this->setTitle($this->_validator->validateMessageTitle());
        $this->setContent($this->_validator->validateMessageContent());

        if (empty($_SESSION["ERROR"]))
        {
            return true;
        }
        return false;
    }

    public function sendMessage($author_id)
    {
        if ($this->prepareSend($author_id))
        {
            $sql = "INSERT INTO privmsgs (pm_author_id, pm_recipient_id, pm_title, pm_content, pm_date)
            VALUES (:author_id, :recipient_id, :title, :content, NOW())";
            $querySendMsg = $this->_db->prepare($sql);
            $querySendMsg->bindParam(":author_id", $this->_author_id, PDO::PARAM_INT);
            $querySendMsg->bindParam(":recipient_id", $this->_recipient_id, PDO::PARAM_INT);
            $querySendMsg->bindParam(":title", $this->_title, PDO::PARAM_STR);
            $querySendMsg->bindParam(":content", $this->_content, PDO::PARAM_STR);
            $querySendMsg->execute();

            $id_msg = $this->_db->lastInsertId();

            $this->sendNotif($id_msg);
            $_SESSION["INFOS"] = "Votre message a été envoyé.";
            return true;
        }
        return false;
    }

    private function sendNotif($id_msg)
    {
        $recipient = new User($this->_db, $this->_recipient_id);
        $author = new User($this->_db, $this->_author_id);
        $_SESSION["INFOS"] = var_dump($recipient);
        $from = "lereverandnox@gmail.com";
        $to = $recipient->getEmail();

        $headers = "From: <" . $from . ">\r\n";
        $headers .= "Reply-To: <" . $from . ">\r\n";
        $headers .= "Return-Path:  < " . $from . " >\r\n";
        $headers .= "Sender: <" . $from . ">\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Date: <" . date("r", time()). ">\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

        $subject = "Vous avez reçu un nouveau message !";
        $message ="<html><body>";
        $message .= "<p>Bonjour " . $recipient->getLogin() . ", vous avez reçu un nouveau message privé de la part de " . $author->getLogin() . "</p>";
        $message .= "<p>Vous pouvez le consulter en cliquant <a href='http://localhost/S1%20-%20PHP/PHP_my_meetic/messagerie.php?mode=read&amp;id=" . $id_msg . "''>ici</a> !</p>";
        $message .= "<p>A très vite parmis nous !</p>";
        $message .= "<p>L'équipe <a href='http://localhost/S1%20-%20PHP/PHP_my_meetic/'>My_Meetic</a></p>";
        $message .= "</body></html>";

        mail($to, $subject, $message, $headers, "-r $from");

        return true;
    }

    public static function prepareMove($db, $user_id, $msg_id)
    {
        $msg_to_move = new Message($db, intval($msg_id));
        if ($msg_to_move->getRecipientId() === $user_id)
        {
            return $msg_to_move;
        }
        else
        {
            $_SESSION["ERROR"]["move"] = "Vous n'avez pas le droit de déplacer ce message.";
        }
    }

    public function deleteMessage()
    {
        $sql = "UPDATE privmsgs SET pm_status = 2 WHERE id = :id";
        $queryDeleteMsg = $this->_db->prepare($sql);
        $queryDeleteMsg->bindParam(":id", $this->_id, PDO::PARAM_INT);
        $queryDeleteMsg->execute();

        $_SESSION["INFOS"] = "Le message a été supprimé.";
        return true;
    }

    public function restoreMessage()
    {
        $sql = "UPDATE privmsgs SET pm_status = 1 WHERE id = :id";
        $queryRestoreMsg = $this->_db->prepare($sql);
        $queryRestoreMsg->bindParam(":id", $this->_id, PDO::PARAM_INT);
        $queryRestoreMsg->execute();

        $_SESSION["INFOS"] = "Le message a été restauré.";
        return true;
    }

    public function markAsRead()
    {
        $sql = "UPDATE privmsgs
        SET pm_status = 1
        WHERE id = :id";
        $queryMAR = $this->_db->prepare($sql);
        $queryMAR->bindParam(":id", $this->_id, PDO::PARAM_INT);
        $queryMAR->execute();
    }

    public static function readMessage($db, $user_id, $msg_id)
    {
        $read_msg = new Message($db, intval($msg_id));
        if ($read_msg->getAuthorId() === $user_id || $read_msg->getRecipientId() === $user_id)
        {
            if ($read_msg->getStatus() == 2)
            {
                $_SESSION["ERROR"]["deleted"] = "Ce message est supprimé.";
                return false;
            }

            if ($read_msg->getRecipientId() === $user_id)
            {
                $read_msg->markAsRead();
            }

            return $read_msg;
        }
        else
        {
            $_SESSION["ERROR"]["denied"] = "Vous n'avez pas le droit de lire ce message.";
            return false;
        }
    }

    public static function countNewMessages($db, $user_id)
    {
        $sql = "SELECT COUNT(pm.id) AS nb FROM privmsgs AS pm WHERE pm.pm_recipient_id = :recipient_id AND pm_status = 0";
        $queryNewMsgs = $db->prepare($sql);
        $queryNewMsgs->bindParam(":recipient_id", $user_id, PDO::PARAM_INT);
        $queryNewMsgs->execute();
        $data = $queryNewMsgs->fetch();
        $queryNewMsgs->closeCursor();

        if ($data)
        {
            return $data["nb"];
        }
        return false;
    }
}
?>