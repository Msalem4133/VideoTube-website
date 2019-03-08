<?php
require_once("includes/classes/ButtonProvider.php");

class VideoInfoControls{
  private $video,$userLoggedInObj;
  public function __construct($video,$userLoggedInObj){
    $this->video=$video;
    $this->userLoggedInObj=$userLoggedInObj;
  }
  public function create(){
    $likeButton=$this->createLikedButton();
    $disLikeButton=$this->createDisLikedButton();
    return "<div class='controls'>
          $likeButton
          $disLikeButton
            </div>" ;
  }
  private function createLikedButton(){
    $text=$this->video->getLikes();
    $videoId=$this->video->getId();
    $action="likeVideo(this,$videoId)";
    $class="likeButton";

    $imageSrc="assets/images/icons/thumb-up.png";

    if($this->video->wasLiked()>0)$imageSrc="assets/images/icons/thumb-up-active.png";
    return ButtonProvider::creatButton($text,$imageSrc,$action,$class);
  }

  private function createDisLikedButton(){
    $text=$this->video->getDislikes();
    $videoId=$this->video->getId();
    $action="dislikeVideo(this,$videoId)";
    $class="dislikeButton";
    $imageSrc="assets/images/icons/thumb-down.png";

    if($this->video->wasdisliked()>0)$imageSrc="assets/images/icons/thumb-down-active.png";
    return ButtonProvider::creatButton($text,$imageSrc,$action,$class);
  }
}
?>