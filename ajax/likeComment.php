<?php
require_once('../includes/config.php');
require_once('../includes/classes/Comment.php');
require_once('../includes/classes/User.php');
if(isset($_POST['commentId'])&&isset($_POST['videoId'])){

  $username=$_SESSION['userLoggedIn'];
  $videoId=$_POST['videoId'];
  $commentId=$_POST['commentId'];
  $userLoggedInObj=new User($con,$username);
  $comment=new Comment($con,$commentId,$userLoggedInObj,$videoId);

  echo $comment->likeComment();


}
?>