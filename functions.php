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
  $r = mysqli_query($h, "SELECT SUM(value) score FROM likes WHERE $obj_type='$obj_id'");
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
  $r = mysqli_query($h, "SELECT links.text link_text, links.url link_url, links.id link_id, links.author author_id, coalesce(COUNT(likes.value)+COUNT(c.id),COUNT(likes.value),COUNT(c.id),0) total
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
  while($row){
    $text=$row['link_text'];
    $url=$row['link_url'];
    $author_id=$row['author_id'];
    $link_id=$row['link_id'];
    $author=getUsername($author_id);
    echo '<div class="row shadow-sm p-3 my-2 bg-white rounded">';
    echo '  <div class="col text-truncate">';
    echo "    (Posted by <a href=profile.php?$author_id=>$author</a>)";
    echo '  </div>';
    echo '  <div class="col text-truncate">';
    echo "    <a href=$url target='_blank'>$url</a>";
    echo '  </div>';
    echo "  <div class='col text-truncate'>";
    echo "    <a href='link.php?link_id=$link_id'>$text</a>";
    echo "  </div>";
    echo "  <div class='col'>";
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
?>
