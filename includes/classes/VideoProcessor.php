<?php
class VideoProcessor{
  private $con;
  private $sizeLimit=500000000;
  private $allowedTypes=array("mp4", "m4a", "m4v", "f4v", "f4a", "m4b", "m4r", "f4b", "mov","3gp", "3gp2", "3g2", "3gpp", "3gpp2","ogg", "oga", "ogv", "ogx","wmv", "wma", "asf","webm","flv");
  private $ffmpeg; 
  private $ffprobePath;
  public function __construct($con){
    $this->con=$con;
    $this->ffmpeg=realpath("ffmpeg/bin/ffmpeg.exe" );
    $this->ffprobePath=realpath("ffmpeg/bin/ffprobe.exe");
  }
  public function upload($videoUploadData){
    $targetDir="uploads/videos/";

    $videoData=$videoUploadData->getVideoDataArray();
    
    $tempFilepath=$targetDir.uniqid().basename($videoData['name']);
    
    $tempFilepath=str_replace(' ','-',$tempFilepath);
    
    $isValidData=$this->processData($videoData,$tempFilepath);
    
    if(!$isValidData){
      return false;
    }
    if(move_uploaded_file($videoData['tmp_name'],$tempFilepath)){
      $finalFilePath=$targetDir.uniqid().'.mp4';
      if(!$this->insertVideoData($finalFilePath,$videoUploadData))
      {
        return false;
      }
      if(!$this->converVideoToMp4($tempFilepath,$finalFilePath)){
        echo "uplod faild";
        return false;
      }
      if(!$this->deleteFile($tempFilepath)){
        echo"uplod faild";
        return false;
      }
      if(!$this->generateThumbnails($finalFilePath)){
        echo"cannot generate thumbnails";
        return false;
      }
      echo "success";
      return true;
    }
  }
  private function processData($videoData,$Filepath)
  {
    $videoType=pathinfo($Filepath,PATHINFO_EXTENSION);
    if (!$this->isValidSize($videoData)) {
      echo "file is too large. canot be more than".$this->sizeLimit."Byte";
      return false;
    }
    elseif(!$this->isValidType($videoType)){
      echo 'invalid type';
      return false;
    }
    elseif($this->hasError($videoData))
    {
      return false;
    }
    return true;
  }

    private function isValidSize($data){
      return $data['size']<=$this->sizeLimit;
    }

    private function isValidType($type)
    {
      $lowercased=strtolower($type);
      return in_array($lowercased,$this->allowedTypes);
    }

    private function hasError($data){
      return $data['error'];
    }

    private function insertVideoData($finalpath,$videodata)
    {
      $query=$this->con->prepare('INSERT INTO videos(title,uploadedBy,description,privacy,category,filePath)values(:title,:uploadBy,:description,:privacy,:category,:filePath)');
      $title=$videodata->getTitle();
      $uploadby=$videodata->uploadBy();
      $desc=$videodata->getDescription();
      $priv=$videodata->getPrivacy();
      $cat=$videodata->getCategory();
      $query->bindParam(':title',$title);
      $query->bindParam(':uploadBy',$uploadby);
      $query->bindParam(':description',$desc);
      $query->bindParam(':privacy',$priv);
      $query->bindParam(':category',$cat);
      $query->bindParam(':filePath',$finalpath);
      return $query->execute();
    }
    private function converVideoToMp4($tempFilepath,$finalFilePath){
      $cmd="$this->ffmpeg -i $tempFilepath $finalFilePath 2>&1";
      $outputlog=array();
       exec($cmd,$outputlog,$returnCode);
       if($returnCode!=0){
         foreach($outputlog as $line)
            echo $line.'<br>';
         return false;
       }
       return true;
     }
     private function deleteFile($filepath){
      if(!unlink($filepath)){
        echo"Couldont Delete File";
        return false;
      }
      return true;
     }
     private function generateThumbnails($filepath){
       $thumbnailsSize="210*118";
       
       $numThumbnails=3;
       
       $pathToThumbnail="uploads/videos/thumbnails";
       
       $duration=$this->getVideoDuration($filepath);
       
       $videoId=$this->con->lastInsertId();
       
       $this->updateDuration($duration,$videoId);

       for($num=1;$num<=$numThumbnails;$num++)
       {
         $imageName=uniqid().".jpg";
         
         $interval=($duration*0.8)/$numThumbnails*$num;

         $fullThumbnailPath="$pathToThumbnail/$videoId-$imageName";

         $cmd="$this->ffmpeg -i $filepath -ss $interval -s $thumbnailsSize -vframes 1 $fullThumbnailPath 2>&1";

         $outputlog=array();
         exec($cmd,$outputlog,$returnCode);

         if($returnCode!=0){
            foreach($outputlog as $line){
              echo $line."<br>";
            }
         }
         $selected=($num==1)?1:0;
         $query=$this->con->prepare("INSERT INTO thumbnails(videoId,filePath,selected)
         values(:videoId,:filePath,:selected)");
         $query->bindParam(":videoId",$videoId);
         $query->bindParam(":filePath",$fullThumbnailPath);
         $query->bindParam(":selected",$selected);
         $sucess=$query->execute();

         if(!$sucess){
           echo"error inserting Thumbnails";
           return false;
         }
       }
       return true;
     }
     private function getVideoDuration($filepath){
       return (int)shell_exec("$this->ffprobePath -v error -show_entries format=duration -of default=noprint_wrappers=1:nokey=1 $filepath
       ");
     }
     private function updateDuration($duration,$videoId){
       $hours=floor($duration/3600);
       $mins=floor(($duration-($hours*3600))/60);
       $secs=floor($duration%60);
       $hours=($hours<1)?"":$hours.":";
       $mins=($mins<10)?"0".$mins.":":$mins.":";
       $secs=($secs<10)?"0".$secs:$secs;

       $duration=$hours.$mins.$secs;

       $query=$this->con->prepare("UPDATE videos set duration=:duration WHERE id=:videoId");

       $query->bindParam(":duration",$duration);
       $query->bindParam(":videoId",$videoId);
       $query->execute();


     }

    
    
  }
?>