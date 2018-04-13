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
?>
