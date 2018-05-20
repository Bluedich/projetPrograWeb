 <?php
include("config.php");

//Returns handle for connection to database
function con() {
  return mysqli_connect($GLOBALS['dbServ'], $GLOBALS['dbUser'],
  $GLOBALS['dbPass'], $GLOBALS['dbName']);
}

function pageTitle() {
  return $GLOBALS['defaultPageTitle'];
}

function query_feedback($handle){
  echo mysqli_info($handle);
  echo mysqli_error($handle);
}

function checkLogin($email,$pwd){
  //echo "email : $email\n";
  //echo "pwd: $pwd\n";
  $h = con();
  $r = mysqli_query($h, "SELECT * FROM users WHERE pwd='$pwd' AND mail='$email'");
  query_feedback($h);
  $row = mysqli_fetch_assoc($r);
  if ($row!=NULL){
    $_SESSION['username']=$row['user'];
    $_SESSION['mail']=$row['mail'];
    $_SESSION['u_id']=$row['id'];
    $_SESSION['login']=true;
    header('Location: index.php'); //redirection to index
		die();
  }
  else{
    echo "Wrong username/password";
  }
}

function checkSignup($username,$mail,$pwd){
  $h = con();
  $r = mysqli_query($h, "SELECT id FROM users WHERE mail='$mail'");
  query_feedback($h);
  $r2 = mysqli_query($h, "SELECT id FROM users WHERE user='$username'");
  query_feedback($h);
  $row = mysqli_fetch_assoc($r);
  $row2 = mysqli_fetch_assoc($r2);
  if ($row!=NULL){
    echo "There is already an account associated with this email.<br>";
  }
  $r = mysqli_query($h, "SELECT id FROM users WHERE mail='$mail'");
  query_feedback($h);
  $row = mysqli_fetch_assoc($r);
  if ($row2!=NULL){
    echo "This username is already taken.<br>";
  }
  if($row==NULL && $row2==NULL){
    mysqli_query($h, "INSERT INTO users (mail, user, pwd, last_con) VALUES ('$mail','$username','$pwd', CURRENT_TIMESTAMP)");
    query_feedback($h);
    header('Location: login.php'); //redirection to login
    die();
  }
}

function createLink($url,$text,$u_id){
  $h = con();
  mysqli_query($h, "INSERT INTO `links` (`text`, `url`, `author`, `date`) VALUES ('$text', '$url', '$u_id', CURRENT_TIMESTAMP)");
  mysqli_close($h);
  query_feedback($h);
  header('Location: linkcreated.php');
  die();
}

function editLink($link_id,$n_url,$n_text){
  $h = con();
  if($_SESSION['u_id']==getLinkAuthor($link_id)){
    mysqli_query($h, "UPDATE links SET `text` ='$n_text', `url` = '$n_url', `date` = CURRENT_TIMESTAMP WHERE id='$link_id'");
    echo "<br><br><br><br><br><br><br>";
    query_feedback($h);
    $location = "Location: link.php?link_id=$link_id";
    header($location);
    die();
  }
  else{
    exit("You're not allowed to modify this link.");
  }
}

function deleteLink($id){
  if($_SESSION['u_id']==getLinkAuthor($id)){
    deleteLinkLikes($id); //We need to delete link's likes and dislikes first
    deleteLinkComments($id);//Ass well as it's comments
    $h = con();
    mysqli_query($h, "DELETE FROM links WHERE id='$id'");
    query_feedback($h);
  }
  else{
    exit("You're not allowed to delete this link.");
  }
}

function deleteLinkLikes($link_id){
  $h = con();
  mysqli_query($h, "DELETE FROM likes WHERE link='$link_id'");
  query_feedback($h);
}

function deleteLinkComments($link_id){
  $h = con();
  $r = mysqli_query($h, "SELECT likes.id like_id FROM comments c JOIN links ON c.link=links.id JOIN likes ON likes.comment = c.id WHERE links.id = '$link_id'");
  $row=mysqli_fetch_assoc($r);
  while($row){
    $like_id=$row['like_id'];
    mysqli_query($h, "DELETE FROM likes WHERE id='$like_id'");
    $row=mysqli_fetch_assoc($r);
  }
  mysqli_query($h, "DELETE FROM comments WHERE link='$link_id'");
  query_feedback($h);
}

function addComment($text, $u_id, $link_id){
  $h = con();
  mysqli_query($h, "INSERT INTO `comments` (`text`, `author`, `link`, `date`) VALUES ('$text', '$u_id', '$link_id', CURRENT_TIMESTAMP)");
  query_feedback($h);
}

function deleteComment($id){
  deleteCommentLikes($id); //We need to delete the comment's likes and dislikes first
  $h = con();
  mysqli_query($h, "DELETE FROM comments WHERE id='$id'");
  query_feedback($h);
}

function deleteCommentLikes($comm_id){
  $h = con();
  mysqli_query($h, "DELETE FROM likes WHERE comment='$comm_id'");
  query_feedback($h);
}

function deleteLike($id){
  $h = con();
  mysqli_query($h, "DELETE FROM likes WHERE id='$id'");
  query_feedback($h);
}

function vote($value, $u_id, $obj_id, $obj_type){
  //check if a vote already exists for $u_id/$obj_id/$obj_type triplet
  $h = con();
  $r = mysqli_query($h, "SELECT * FROM likes WHERE user='$u_id' AND $obj_type='$obj_id'");
  query_feedback($h);
  $row=mysqli_fetch_assoc($r);
  if($row){
    $row_id = $row['id'];
    if ($row['value']==$value){//like + like = no like, dislike + dislike = no like
      deleteLike($row_id);
    }
    else{ //like + dislike = dislike, dislike + like = like
      mysqli_query($h, "UPDATE likes SET value = '$value', `date` = CURRENT_TIMESTAMP WHERE id='$row_id'");
      query_feedback($h);
    }
  }
  else{
    if($obj_type=='comment'){
      mysqli_query($h, "INSERT INTO likes (`comment`, `value`, `date`, `user`) VALUES ('$obj_id', '$value', CURRENT_TIMESTAMP, '$u_id')");
      query_feedback($h);
    }
    else{
      mysqli_query($h, "INSERT INTO likes (`link`, `value`, `date`, `user`) VALUES ('$obj_id', '$value', CURRENT_TIMESTAMP, '$u_id')");
      query_feedback($h);
    }
  }
}

function getUsername($id){
  $h = con();
  $r = mysqli_query($h, "SELECT user FROM users WHERE id='$id'");
  query_feedback($h);
  $row=mysqli_fetch_assoc($r);
  return $row['user'];
}

function userLiked($obj_type,$obj_id,$u_id){
  $h = con();
  $r = mysqli_query($h, "SELECT value FROM likes WHERE user='$u_id' AND $obj_type='$obj_id'");
  query_feedback($h);
  $row=mysqli_fetch_assoc($r);
  if ($row['value']==1){
    return 1;
  }
  return 0;
}

function userDisliked($obj_type,$obj_id,$u_id){ //onj_type is 'link' or 'comment'
  $h = con();
  $r = mysqli_query($h, "SELECT value FROM likes WHERE user='$u_id' AND $obj_type='$obj_id'");
  query_feedback($h);
  $row=mysqli_fetch_assoc($r);
  if ($row['value']==-1){
    return 1;
  }
  return 0;
}

function getScore($obj_type, $obj_id){
  $h = con();
  $r = mysqli_query($h, "SELECT coalesce(SUM(value),0) score FROM likes WHERE $obj_type='$obj_id'");
  query_feedback($h);
  $row=mysqli_fetch_assoc($r);
  return $row['score'];
}

function likeDislikeButton($obj_type, $obj_id, $location){
  $u_id=$_SESSION['u_id'];
  $score=getScore($obj_type, $obj_id);
  if (userLiked($obj_type,$obj_id,$u_id)){
    $up_button_type = "images/up_arrow.png";
  }
  else{
    $up_button_type = "images/up_arrow2.png";
  }
  echo "<a href='like-dislike.php?like=1&obj_id=$obj_id&obj_type=$obj_type&location=$location'><img src='$up_button_type' width='20' height='20' alt='Upvote'></a>";
  echo "&nbsp;&nbsp;";
  echo "$score";
  echo "&nbsp;&nbsp;";
  if (userDisliked($obj_type,$obj_id,$u_id)){
    $down_button_type = "images/down_arrow.png";
  }
  else{
    $down_button_type = "images/down_arrow2.png";
  }
  echo "<a href='like-dislike.php?dislike=1&obj_id=$obj_id&obj_type=$obj_type&location=$location'><img src='$down_button_type' width='20' height='20' alt='Downvote'></a>";
}

function getHotLinks($location){
  $h = con();
  //we use coalesce in order to not have a null result if one of the two results are null and get a zero if both are
  $r = mysqli_query($h, "SELECT links.date link_date, links.text link_text, links.url link_url, links.id link_id, links.author author_id, coalesce(COUNT(likes.value)+COUNT(c.id),COUNT(likes.value),COUNT(c.id),0) total
                          FROM links LEFT JOIN comments c
                          ON links.id = c.link
                          LEFT JOIN likes
                          ON links.id = likes.link
                          WHERE links.date > DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 1 DAY)
                          GROUP BY links.id
                          ORDER BY total DESC
                          ");
  query_feedback($h);
  $row=mysqli_fetch_assoc($r);
  if(!$row){
    echo '<div class="row shadow-sm p-3 my-2 bg-white rounded">';
    echo 'No recent links :/';
    echo '  </div>';
  }
  while($row){
    $text=$row['link_text'];
    $url=$row['link_url'];
    $author_id=$row['author_id'];
    $link_id=$row['link_id'];
    $link_date=$row['link_date'];
    $author=getUsername($author_id);
    echo '<div class="row shadow-sm p-3 my-2 bg-white rounded">';
    echo '  <div class="col-2 text-truncate">';
    echo "    <small class='text-muted'>Posted by <a href=profile.php?$author_id=>$author</a><br>$link_date</small>";
    echo '  </div>';
    echo "  <div class='col-8'>";
    echo "    <div class='row'>";
    echo "      <div class='col-12 text-truncate'>";
    echo "        <a href='link.php?link_id=$link_id'>$text</a>";
    echo "      </div>";
    echo '      <div class="col-12 text-truncate">';
    echo "        <a class='mx-auto' href=$url target='_blank'>$url</a>";
    echo '      </div>';
    echo '     </div>';
    echo "  </div>";
    echo "  <div class='col-2'>";
    likeDislikeButton("link",$link_id,$location);
    echo "  </div>";
    echo '</div>';
    //foreach($row as $key => $value) {
    //  echo "$key is at $value; ";
    //}
    $row=mysqli_fetch_assoc($r);
  }
}

function getLinktext($link_id){
  $h = con();
  $r = mysqli_query($h, "SELECT `text` FROM links WHERE id='$link_id'");
  query_feedback($h);
  $row=mysqli_fetch_assoc($r);
  $text = $row['text'];
  return $text;
}

function getLinkUrl($link_id){
  $h = con();
  $r = mysqli_query($h, "SELECT `url` FROM links WHERE id='$link_id'");
  query_feedback($h);
  $row=mysqli_fetch_assoc($r);
  $url = $row['url'];
  return $url;
}

function getLinkAuthor($link_id){
  $h = con();
  $r = mysqli_query($h, "SELECT author FROM links WHERE id='$link_id'");
  query_feedback($h);
  $row=mysqli_fetch_assoc($r);
  $author = $row['author'];
  return $author;
}

function dispComments($link_id){
  $h = con();
  $r = mysqli_query($h, "SELECT id, `text`, author, `date` FROM comments WHERE link='$link_id' ORDER BY `date` DESC");
  query_feedback($h);
  $row=mysqli_fetch_assoc($r);
  while($row){
    $comm_id=$row['id'];
    $text=$row['text'];
    $author=$row['author'];
    $author_name=getUsername($author);
    $date=$row['date'];
    echo "<hr/>";
    echo "<a name='$comm_id'></a>";
    echo "<div class='row px-3 py-1 class='border-top''>";
    likeDislikeButton("comment", $comm_id, "link.php?link_id=$link_id"); echo "&nbsp;<small class='text-muted'>Posted by <a href='profile.php?id=$author'>$author_name</a>&nbsp;$date</small><br>";
    echo "</div>";
    echo "<div class='row px-5 py-1 class='border-top''>";
    echo "<div col-12>$text</div>";
    echo "</div>";
    $row=mysqli_fetch_assoc($r);
  }
}

function getPostedComments($u_id){
  $h = con();
  $r = mysqli_query($h, "SELECT id, `text`, `date`, link FROM comments WHERE author='$u_id' ORDER BY `date` DESC");
  query_feedback($h);
  $row=mysqli_fetch_assoc($r);
  while($row){
    $comm_id=$row['id'];
    $text=$row['text'];
    $date=$row['date'];
    $link_id=$row['link'];
    echo "<hr/>";
    echo "<div class='row px-3 py-1 class='border-top''>";
    likeDislikeButton("comment", $comm_id, "profile.php?u_id=$u_id"); echo "&nbsp;<small class='text-muted text-truncate'><a href='link.php?link_id=$link_id#$comm_id'>Go to comment</a>&nbsp&nbsp&nbsp $date</small><br>";
    echo "</div>";
    echo "<div class='row px-5 py-1 class='border-top''>";
    echo "<div col-12>$text</div>";
    echo "</div>";
    $row=mysqli_fetch_assoc($r);
  }
}

function getPostedLinks($u_id){
  $h = con();
  $r = mysqli_query($h, "SELECT id, `text`, url, `date` FROM links WHERE author='$u_id' ORDER BY `date` DESC");
  query_feedback($h);
  $row=mysqli_fetch_assoc($r);
  while($row){
    $link_id=$row['id'];
    $text=$row['text'];
    $date=$row['date'];
    $url=$row['url'];
    echo "<hr/>";
    echo "<div class='row px-3 py-1 class='border-top'>";
    likeDislikeButton("link", $link_id, "profile.php?u_id=$u_id"); echo "&nbsp;<small class='text-muted text-truncate'>$date&nbsp&nbsp&nbsp<a href='$url'>$url</a></small>";
    echo "</div>";
    echo "<div class='row px-3 py-1'><div class='col-12 text-truncate'><a href='link.php?link_id=$link_id'>$text</a></div></div>";
    $row=mysqli_fetch_assoc($r);
  }
}

function getLikes($u_id){
  $h = con();
  $r = mysqli_query($h, "SELECT likes.id like_id, likes.comment comm_id, likes.link link_id, likes.value value, likes.`date`, links.url url, links.text link_text, c.text comm_text, c.link c_link_id FROM likes
                                                                            LEFT JOIN comments c ON likes.comment=c.id
                                                                            LEFT JOIN links ON likes.link=links.id WHERE likes.user='$u_id' ORDER BY `date` DESC");
  query_feedback($h);
  $row=mysqli_fetch_assoc($r);
  while($row){
    $date=$row['date'];
    $value=$row['value'];
    if($row['comm_id']){
      $obj_type="comment";
      $obj_id=$row['comm_id'];
      $text=$row['comm_text'];
      $link_id=$row['c_link_id'];
      echo "<hr/>";
      echo "<div class='row px-3 py-1 class='border-top''>";
      likeDislikeButton($obj_type, $obj_id, "profile.php?u_id=$u_id"); echo "&nbsp;<small class='text-muted text-truncate'><a href='link.php?link_id=$link_id#$obj_id'>Go to comment</a>&nbsp&nbsp&nbsp $date</small><br>";
      echo "</div>";
      echo "<div class='row px-5 py-1 class='border-top''>";
      echo "<div col-12>$text</div>";
      echo "</div>";
    }
    else{
      $text=$row['link_text'];
      $obj_type="link";
      $obj_id=$row['link_id'];
      $url=$row['url'];
      $text=$row['link_text'];
      echo "<hr/>";
      echo "<div class='row px-3 py-1 class='border-top'>";
      likeDislikeButton($obj_type, $obj_id, "profile.php?u_id=$u_id"); echo "&nbsp;<small class='text-muted text-truncate'>$date&nbsp&nbsp&nbsp<a href='$url'>$url</a></small>";
      echo "</div>";
      echo "<div class='row px-3 py-1'><div class='col-12 text-truncate'><a href='link.php?link_id=$obj_id'>$text</a></div></div>";
    }
    $row=mysqli_fetch_assoc($r);
  }
}

function updateLastCon($u_id){
  $h = con();
  mysqli_query($h, "UPDATE users SET last_con = CURRENT_TIMESTAMP WHERE id='$u_id'");
  query_feedback($h);
}

function getLastCon($u_id){
  $h = con();
  $r=mysqli_query($h, "SELECT last_con FROM users WHERE id='$u_id'");
  query_feedback($h);
  $row=mysqli_fetch_assoc($r);
  return $row['last_con'];
}


function getLinkUpdates($u_id){
  $h = con();
  $r1 = mysqli_query($h, "SELECT last_con FROM users WHERE id=$u_id");
  $row=mysqli_fetch_assoc($r1);
  $last_con = getLastCon($u_id);
  $r2 = mysqli_query($h,
  "SELECT links.id link_id, links.url link_url, links.text link_text, COUNT(DISTINCT likes.id) n_votes, COUNT(DISTINCT c.id) n_comms, coalesce( COUNT(likes.id)+COUNT(c.id), COUNT(likes.id), COUNT(c.id), 0) total
   FROM links LEFT JOIN comments c ON links.id=c.link
   LEFT JOIN likes ON links.id=likes.link
   WHERE links.author = '$u_id'
   AND (c.date IS NULL OR (c.date > DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 1 DAY)
    AND c.date > '$last_con'))
   AND (likes.date IS NULL OR (likes.date > DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 1 DAY)
    AND likes.date > '$last_con'))
   GROUP BY links.id
   ORDER BY total DESC");
   query_feedback($h);
  $row=mysqli_fetch_assoc($r2);
  while($row){
    $link_id=$row['link_id'];
    $text=$row['link_text'];
    $url=$row['link_url'];
    $n_votes=$row['n_votes'];
    $n_comms=$row['n_comms'];
    echo '<div class="row mt-2">';
    echo "  <div class='alert alert-primary' role='alert'>";
    if($n_comms){
      echo "A link you posted got $n_comms new comment(s)";
      if($n_votes)
        echo ", and $n_votes new vote(s) : ";
      else{
        echo ".";
      }
    }
    elseif($n_votes){
      echo "A link you posted got $n_votes new vote(s) : ";
    }
    echo "<div class='row'>";
    echo "  <div class='col-12 text-truncate'>";
    echo "      <a class='alert-link' href='link.php?link_id=$link_id'>$text</a>";
    echo '  </div>';
    echo "  <div class='col-12 text-truncate'>";
    echo "      <a class='mx-auto alert-link' href=$url target='_blank'>$url</a>";
    echo '    </div>';
    echo '  </div>';
    echo '  </div>';
    echo '  </div>';
    $row=mysqli_fetch_assoc($r2);
  }
  $r3 = mysqli_query($h,
  "SELECT r_links.comm_id c_id, r_links.id link_id, r_links.comm_text c_text,
    r_links.n_likes n_likes, COUNT(DISTINCT c.id) n_comms,
    coalesce(COUNT(c.id)+r_links.n_likes,COUNT(c.id),r_links.n_likes) total
   FROM comments c JOIN
    (SELECT links.id id, c.id comm_id, c.text comm_text, COUNT(DISTINCT likes.id) n_likes FROM links
      JOIN comments c ON links.id=c.link
      LEFT JOIN likes ON likes.comment=c.id
      WHERE links.author='$u_id' AND (likes.date IS NULL OR
        (likes.date IS NOT NULL AND likes.date > DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 1 DAY)
        AND likes.date > '$last_con'))
      GROUP BY c.id) r_links
   ON r_links.id=c.link
   WHERE c.date > DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 1 DAY)
    AND c.date > '$last_con'
   GROUP BY r_links.id
   ORDER BY total DESC");
   query_feedback($h);
  $row=mysqli_fetch_assoc($r3);
  while($row){
    $link_id=$row['link_id'];
    $comm_id=$row['c_id'];
    $comm_text=$row['c_text'];
    $n_likes=$row['n_likes'];
    $n_comm=$row['n_comms'];
    echo '<div class="row mt-2">';
    echo "  <div class='alert alert-primary' role='alert'>";
    if($n_likes){
      echo "A comment you posted got $n_likes new vote(s)<br>";
    }
    if($n_comm){
      echo "There have been $n_comm new comment(s) on a link you commented. ";
    }
    echo "<div class='row'>";
    echo "  <div class='col-12 text-truncate'>";
    echo "    <a class='alert-link' href='link.php?link_id=$link_id#$comm_id'>$comm_text</a>";
    echo '  </div>';
    echo ' </div>';
    echo '</div>';
    echo '</div>';
    $row=mysqli_fetch_assoc($r2);
  }
}
?>
