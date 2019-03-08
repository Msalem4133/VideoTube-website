<?php
class Video{
  private $con,$sqlData,$userLoggedInObj;
  public function __construct($con,$input,$userLoggedInObj){
    $this->con=$con;
    $this->userLoggedInObj=$userLoggedInObj;

    if(is_array($input)){
      $this->sqlData=$input;
    }
    else{
      $query=$this->con->prepare("SELECT * FROM videos where id= :id");
      
      $query->bindParam(":id",$input);
  
      $query->execute();
  
      $this->sqlData=$query->fetch(PDO::FETCH_ASSOC);
    }


  }
  public function getId()
  {
    return $this->sqlData['id'];
  }

  public function getUploadBy(){
    return $this->sqlData["uploadedBy"];
  }

  public function getTitle(){
    return $this->sqlData["title"];
  }

  public function getDescripition(){
    return $this->sqlData["description"];
  }

  public function getPrivacy(){
    return $this->sqlData["privacy"];
  }

  public function getFilePath(){
    return $this->sqlData['filePath'];
  }
  public function getCategory(){
    return $this->sqlData["category"];
  }
  public function getUploadDate(){
    $date= $this->sqlData["uploadDate"];
    return date("M j, Y",strtotime($date));
  }

  public function getTimeStamp(){
    $date= $this->sqlData["uploadDate"];
    return date("M jS, Y",strtotime($date));
  }

  public function getViews(){
    return $this->sqlData["views"];
  }
  public function getDuration(){
    return $this->sqlData["duration"];
  }
  public function getIncrementViews(){
    
    $query=$this->con->prepare("UPDATE videos set views=views+1 where id=:id");
    $query->bindParam(":id",$videoId);
    $videoId=$this->getId();
    $query->execute();

    $this->sqlData['views']=$this->sqlData['views']+1;

  }
  public function getLikes(){
    $query=$this->con->prepare("select count(*) as 'count'from likes where videoId=:videoId");
    $query->bindParam(":videoId",$videoId);
    $videoId=$this->getId();
    $query->execute();
    $data=$query->fetch(PDO::FETCH_ASSOC);
    return $data['count'];
  }

  public function getDislikes(){
    $query=$this->con->prepare("SELECT count(*) as 'count' from dislike where videoId=:videoId");
    $query->bindParam(":videoId",$id);
    $id=$this->getId();
    $query->execute();
    $data=$query->fetch(PDO::FETCH_ASSOC);
    return $data['count'];
  }

  public function like(){
    $id=$this->getId();
    $username=$this->userLoggedInObj->getUsername();

    //check if user is already liked the video

    if($this->wasLiked()>0)
    {
      $query=$this->con->prepare("DELETE FROM likes where username=:username AND videoId=:videoId");
      $query->bindParam(":username",$username);
      $query->bindParam(":videoId",$id);

      $query->execute();

      $result=array(
        "likes"=>-1,
        "dislikes"=>0
      );
      return  json_encode($result);
    }
    else{
      /***Remove from dislike if it exist to avoid dislike and like for same video***/
      $query=$this->con->prepare("DELETE FROM dislike where username=:username AND videoId=:videoId");
      $query->bindParam(":username",$username);
      $query->bindParam(":videoId",$id);
      $query->execute();
      $count=$query->rowCount();
      /*********************add like to this video******/
      $query=$this->con->prepare("INSERT INTO likes(username,videoId)VALUES(:username,:videoId)     ");
      $query->bindParam(":username",$username);
      $query->bindParam(":videoId",$id);

      $query->execute();

      $result=array(
        "likes"=>1,
        "dislikes"=>0-$count
      );
      return  json_encode($result);
    }
  }

  public function dislike(){
    $id=$this->getId();
    $username=$this->userLoggedInObj->getUsername();
    if ($this->wasdisliked()>0) {
      $query=$this->con->prepare("DELETE FROM dislike where username=:username AND videoId=:videoId");
      $query->bindParam(":username",$username);
      $query->bindParam(":videoId",$id);
      
      $query->execute();

      $result=array(
        "likes"=>0,
        "dislikes"=>-1
      );
      return  json_encode($result);
    }
    else{
      /************remove like if exist****/
      $query=$this->con->prepare("DELETE FROM likes where username=:username AND videoId=:videoId");
      $query->bindParam(":username",$username);
      $query->bindParam(":videoId",$id);
      $query->execute();
      $count=$query->rowCount();
      
      /**************add to dislike table***** */
      $query=$this->con->prepare("INSERT INTO dislike(username,videoId)VALUES(:username,:videoId)");

      $query->bindParam(':username',$username);
      $query->bindParam(':videoId',$id);

      $query->execute();

      $result=array(
        "likes"=>0-$count,
        "dislikes"=>1
      );
      return  json_encode($result);
    }
  } 
  /************two function to detect already liked or disliked to make it active*************** */
  public function wasLiked(){
    $id=$this->getId();
    $username=$this->userLoggedInObj->getUsername();
    //check if user is already liked the video
    $query=$this->con->prepare("SELECT *FROM likes WHERE username=:username AND videoId=:videoId");

    $query->bindParam('username',$username);

    $query->bindParam(':videoId',$id);

    $query->execute();

    return $query->rowCount();
  }

  public function wasdisliked(){
    $id=$this->getId();
    $username=$this->userLoggedInObj->getUsername();
    //check if user is already liked the video
    $query=$this->con->prepare("SELECT *FROM dislike WHERE username=:username AND videoId=:videoId");

    $query->bindParam('username',$username);

    $query->bindParam(':videoId',$id);

    $query->execute();

    return $query->rowCount();
  }
  public function getNumberOfComments(){
    $query=$this->con->prepare("SELECT * FROM comments where videoId=:videoId");
    $query->bindParam(':videoId',$id);

    $id=$this->getId();
    $query->execute();
    return $query->rowCount();
  }
  public function getComments(){
    $query=$this->con->prepare("SELECT * FROM comments where videoId=:videoId and responseTo=0 ORDER BY date DESC");
    $query->bindParam(':videoId',$id);
    $id=$this->getId();
    $query->execute();
    $comments=array();

    while($row=$query->fetch(PDO::FETCH_ASSOC)){
      $comment=new Comment($this->con,$row,$this->userLoggedInObj,$id);
      array_push($comments,$comment);
    }
    return $comments;
  }

  public function getThumbnails(){
    $query=$this->con->prepare("SELECT filePath from thumbnails where videoId=:videoId AND selected=1");
    $query->bindParam(":videoId",$videoId);
    $videoId=$this->getId();
    $query->execute();

    return $query->fetchColumn();

  }
}
?>