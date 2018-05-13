<?php
include("functions.php");
include("header.php");
include("navbar.php"); ?>
<br><br><br>
<div class="container-fluid px-1">
  <div class="row">
    <div class="col-3 px-5">
    </div>
    <div class="col-6 px-5">
      <div class="row">
        <div class="col shadow-sm p-3 mb-5 bg-white rounded">
          <div class="row px-3">
            <?php
            $link_id = $_GET['link_id'];
            $author_id = getLinkAuthor($link_id);
            $author = getUsername($author_id);
            echo "<small class='text-muted'>Posted by &nbsp<a href='profile.php?id=$author_id'>$author</a></small>";
            ?>
          </div>
          <div class="row p-3">
            <?php
            $link_text = getLinkText($link_id);
            echo "<h5>$link_text</h3>";
            ?>
          </div>
          <?php if($_SESSION['u_id']==getLinkAuthor($link_id)){ ?>
          <div class="row px-3">
            <div class="col">
            </div>
            <div class="col-1">
              <?php echo "<a href='editLink.php?link_id=$link_id''>edit</a>"; ?>
            </div>
            <div class="col-1">
              <?php echo "<a href='deleteLink.php?link_id=$link_id''>delete</a>"; ?>
            </div>
          </div>
        <?php } ?>
        </div>
      </div>
    </div>
    <div class="col-3 px-5">
    </div>
  </div>
</div>
<?php
include("footer.php") ?>
