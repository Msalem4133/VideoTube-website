<?php
class VideoPlayer{

  private $video;
  public function __construct($video){
    
    $this->video=$video;
  }
  public function create($autoPlay){
    if(!$autoPlay){
      $autoPlay="autoplay";
    }
    else{
      $autoPlay="";
    }
    $filePath=$this->video->getFilePath();
    return "<video class='videoPlayer' controls $autoPlay>
    <source src='$filePath'type='video/mp4'>
    your brwoser not support the video type
    </video>";
  }

}