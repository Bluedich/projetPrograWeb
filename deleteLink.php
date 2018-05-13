<?php
include("functions.php");
include("header.php");
deleteLink($_GET['link_id'])
?>
<?php include("navbar.php"); ?>
<div class="row"><br><br><br></div>
<div class="row">
  <div class="col-sm"></div>
  <div class="col-sm">
    <div class="alert alert-success" role="alert">
      Link succesfully deleted.
    </div>
    <br>
    <a href="index.php"><button type="button" class="btn btn-success">OK</button></a>
  </div>
  <div class="col-sm"></div>
</div>
<div class="row"></div>

<?php
include("footer.php") ?>
