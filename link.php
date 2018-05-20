<?php
include("functions.php");
include("header.php");
include("navbar.php");

if(isset($_POST['submit']) && isset($_GET['link_id'])){
  $text=$_POST['text'];
  $link_id=$_GET['link_id'];
  $u_id=$_SESSION['u_id'];
  addComment($text, $u_id, $link_id);
}
?>
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
            $u_id = $_SESSION['u_id'];
            $u_name = $_SESSION['username'];
            $link_id = $_GET['link_id'];
            $author_id = getLinkAuthor($link_id);
            $author = getUsername($author_id);
            echo "<small class='text-muted'>Posted by &nbsp<a href='profile.php?id=$author_id'>$author</a></small>";
            ?>
          </div>
          <div class="row px-3">
            <?php
              $link_url = getLinkUrl($link_id);
              likeDislikeButton("link", $link_id, "link.php?link_id=$link_id"); echo "&nbsp<small class='text-muted text-truncate'><a href='$link_url'>$link_url</a></small>";
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
                <?php echo "<a href='editLink.php?link_id=$link_id'>edit</a>"; ?>
              </div>
              <div class="col-1">
                <?php echo "<a href='deleteLink.php?link_id=$link_id'>delete</a>"; ?>
              </div>
            </div>
          <?php } ?>
          <div class="row px-3">
            <div class="col-12">
              <?php echo "<form method='post' action='link.php?link_id=$link_id' class='form-signin'>";?>
                <?php echo "<small class='text-muted'>Comment as &nbsp<a href='profile.php?id=$u_id'>$u_name</a></small>";?>
                <textarea name="text" type="text" rows="5" maxlength="1995" class="form-control" placeholder="Comment" required></textarea>
                <br>
                <input type="submit" class="btn btn-primary btn-send" value="Post Comment" name="submit">
              </form>
            </div>
          </div>
          <?php dispComments($link_id) ?>
        </div>
      </div>
    </div>
    <div class="col-3 px-5">
    </div>
  </div>
</div>
<?php
include("footer.php") ?>
