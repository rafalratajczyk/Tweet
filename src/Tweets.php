<?php

class Tweet
{
    static private $conn;

    private $id;
    private $userId;
    private $text;
    private $creationDate;

    /**
     * Tweet constructor.
     * @param $id
     * @param $userId
     * @param $text
     * @param $creationDate
     */
    public function __construct($id, $userId, $text, $creationDate)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->text = $text;
        $this->creationDate = $creationDate;
    }

    static public function setConnection(mysqli $newConnection)
    {
        self::$conn = $newConnection;
    }

    static public function createTweet($userId, $text)
    {
        if (is_string($text) && strlen($text) <= 140) {
            $sql = "INSERT INTO Tweets(text, creation_date, user_id) VALUES ('$text', NOW(), '$userId')";
            $result = self::$conn->query($sql);

            if ($result == true) {
                $myTweet = new Tweet(self::$conn->insert_id, $userId, date("Y-m-d H:i:s"), $text);
                return $myTweet;
            }
        }

        return false;
    }

    static public function getTweetById($id)
    {
        $sql = "SELECT * FROM Tweets WHERE tweet_id=$id";
        $result = self::$conn->query($sql);

        if ($result == true) {
            if ($result->num_rows == 1) {
                $row = $result->fetch_assoc();
                $tweetById = new Tweet($row["tweet_id"], $row["user_id"], $row["text"], $row["creation_date"]);
                return $tweetById;
            }
        }

        return false;
    }

    static public function loadAllTweets()
    {
        $ret = [];
        $sql = "SELECT * FROM Tweets ORDER BY creation_date DESC";
        $result = self::$conn->query($sql);

        if ($result == true) {
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $loadedTweets = new Tweet($row["tweet_id"],
                        $row["user_id"],
                        $row["text"],
                        $row["creation_date"]);
                    $ret[] = $loadedTweets;
                }
            }
        }
        return $ret;
    }

    static public function loadAllUserTweets($userId)
    {
        $ret = [];
        $sql = "SELECT * FROM Tweets WHERE user_id={$userId}";
        $result = self::$conn->query($sql);

        if ($result == true) {
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $tweetsByUserId = new Tweet($row["tweet_id"],
                        $row["user_id"],
                        $row["text"],
                        $row["creation_date"]);
                    $ret[] = $tweetsByUserId;

                }
            }
        }
        return $ret;
    }

    public function updateTweet()
    {
        $sql = "UPDATE Tweets SET text='{$this->text}' WHERE tweet_id='{$this->id}' ";
        $result = self::$conn->query($sql);
        return $result;
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
    public function getText()
    {
        return $this->text;
    }

    /**
     * @return mixed
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * @param mixed $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }


}
