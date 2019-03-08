<?php 
require_once("includes/header.php");
require_once("includes/classes/VideoPlayer.php");
require_once("includes/classes/VideoInfoSection.php");

require_once("includes/classes/Comment.php");

require_once("includes/classes/CommentSection.php");


if(!isset($_GET['id'])){
  echo "try again";
  exit();
}
$video=new Video($con,$_GET['id'],$userLoggedInObj);
$video->getIncrementViews();
?>
<script src="assets/js/videoPlayerAction.js"></script>

<script src="assets/js/commentAaction.js"></script>

<div class="watchLeftColumn">
<?php

$videoPlayer=new VideoPlayer($video);
echo $videoPlayer->create(true);

$videoInfoSection= new VideoInfoSection($con,$video,$userLoggedInObj);
echo $videoInfoSection->create();

$commentsection=new CommentSection($con,$video,$userLoggedInObj);
echo $commentsection->creat();
?>
</div>
<div class="suggestion">
<?php
$videoGrid=new VideoGrid($con,$userLoggedInObj);
echo $videoGrid->create(null,null,false);
?>
</div>
 <?php require_once("includes/footer.php") ; ?>