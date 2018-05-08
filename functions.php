<?php
include("config.php");

//Returns handle for connection to database
function con() {
  return mysqli_connect($GLOBALS['dbServ'], $GLOBALS['dbUser'],
  $GLOBALS['dbPass'], $GLOBALS['dbName']);
}

function pageTitle() {
  return $GLOBALS['defaultPageTitle'];
}

function query_feedback($handle){
  echo mysqli_info($handle);
  echo "<br>";
  echo mysqli_error($handle);
  echo "<br>";
}

function checkLogin($email,$pwd){
  //echo "email : $email\n";
  //echo "pwd: $pwd\n";
  $h = con();
  $r = mysqli_query($h, "SELECT * FROM users WHERE pwd='$pwd' AND mail='$email'");
  query_feedback($h);
  $row = mysqli_fetch_assoc($r);
  if ($row!=NULL){
    session_start();
    $_SESSION['username']=$row['user'];
    $_SESSION['mail']=$row['mail'];
    $_SESSION['user ID']=$row['id'];
    header('Location: index.php'); //redirection to index
		die();
  }
  else{
    echo "Wrong username/password";
  }
}
?>
