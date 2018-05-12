<?php
include("functions.php");
include("header.php");
  if(isset($_POST['url']) && isset($_POST["text"])){
    createLink($_POST['url'],$_POST["text"],$_SESSION["u_id"]);
  }
?>
<?php include("navbar.php"); ?>
<br><br><br>
<div class="row"><br><br><br></div>
<div class="row">
  <div class="col-sm"></div>
  <div class="col-sm">
    <form method="post" action="createlink.php" class="form-signin">
      <img class="mb-4" src="https://getbootstrap.com/assets/brand/bootstrap-solid.svg" alt="" width="72" height="72">
      <h1 class="h3 mb-3 font-weight-normal">Post a link</h1>
      <input name="url" type="url"  class="form-control" placeholder="Link url" required autofocus>
      <br>
      <input name="text" type="text" class="form-control" placeholder="Link text" required>
      <br>
      <input type="submit" class="btn btn-primary btn-send" value="Post" name="submit">
      <a href="index.php"><button type="button" class="btn btn-secondary btn-send">Cancel</button></a>
    </form>
  </div>
  <div class="col-sm"></div>
</div>
<div class="row"></div>
<?php
include("footer.php") ?>
