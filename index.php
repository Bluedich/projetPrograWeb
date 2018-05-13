<?php
include("functions.php");
include("header.php");
include("navbar.php"); ?>
<div class="container-fluid py-5 my-4">
  <div class="row">
    <div class="col-7 px-4 mx-5 rounded"><?php getHotLinks("index.php") ?>
    </div>
    <div class="col px-5">
      <div class="row">
        <div class="col shadow-sm p-3 my-2 bg-white rounded">
        </div>
      </div>
    </div>
  </div>
</div>
<?php
include("footer.php") ?>
