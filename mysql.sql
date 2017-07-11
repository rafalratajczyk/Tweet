

CREATE TABLE `Users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `hash_pass` varchar(60) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE `Messages` (
  `sender_id` int(11) unsigned NOT NULL,
  `recipient_id` int(11) unsigned NOT NULL,
  `message` text NOT NULL,
  `sendDate` datetime NOT NULL,
  `readDate` datetime DEFAULT NULL,
  KEY `sender_id` (`sender_id`),
  KEY `recipient_id` (`recipient_id`),
  CONSTRAINT `Messages_ibfk_2` FOREIGN KEY (`recipient_id`) REFERENCES `Users` (`id`),
  CONSTRAINT `Messages_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `Users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `Tweets` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `text` varchar(140) NOT NULL DEFAULT '',
  `creationDate` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `Tweets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `Comments` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tweet_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `text` varchar(60) NOT NULL DEFAULT '',
  `creationDate` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tweet_id` (`tweet_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `Comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `Users` (`id`),
  CONSTRAINT `Comments_ibfk_1` FOREIGN KEY (`tweet_id`) REFERENCES `Tweets` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;