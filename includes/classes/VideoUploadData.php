<?php 
  class VideoUploadData{
    private $VideoDataArray,$titleinput,$descriptionInput,$categoryInput,$privacyInput,$uploadBy;
    public function  __construct($VideoDataArray,$titleinput,$descriptionInput,$categoryInput,$privacyInput,$uploadBy){
      $this->VideoDataArray=$VideoDataArray;
      $this->titleinput=$titleinput;
      $this->descriptionInput=$descriptionInput;
      $this->categoryInput=$categoryInput;
      $this->privacyInput=$privacyInput;
      $this->uploadBy=$uploadBy;
    }
    public function getVideoDataArray(){
      return $this->VideoDataArray;
    }
    public function getTitle(){
      return $this->titleinput;
    }
    public function getDescription(){
      return $this->descriptionInput;
    }
    public function getCategory(){
      return $this->categoryInput;
    }
    public function getPrivacy(){
      return $this->privacyInput;
    }
    public function uploadBy(){
      return $this->uploadBy;
    }

  }
?>