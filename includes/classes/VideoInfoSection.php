<?php
require_once("includes/classes/VideoInfoControls.php");
class VideoInfoSection{
  private $con,$video,$userLoggedInObj;
  public function __construct($con,$video,$userLoggedInObj){
    $this->con=$con;
    $this->video=$video;
    $this->userLoggedInObj=$userLoggedInObj;
  }
  public function create(){
    return $this->creatPrimaryInfo() .$this->creatSecondaryInfo();
  }
  private function creatPrimaryInfo(){
    $title=$this->video->getTitle();
    $views=$this->video->getViews();

    $videoInfoControls=new VideoInfoControls($this->video,$this->userLoggedInObj);
    $controls=$videoInfoControls->create();
    return "<div class='videoInfo'>
                <h1>$title</h1>
                <div class='bottonSection'>
                  <span class='viewCount'>$views views</span>
                  $controls
                </div>
            </div>  ";

  }

  private function creatSecondaryInfo(){
    $description=$this->video->getDescripition();
    $uploadDate=$this->video->getUploadDate();
    $uploadedBy=$this->video->getUploadBy();
    $profileButton=ButtonProvider::creatUserProfileButton($this->con,$uploadedBy);

    if($uploadedBy==$this->userLoggedInObj->getUsername())
    {
      $actionButton=ButtonProvider::creatEditVideoButton($this->video->getId());
    }
    else
    {
      $userToObj=new User($this->con,$uploadedBy);
      $actionButton=ButtonProvider::creatSubscriberButton($this->con,$userToObj,$this->userLoggedInObj);
    }
    //$buttonSection=$this->buttonSection();
    return "<div class='secoundryInfo'>
              <div class='topRow'>
                $profileButton
                <div class='uploadInfo'>
                  <span class='owner'>
                    <a href ='profile.php?username=$uploadedBy'>
                      $uploadedBy
                    </a>
                  </span>
                  <span class='date'>
                    Published on $uploadDate
                  </span>
                </div>
                $actionButton
              </div>
              <div class='descriptionContainer'>
                $description
              </div>
            </div>";
  }
  private function topSection(){
    return "<div class='topSection'>
              <div class='userImage'>
                <img src='assets/images/profilePictures/default-female.png'>
              </div>
              <div class=''user-videoInfo>
                <h2>mido</h2>
                <h5>Published on oct 9,2017</h5>
              </div>
              <div>
                <button>SUBSCRIBE</button>
              </div>
              <br>
            </div>";
  }


}
?>