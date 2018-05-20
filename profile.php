<?php
include("functions.php");
include("header.php");
?>
<?php include("navbar.php");
if (isset($_GET['u_id'])){
    $u_id = $_GET['u_id'];
}
else{
  $u_id = $_SESSION['u_id'];
}
?>
<br><br><br>
<div class="container-fluid px-5">
  <div class="row">
    <div class="col-12 p-3 mx-3">
      <div class="row">
        <div class="col shadow-sm p-3 mb-5 bg-white rounded">
          <?php include("profilenav.php") ?>
          <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="links" role="tabpanel" aria-labelledby="nav-links-tab-tab">
              <?php getPostedLinks($u_id) ?>
            </div>
            <div class="tab-pane fade" id="comments" role="tabpanel" aria-labelledby="nav-comments-tab">
              <?php getPostedComments($u_id) ?>
            </div>
            <div class="tab-pane fade" id="votes" role="tabpanel" aria-labelledby="nav-votes-tab">
              <?php getLikes($u_id) ?>
            </div>
          </div>
        </div>
      </div>
    </div>
    </div>
  </div>
</div>
<?php
include("footer.php") ?>
