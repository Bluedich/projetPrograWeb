<?php
include("functions.php");
include("header.php");
include("navbar.php"); ?>
<br><br><br>
<div class="container-fluid px-1">
  <div class="row">
    <div class="col-7 px-5"><?php getHotLinks("index.php") ?>
    </div>
    <div class="col-5 px-5">
      <div class="row">
        <div class="col  shadow-sm p-3 mb-5 bg-white rounded">
        </div>
      </div>
    </div>
  </div>
</div>
<?php
for ($x=0; $x<100; $x++){
  echo "<br>";
}
include("footer.php") ?>
