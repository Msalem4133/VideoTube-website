<?php
require_once('ButtonProvider.php');
class CommentControl{

  private $con,$comment,$userLoggedInObj;
  public function __construct($con,$comment,$userLoggedInObj){
    $this->con=$con;
    $this->comment=$comment;
    $this->userLoggedInObj=$userLoggedInObj;
  }
  public function create(){
    $replayButton=$this->creatReplayButton();
    $likeCount=$this->creatLikesCount();
    $likeButton=$this->createLikedButton();
    $disLikeButton=$this->createDisLikedButton();
    $replaySection=$this->creatReplaySection();

    return "<div class='controls'>
              $replayButton
              $likeCount
              $likeButton
              $disLikeButton
            </div>
            $replaySection" ;
  }
  private function creatReplayButton(){
    $text="Replay";
    $action="toggleReplay(this)";

    return ButtonProvider::creatButton($text,null,$action,null);

  }
  private function creatLikesCount(){
    $text=$this->comment->getLikes();
    if($text==0)$text="";
    return "<span class='likedCount'>$text</span>";
  }
  private function creatReplaySection(){
   
    $postedBy=$this->userLoggedInObj->getUsername();
    $videoId=$this->comment->getVideoId();
    $commentId=$this->comment->getId();

    $profileButton=ButtonProvider::creatUserProfileButton($this->con,$postedBy);
    
    $cancelButtonAction="toggleReplay(this)";
    $cancelButton=ButtonProvider::creatButton("Cancel",NULL,$cancelButtonAction,"cancelComment");

    $postButtonAction="postComment(this,\"$postedBy\",$videoId,$commentId,\"repliesSection\")";
    $postButton=ButtonProvider::creatButton("Replay",NULL,$postButtonAction,"postComment");
  
    return "<div class='commentForm hidden'>
              $profileButton
              <textarea class='commentBodyClass' placeholder='Add a Public Comment'></textarea>
              $cancelButton
              $postButton
            </div>";
  }
  private function createLikedButton(){
    $commentId=$this->comment->getId();
    $videoId=$this->comment->getVideoId();
    $action="likeComment($commentId,this,$videoId)";
    $class="likeButton";

    $imageSrc="assets/images/icons/thumb-up.png";

    if($this->comment->wasLikedBy()>0)$imageSrc="assets/images/icons/thumb-up-active.png";
    return ButtonProvider::creatButton("",$imageSrc,$action,$class);
  }

  private function createDisLikedButton(){
    $commentId=$this->comment->getId();
    $videoId=$this->comment->getVideoId();
    $action="dislikeComment($commentId,this,$videoId)";
    $class="dislikeButton";
    $imageSrc="assets/images/icons/thumb-down.png";

    if($this->comment->wasDislikedBy()>0)$imageSrc="assets/images/icons/thumb-down-active.png";
    return ButtonProvider::creatButton("",$imageSrc,$action,$class);
  }

}
?>