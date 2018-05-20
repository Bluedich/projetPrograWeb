<?php
include("functions.php");
include("header.php");
include("navbar.php"); ?>
<div class="container-fluid py-5 my-4">
  <div class="row">
    <div class="col-7 px-4 mx-5 rounded">
      <?php getHotLinks("index.php") ?>
    </div>
    <div class="col mx-4 rounded">
      <?php getLinkUpdates($_SESSION['u_id'])?>
    </div>
  </div>
</div>
<?php
include("footer.php") ?>
