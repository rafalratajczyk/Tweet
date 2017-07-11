<?php

require_once('connection.php');
require_once('Message.php');

class User
{
    private $id = -1;
    private $username;
    private $email;
    private $hashPass;

    public function validate()
    {
        return true;
    }

    public function login($email, $password): bool
    {
        $sql = "SELECT * FROM Users WHERE email = :email";

        $stmt = DB::getInstance()->prepare($sql);
        $result = $stmt->execute(['email' => $email]);

        if ($result && $stmt->rowCount() == 1) {
            $userData = $stmt->fetch(PDO::FETCH_ASSOC);

            if (password_verify($password, $userData['hash_pass'])) {

                session_start();
                $_SESSION['loggedin'] = true;
                $_SESSION['userID'] = $userData['id'];

                return true;

            }
        }
        return false;
    }

    public function delete(): bool
    {
        if (!$this->id != -1) {
            $sql = "DELETE Users, Comments, Tweets, Messages FROM Users, Comments, Tweets, Messages WHERE Comments.Users.id = :user_id AND Comments.recipient_id = :user_id AND Comments.sender_id = :user_id AND Users.id = :user_id";

            $stmt = DB::getInstance()->prepare($sql);

            if ($stmt->execute(['user_id' => $this->id])) {
                $this->id = -1;

                return true;
            }
        }

        return false;
    }

    public static function all()
    {
        $sql = "SELECT * FROM Users ORDER BY id ASC";
        $users = [];

        $result = DB::getInstance()->query($sql);

        if ($result && $result->rowCount() > 0) {
            foreach ($result->fetchAll(PDO::FETCH_ASSOC) as $userData) {
                $user = new User();

                $user->id = $userData['id'];
                $user->username = $userData['username'];
                $user->email = $userData['email'];
                $user->hashPass = $userData['hash_pass'];

                $users[] = $user;
            }
        }

        return $users;
    }

    public static function byID($id)
    {
        $sql = "SELECT * FROM Users WHERE id = :id";
        $stmt = DB::getInstance()->prepare($sql);
        $result = $stmt->execute(['id' => $id]);

        if ($result && $stmt->rowCount() == 1) {
            $userData = $stmt->fetch(PDO::FETCH_ASSOC);

            $user = new User();

            $user->id = $userData['id'];
            $user->username = $userData['username'];
            $user->email = $userData['email'];
            $user->hashPass = $userData['hash_pass'];

            return $user;
        } else {
            return null;
        }
    }

    public function save()
    {
        $conn = DB::getInstance();
        if ($this->id == -1) {
            //INSERT
            $sql = "INSERT INTO Users(email, hash_pass) VALUES (:email, :hash_pass)";
            $stmt = $conn->prepare($sql);

            if ($stmt->execute([
                ':email' => $this->email,
                ':hash_pass' => $this->hashPass])
            ) {
                $this->id = $conn->lastInsertId();

                $_SESSION['loggedin'] = true;
                $_SESSION['userID'] = $this->id;
                return true;
            } else {
                return false;
            }

        } else {
            //UPDATE
            $sql = "UPDATE Users SET username = :username, email = :email, hash_pass = :hash_pass WHERE id = :id";
            $stmt = $conn->prepare($sql);

            return $stmt->execute([
                ':username' => $this->username,
                ':email' => $this->email,
                ':hash_pass' => $this->hashPass,
                'id' => $this->id,
            ]);
        }
    }

    public function getTweets()
    {

        $sql = 'SELECT * FROM Tweets WHERE user_id = :user_id ORDER BY creationDate DESC';

        $tweets = [];
        $stmt = DB::getInstance()->prepare($sql);
        $result = $stmt->execute(['user_id' => $this->getId()]);

        if ($result && $stmt->rowCount() > 0) {

            foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $tweetData) {
                $tweet = new Tweet();

                $tweet
                    ->setId($tweetData['id'])
                    ->setUserId($tweetData['user_id'])
                    ->setText($tweetData['text'])
                    ->setCreationDate($tweetData['creationDate']);

                $tweets[] = $tweet;
            }
        }

        return $tweets;
    }

    public function getMessages()
    {

        $sql = 'SELECT * FROM Messages WHERE recipient_id = :recipient_id ORDER BY sendDate DESC';

        $stmt = DB::getInstance()->prepare($sql);
        $result = $stmt->execute(['recipient_id' => $this->getId()]);

        if ($result && $stmt->rowCount() > 0) {

            $messagesArray = array();

            $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($messages as $m) {
                $message = new Message();
                $message
                    ->setId($m['id'])
                    ->setRecipient($m['recipient_id'])
                    ->setSender($m['sender_id'])
                    ->setText($m['text'])
                    ->setSendDate($m['sendDate'])
                    ->setReadDate($m['readDate']);

                if (!array_key_exists($m['sender_id'], $messagesArray)) {
                    $messagesArray[$m['sender_id']] = $message;
                }
            }
            return $messagesArray;

        } else {
            return [];
        }

    }

    public function getSelectList()
    {

        $users = self::all();
        $usersList = array();

        foreach ($users as $user) {
            if ($user->getId() != $this->getId()) {
                if ($user->getUsername()) {
                    $usersList[$user->getId()] = $user->getUsername();
                } else {
                    $usersList[$user->getId()] = $user->getEmail();
                }
            }
        }

        return $usersList;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @param mixed $hashPass
     */
    public function setHashPass($hashPass)
    {
        $this->hashPass = $hashPass;
    }


}
