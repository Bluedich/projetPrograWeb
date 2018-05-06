<?php
include("config.php");
include("functions.php");

$createDb = "CREATE DATABASE IF NOT EXISTS {$GLOBALS['dbName']};";

$cUsers = "CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mail` varchar(255) NOT NULL,
  `user` varchar(64) NOT NULL,
  `pwd` varchar(64) NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT unicity_users UNIQUE (`mail`, `user`)
) ENGINE=InnoDB;";

$cComments = "CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text` text NOT NULL,
  `author` int(11) NOT NULL,
  `link` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT fk_author_comments FOREIGN KEY (`author`) REFERENCES `users`(`id`),
  CONSTRAINT fk_link_comments FOREIGN KEY (`link`) REFERENCES `links`(`id`)
) ENGINE=InnoDB;";

$cLinks = "CREATE TABLE IF NOT EXISTS `links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text` varchar(255) NOT NULL,
  `url` varchar(2000) NOT NULL,
  `author` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `description` text NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`author`) REFERENCES `users`(`id`)
) ENGINE=InnoDB;";

$cLikes = "CREATE TABLE IF NOT EXISTS `likes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `comment` int(11),
  `link` int(11),
  `value` int(1) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`comment`) REFERENCES `comments`(`id`),
  FOREIGN KEY (`link`) REFERENCES `links`(`id`),
  CONSTRAINT chk_likes_objectUnicity CHECK ((`comment` = null AND `link` != null) OR
    (`link` = null AND `comment` != null)),
  CONSTRAINT chk_likes_value CHECK(`value` = 0 OR `value` = 1)
) ENGINE=InnoDB;";

echo "Connection to MySQL Database.";
echo "<br>";

//Create Database if it doesnt exist
$conn = new mysqli($GLOBALS['dbServ'],$GLOBALS['dbUser'],$GLOBALS['dbPass']);
mysqli_query($conn, $createDb);

$h = con();
query_feedback($h);

if (!$h) {
    die("Connection failed: " . mysqli_connect_error());
}

echo "Creating 'users' table.";
echo "<br>";
mysqli_query($h, $cUsers);
query_feedback($h);

echo "Creating 'links' table.";
echo "<br>";
mysqli_query($h, $cLinks);
query_feedback($h);

echo "Creating 'comments' table.";
echo "<br>";
mysqli_query($h, $cComments);
query_feedback($h);

echo "Creating 'likes' table.";
echo "<br>";
mysqli_query($h, $cLikes);
query_feedback($h);

mysqli_close($h);

?>
