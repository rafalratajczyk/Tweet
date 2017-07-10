<?php

class Comment
{
    static private $conn;
    private $id;
    private $userId;
    private $tweetId;
    private $date;
    private $text;

    /**
     * Comment constructor.
     * @param $id
     * @param $userId
     * @param $tweetId
     * @param $date
     * @param $text
     */
    public function __construct($id, $userId, $tweetId, $date, $text)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->tweetId = $tweetId;
        $this->date = $date;
        $this->text = $text;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param mixed $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }



}