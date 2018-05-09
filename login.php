<?php
session_start();

include("functions.php");

if(isset($_POST['email']) && isset($_POST["pwd"])){
  checkLogin($_POST['email'],$_POST["pwd"]);
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta charset="utf-8">
		<meta content="width=device-width, initial-scale=1.0" name="viewport">
		<link rel="icon" type="image/png" href="images/favicon.png">
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
    <title><?php echo pageTitle()?>-Login</title>
	</head>
  <body class="text-center">
    <div class="row"><br><br></div>
    <div class="row">
      <div class="col-sm"></div>
      <div class="col-sm">
        <form method="post" action="login.php" class="form-signin">
          <h1 class="h3 mb-3 font-weight-normal">Please sign in</h1>
          <input name="email" type="email"  class="form-control" placeholder="Email address" required autofocus>
          <br>
          <input name="pwd" type="password" class="form-control" placeholder="Password" required>
          <br>
          <input type="submit" class="btn btn-primary btn-send" value="Log in" name="validate">
        </form>
        <br>
        Don't have an account ? <a href="signup.php">Signup</a> here.
      </div>
      <div class="col-sm"></div>
    </div>
    <div class="row"></div>
<?php include("footer.php"); ?>
