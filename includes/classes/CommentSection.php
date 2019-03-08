<?php
class CommentSection{

private $con;
private $video;
private $userLoggedInObj;

public function __construct($con,$video,$userLoggedInObj){
  $this->con=$con;
  $this->video=$video;
  $this->userLoggedInObj=$userLoggedInObj;
}
public function creat(){
return $this->creatCommentSection();
}

private function creatCommentSection(){
  $numComments=$this->video->getNumberOfComments();
  $postedBy=$this->userLoggedInObj->getUsername();
  $videoId=$this->video->getId();

  $profileButton=ButtonProvider::creatUserProfileButton($this->con,$postedBy);

  $commentAction="postComment(this,\"$postedBy\",$videoId,null,\"comments\")";
  $commentButton=ButtonProvider::creatButton("COMMENT",NULL,$commentAction,"postComment");

  $comments=$this->video->getComments();
  $commentItem="";
  foreach($comments as $comment){
    $commentItem.=$comment->creat();
  }

  return "<div class='commentSection'>
            <div class='header'>
              <span class='commentCount'>$numComments Comments</span>

              <div class='commentForm'>
                $profileButton
                <textarea class='commentBodyClass' placeholder='Add a Public Comment'></textarea>
                $commentButton
              </div>
            </div>
            <div class='comments'>
              $commentItem
            </div>

          </div>";
}
}
?>