<?php
include('functions.php');
include('header.php');
 include("navbar.php");?>
 <br><br><br><br><br>
   Test page <br>

<?php
//addComment("This is also a comment", 1, 9);
//editLink(2, "Me likey very much", "I am an url");
//deleteLink(2);
//deleteLike(25);
vote(1,1,1,'link');
vote(1,2,1,'link');
vote(-1,1,2,'link');
vote(-1,2,2,'link');
//deleteComment(5);
//deleteLink(1);
//getHotLinks();
include('footer.php');?>
