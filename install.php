<?php
include("config.php");
include("functions.php");

function query_feedback($handle){
  echo mysqli_info($handle);
  echo "<br>";
  echo mysqli_error($handle);
  echo "<br>";
}

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
$h = con();

if (!$h) {
    die("Connection failed: " . mysqli_connect_error());
}

//Create Database if it doesnt exist
mysqli_query($h, $createDb);
query_feedback($h);

echo "Creating 'users' table.\n";
echo "<br>";
mysqli_query($h, $cUsers);
query_feedback($h);

echo "Creating 'links' table.\n";
echo "<br>";
mysqli_query($h, $cLinks);
query_feedback($h);

echo "Creating 'comments' table.\n";
echo "<br>";
mysqli_query($h, $cComments);
query_feedback($h);

echo "Creating 'likes' table.\n";
echo "<br>";
mysqli_query($h, $cLikes);
query_feedback($h);

mysqli_close($h);

?>
