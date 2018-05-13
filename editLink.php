<?php
include("functions.php");
include("header.php");
if(isset($_POST['url']) && isset($_POST["text"]) && isset($_POST["link_id"])){
  $link_id=$_POST["link_id"];
  editLink($_POST['link_id'],$_POST['url'],$_POST["text"]);
}
else{
  $link_id=$_GET["link_id"];
}
?>
<?php include("navbar.php"); ?>
<br><br><br>
<div class="row"><br><br><br></div>
<div class="row">
  <div class="col-sm"></div>
  <div class="col-sm">
    <form method="post" action="editLink.php" class="form-signin">
      <img class="mb-4" src="https://getbootstrap.com/assets/brand/bootstrap-solid.svg" alt="" width="72" height="72">
      <h1 class="h3 mb-3 font-weight-normal">Edit Link</h1>
      <input name="url" type="url"  class="form-control" value=<?php $url=getLinkUrl($link_id); echo "$url"?> required autofocus>
      <br>
      <textarea name="text" type="text" rows="5" maxlength="1995" class="form-control" required><?php $text=getLinktext($link_id); echo "$text"?></textarea>
      <br>
      <input type="submit" class="btn btn-primary btn-send" value="Post" name="submit">
      <?php
      if(isset($link_id)){
        echo "<input type='hidden' value='$link_id' name='link_id'>";
      }
      ?>
      <a href="index.php"><button type="button" class="btn btn-secondary btn-send">Cancel</button></a>
    </form>
  </div>
  <div class="col-sm"></div>
</div>
<div class="row"></div>
<?php
include("footer.php") ?>
