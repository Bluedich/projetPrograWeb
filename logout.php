<?php
  include('functions.php');
  session_start();
  updateLastCon($_SESSION['u_id']);
  session_destroy();
  header("Location: login.php");
?>
