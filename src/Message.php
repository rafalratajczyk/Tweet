<?php
require_once('connection.php');


class Message
{
    private $id = -1;
    private $sender;
    private $recipient;
    private $text;
    private $sendDate;
    private $readDate;


    public function sendMessage($sender_id, $recipient_id, $text)
    {
        $message = new Message();

        $message
            ->setSender($sender_id)
            ->setRecipient($recipient_id)
            ->setText($text)
            ->setSendDate(date("Y-m-d H:i:s"));


        if ($message->saveToDB()) {
            return true;
        } else {
            return false;
        }
    }

    private function saveToDB()
    {
        if ($this->id == -1) {
            //INSERT
            $sql = "INSERT INTO Messages(sender_id, recipient_id, text, sendDate) VALUES (:sender_id, :recipient_id, :text, :sendDate)";
            $stmt = DB::getInstance()->prepare($sql);


            if ($stmt->execute([
                ':sender_id' => $this->getSender(),
                ':recipient_id' => $this->getRecipient(),
                ':text' => $this->getText(),
                ':sendDate' => $this->getSendDate()])
            ) {
                return true;
            } else {
                return false;
            }

        }
        return false;
    }

    public static function bySenderId($sender_id)
    {


        $sql = "SELECT * FROM Messages WHERE sender_id = :sender_id ORDER BY sendDate DESC";
        $stmt = DB::getInstance()->prepare($sql);
        $result = $stmt->execute(['sender_id' => $sender_id]);

        if ($result && $stmt->rowCount() == 1) {
            $messageData = $stmt->fetch(PDO::FETCH_ASSOC);

            $message = new Message();

            $message
                ->setId($messageData['id'])
                ->setSender(['sender_id'])
                ->setRecipient($messageData['recipient_id'])
                ->setText($messageData['text'])
                ->setSendDate($messageData['sendDate'])
                ->setReadDate($messageData['readDate']);

            return $message;
        } else {
            return [];
        }
    }


    /**
     * @return mixed
     */
    public
    function getSender()
    {
        return User::byID($this->recipient);
    }

    /**
     * @param mixed $sender
     * @return Message
     */
    public
    function setSender($sender)
    {
        $this->sender = $sender;
        return $this;
    }

    /**
     * @return mixed
     */
    public
    function getRecipient()
    {
        return $this->recipient;
    }

    /**
     * @param mixed $recipient
     * @return Message
     */
    public
    function setRecipient($recipient)
    {
        $this->recipient = $recipient;
        return $this;
    }

    /**
     * @return mixed
     */
    public
    function getText()
    {
        return $this->text;
    }

    /**
     * @param mixed $text
     * @return Message
     */
    public
    function setText($text)
    {
        $this->text = $text;
        return $this;
    }

    /**
     * @return mixed
     */
    public
    function getSendDate()
    {
        return $this->sendDate;
    }

    /**
     * @param mixed $sendDate
     * @return Message
     */
    public
    function setSendDate($sendDate)
    {
        $this->sendDate = $sendDate;
        return $this;
    }

    /**
     * @return mixed
     */
    public
    function getReadDate()
    {
        return $this->readDate;
    }

    /**
     * @param mixed $readDate
     * @return Message
     */
    public
    function setReadDate($readDate)
    {
        $this->readDate = $readDate;
        return $this;
    }

    /**
     * @return int
     */
    public
    function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Message
     */
    public
    function setId($id)
    {
        $this->id = $id;
        return $this;
    }
}