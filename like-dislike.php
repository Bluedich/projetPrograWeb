<?php
include('functions.php');
include('header.php');
if(isset($_GET['like'])){
  vote(1,$_SESSION['u_id'],$_GET['obj_id'],$_GET['obj_type']);
}
elseif(isset($_GET['dislike'])){
  vote(-1,$_SESSION['u_id'],$_GET['obj_id'],$_GET['obj_type']);
}
$location = 'Location: ' . $_GET['location'];
header($location);
die();
include('footer.php');
?>
