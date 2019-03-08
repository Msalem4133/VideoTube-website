<?php
class ButtonProvider{

  public static $signInFunction="notSignedIn()";

  public static function creatLink($link){
    return User::isLoggedIn()?$link :ButtonProvider::$signInFunction;
  }
  public static function creatButton($text,$imageSrc,$action,$class){
    $image=($imageSrc==null)?"":"<img src='$imageSrc'>";
    $action= ButtonProvider::creatLink($action);
      return "<button class='$class' onclick='$action'>
                $image
                <span class='text'>
                  $text
                </span>
              </button>";
  }

  public static function creatHyperLinkButton($text,$imageSrc,$link,$class){
    $image=($imageSrc==null)?"":"<img src='$imageSrc'>";

      return "<a href='$link'>
                <button class='$class'>
                  $image
                  <span class='text'>
                    $text
                  </span>
                </button>
              </a>";
  }

  public static function creatUserProfileButton($con,$username){
    $userObj= new User($con,$username);
    $profilePic=$userObj->getProfilePic();
    $link="profile.php?username=$username";
    return "<a href='$link'>
              <img src='$profilePic'class='profilePic'>
            </a>";
  }

  public static function creatEditVideoButton($videoId){
    $link="editVideo.php?videoId=$videoId";
    
    $button=ButtonProvider::creatHyperLinkButton("EDIT VIDEO","",$link,"edit button");

    return "<div class='editVideoButttonContainer'>
              $button
            </div>";

  }

  public static function creatSubscriberButton($con,$userToObj,$userLoggedInObj)
  {
    $userTo=$userToObj->getUsername();
    $userLoggedIn=$userLoggedInObj->getUsername();

    $isSubscribedTo=$userLoggedInObj->isSubscribed($userTo);

    $buttonText=$isSubscribedTo?"SUBSCRIBED":"SUBSCRIBE";

    $buttonText .=" ".$userToObj->getSubscriberCount();

    $buttonClass=$isSubscribedTo?"unsubscribe button":"subscribe button";

    $action="subscribe(\"$userTo\",\"$userLoggedIn\",this)";

    $button=ButtonProvider::creatButton($buttonText,null,$action,$buttonClass);

    return "<div class='subscribeButtonContainer'>
              $button
            </div>";

  }
}
?>