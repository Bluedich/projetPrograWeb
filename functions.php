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
    $_SESSION['username']=$row['user'];
    $_SESSION['mail']=$row['mail'];
    $_SESSION['u_id']=$row['id'];
    $_SESSION['login']=true;
    header('Location: index.php'); //redirection to index
		die();
  }
  else{
    echo "Wrong username/password";
  }
}

function checkSignup($username,$mail,$pwd){
  $h = con();
  $r = mysqli_query($h, "SELECT id FROM users WHERE mail='$mail'");
  query_feedback($h);
  $r2 = mysqli_query($h, "SELECT id FROM users WHERE user='$username'");
  query_feedback($h);
  $row = mysqli_fetch_assoc($r);
  $row2 = mysqli_fetch_assoc($r2);
  if ($row!=NULL){
    echo "There is already an account associated with this email.<br>";
  }
  $r = mysqli_query($h, "SELECT id FROM users WHERE mail='$mail'");
  query_feedback($h);
  $row = mysqli_fetch_assoc($r);
  if ($row2!=NULL){
    echo "This username is already taken.<br>";
  }
  if($row==NULL && $row2==NULL){
    mysqli_query($h, "INSERT INTO users (mail, user, pwd) VALUES ('$mail','$username','$pwd')");
    query_feedback($h);
    header('Location: login.php'); //redirection to login
    die();
  }
}

function createLink($url,$text,$u_id){
  $h = con();
  mysqli_query($h, "INSERT INTO `links` (`text`, `url`, `author`, `date`) VALUES ('$text', '$url', '$u_id', CURRENT_TIMESTAMP)");
  query_feedback($h);
  header('Location: linkcreated.php');
  die();
}
?>
