<?php
include("functions.php");
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
		<div class="row"><br><br><br></div>
		<div class="row">
			<div class="col-sm"></div>
			<div class="col-sm">
				<?php
				if(isset($_POST['username']) && isset($_POST['email']) && isset($_POST["pwd"])){
			    checkSignup($_POST['username'], $_POST['email'],$_POST["pwd"]);
			  }
				?>
				<form method="post" action="signup.php">
					<div class="form-group row">
						<label for="inputEmail3" class="col-sm-2 col-form-label">Username</label>
						<div class="col-sm-10">
							<input name='username' type="text" pattern=".{4,}" required title="4 characters minimum" class="form-control" id="inputEmail3" placeholder="Username" required autofocus>
						</div>
					</div>
					<div class="form-group row">
						<label for="inputEmail3" class="col-sm-2 col-form-label">Email</label>
						<div class="col-sm-10">
							<input name="email" type="email"  class="form-control" placeholder="Email address" required autofocus>
						</div>
					</div>
					<div class="form-group row">
						<label for="inputPassword3" class="col-sm-2 col-form-label">Password</label>
						<div class="col-sm-10">
							<input name=pwd pattern=".{6,}" required title="4 characters minimum" type="password" class="form-control" placeholder="Password" required autofocus>
						</div>
					</div>
					<input type="submit" class="btn btn-success btn-send" value="Sign Up" name="validate">
				</form>
				Already have an account ? <a href="login.php">Log in</a> here.
			</div>
			<div class="col-sm"></div>
		</div>
		<div class="row"></div>
	</body>
<?php include("footer.php"); ?>
