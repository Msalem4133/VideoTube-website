<?php
require_once('../includes/config.php');
require_once('../includes/classes/User.php');
require_once('../includes/classes/ButtonProvider.php');
require_once('../includes/classes/Comment.php');

if(isset($_POST['commentText'])&&isset($_POST['postedBy'])&&isset($_POST['videoId'])&&isset($_POST['responseTo'])){

  $userLoggedInObj=new User($con,$_SESSION['userLoggedIn']);

  $query=$con->prepare("INSERT INTO comments(postedBy,videoId,responseTo,body)VALUES(:postedBy,:videoId,:responseTo,:body)");

  $query->bindParam(':postedBy',$_POST['postedBy']);
  $query->bindParam(':videoId',$_POST['videoId']);
  $query->bindParam(':responseTo',$_POST['responseTo']);
  $query->bindParam(':body',$_POST['commentText']);

  $postBy=$_POST['postedBy'];
  $videoId=$_POST['videoId'];
  $responseTo=isset($_POST['responseTo'])?$_POST['responseTo']:0;
  $commentText=$_POST['commentText'];
  $query->execute();

  

  $newComment=new Comment($con,$con->lastInsertId(),$userLoggedInObj,$videoId);
  echo $newComment->creat();

}

else{
  echo "One or more parameter are not passed into subscribe.php the file";
  }

?>