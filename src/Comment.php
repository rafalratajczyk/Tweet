<?php

class Comment
{
    static private $conn;
    private $id;
    private $userId;
    private $tweetId;
    private $creationDate;
    private $text;

    static public function setConn(mysqli $newComment)
    {
        self::$conn = $newComment;
    }

    static public function newComments($userId, $tweetId, $text)
    {
        $sqlQuerry = "INSERT INTO Comments (user_id, tweet_id, creation_date, text )
                VALUES ('$userId', '$tweetId', NOW(), '$text' )";
        $result = self::$conn->query($sqlQuerry);

        if ($result = true && strlen($result) <= 60) {
            $myCommnents = new Comment(self::$conn->insert_id, $userId, $tweetId, $text, date("Y-m-d H:i:s"));

            return $myCommnents;
        }
    }

    static public function getCommentsByIdOfTweet($tweedId)
    {
        $sqlQuerry = "INSERT INTO Comments (user_id, tweet_id, creation_date, text )
                VALUES ('$userId', '$tweetId', NOW(), '$text' )";
        $result = self::$conn->query($sqlQuerry);
        $array = [];

        if ($result === true) {
            while ($row = $result->fetch_assoc()) {
                $commentsByIdOfTweet = new Comment(
                    $row['comment_id'],
                    $row['user_id'],
                    $row['tweet_id'],
                    $row['creation_date'],
                    $row['text']
                );
                $array = $commentsByIdOfTweet;
            }
        }

        return $array;
    }

    /**
     * Comment constructor.
     * @param $id
     * @param $userId
     * @param $tweetId
     * @param $date
     * @param $text
     */
    public function __construct($id, $userId, $tweetId, $creationDate, $text)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->tweetId = $tweetId;
        $this->creationDate = $creationDate;
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
    public function getCreationDate()
    {
        return $this->creationDate;
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