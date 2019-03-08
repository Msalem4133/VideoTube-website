<?php
require_once('ButtonProvider.php');
require_once('CommentControl.php');
class Comment{
  private $con,
  $sqlData,
  $userLoggedInObj,
  $videoId;

  public function __construct(
    $con,
    $input,
    $userLoggedInObj,
    $videoId){

    if(!is_array($input)){
      $query=$con->prepare("SELECT * FROM comments WHERE id=:id");
      
      $query->bindParam(":id",$input);
      $query->execute();
      $input=$query->fetch(PDO::FETCH_ASSOC);
    }
    $this->sqlData=$input;
    $this->con=$con;
    $this->userLoggedInObj=$userLoggedInObj;
    $this->videoId=$videoId;

  }
  public function creat(){
    $id=$this->sqlData['id'];
    $body=$this->sqlData['body'];
    $videoId=$this->getVideoId();
    $postedBy=$this->sqlData['postedBy'];
    $profileButton=ButtonProvider::creatUserProfileButton($this->con,$postedBy);
    $timeSpan=$this->time_elapsed_string($this->sqlData['date']);
    $commentControlObj=new CommentControl($this->con,$this,$this->userLoggedInObj);
    $commentControl=$commentControlObj->create();

    $numsResponses= $this->getNumberOfReplies();
    $viewRepliesText="";
    if($numsResponses>0)
    {
      $viewRepliesText="<span class='repliesSection viewReplies'onclick='getReplies($id,this,$videoId)'>
        view all $numsResponses replies
      </span>";
    }
    else{
      $viewRepliesText="<div class='repliesSection'></div>";
    }
    return "<div class='itemContainer'>
              <div class='comment'>
                $profileButton
                <div class='mainContainer'>
                  <div class='commentHeader'>
                    <a href='profile.php?username=$postedBy'>
                      <span class='username'>$postedBy</span>
                    </a>
                    <span class='timestamp'>$timeSpan</span>
                  </div>
                  <div class='body'>
                    $body
                  </div>
                </div>
              </div>
              $commentControl
              $viewRepliesText
            </div>";

  }
  public function getNumberOfReplies(){
    
    $query=$this->con->prepare("SELECT count(*) from comments where responseTo=:responseTo");
    
    $query->bindParam(":responseTo",$id);
    
    $id=$this->sqlData['id'];
    
    $query->execute();
    
    return $query->fetchColumn();

  }
  public function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}
  public function getId(){
    return $this->sqlData["id"];
  }
  public function getVideoId(){
    return $this->videoId;
  }
  public function getLikes(){
    $query=$this->con->prepare("SELECT COUNT(*)AS 'count'FROM likes WHERE commentId=:commentId");
    $query->bindParam(":commentId",$commentId);
    $commentId=$this->getId();
    $query->execute();

    $data=$query->fetch(PDO::FETCH_ASSOC);

    $numLikes=$data['count'];

    $query=$this->con->prepare("SELECT COUNT(*)AS 'count'FROM dislike WHERE commentId=:commentId");
    $query->bindParam(":commentId",$commentId);
    $query->execute();

    $data=$query->fetch(PDO::FETCH_ASSOC);

    $numsDislikes=$data['count'];
    return $numLikes-$numsDislikes;
  }

    /************two function to detect already liked or disliked to make it active*************** */
    public function wasLikedBy(){
      $id=$this->getId();
      $username=$this->userLoggedInObj->getUsername();
      //check if user is already liked the video
      $query=$this->con->prepare("SELECT *FROM likes WHERE username=:username AND commentId=:commentId");
  
      $query->bindParam('username',$username);
  
      $query->bindParam(':commentId',$id);
  
      $query->execute();
  
      return $query->rowCount();
    }
  
    public function wasDislikedBy(){
      $id=$this->getId();
      $username=$this->userLoggedInObj->getUsername();
      //check if user is already liked the video
      $query=$this->con->prepare("SELECT *FROM dislike WHERE username=:username AND commentId=:commentId");
  
      $query->bindParam('username',$username);
  
      $query->bindParam(':commentId',$id);
  
      $query->execute();
  
      return $query->rowCount();
    }
    public function likeComment(){
      
        //check if user already liked
      if($this->wasLikedBy()){

        //removeLikes from data base
        $this->deleteLikeComment();
        //remove like class by javascript and recount 
        $result=array(
          "likes"=>-1,
        );
        return  json_encode($result);
      }
      else{
        //delete dislike if exisit
        $count=$this->deleteDislike();
        //add like to database
          $this->insertLikeComment();
        //add class active by java script and recount
        $result=array(
          "likes"=>1+$count,
        );
        return  json_encode($result);

      }
    }

    public function dislikeComment(){
      if($this->wasDislikedBy()){
        
        $this->deleteDislike();
        
        $result=array(
          "likes"=>1,
        );
        return  json_encode($result);
      }
      else{

        $count=$this->deleteLikeComment();
        $this->insertDislikeComment();

        $result=array(
          "likes"=>-1-$count,
        );
        return  json_encode($result);

      }
    }
    private function deleteDislike(){
      $commentId=$this->getId();
      $username=$this->userLoggedInObj->getUsername();
      $query=$this->con->prepare("DELETE FROM dislike where
      username=:username AND commentId=:commentId");
      $query->bindParam(":username",$username);
      $query->bindParam(":commentId",$commentId);
      $query->execute();
      return $query->rowCount();

    }
    private function insertDislikeComment(){
      $videoId=$this->getVideoId();
      $id=$this->getId();
      $username=$this->userLoggedInObj->getUsername();
      $query=$this->con->prepare("INSERT into dislike(username,commentId)VALUES
      (:username,:commentId)");
      
      $query->bindParam(":username",$username);
      $query->bindParam(":commentId",$id);
      $query->execute();

    }
    private function insertLikeComment(){
      $videoId=$this->getVideoId();
      $id=$this->getId();
      $username=$this->userLoggedInObj->getUsername();
      $query=$this->con->prepare("INSERT into likes(username,commentId)VALUES
      (:username,:commentId)");
      
      $query->bindParam(":username",$username);
      $query->bindParam(":commentId",$id);
      $query->execute();

    }

    private function deleteLikeComment(){
      
      $commentId=$this->getId();
      $username=$this->userLoggedInObj->getUsername();

      $query=$this->con->prepare("DELETE FROM likes where username=:username AND commentId=:commentId");

      $query->bindParam(":username",$username);
      $query->bindParam(":commentId",$commentId);

      $query->execute();
      return $query->rowCount();
    }
    public function getReplies(){
      $query=$this->con->prepare("SELECT * FROM comments where responseTo=:commentId ORDER BY date asc");
      $query->bindParam(':commentId',$id);
      $id=$this->getId();
      $query->execute();
      $comments="";
      $videoId=$this->getVideoId();
      while($row=$query->fetch(PDO::FETCH_ASSOC)){
        $comment=new Comment($this->con,$row,$this->userLoggedInObj,$videoId);
        $comments.=$comment->creat();
      }
      return $comments;
    }

}
?>