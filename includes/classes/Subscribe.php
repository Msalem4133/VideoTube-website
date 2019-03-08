<?php
class Subscribe{
  private $con;
  private $userTo;
  private $userFrom;

  public function __construct($con,$userTo,$userFrom){
    $this->con=$con;
    $this->userTo=$userTo;
    $this->userFrom=$userFrom;
  }

  public function checkIfAlreadySubscribe(){
    $query=$this->con->prepare('SELECT * FROM subscribers where userTo=:userTo AND userFrom=:userFrom');

    $query->bindParam(':userTo',$this->userTo);
    $query->bindParam(':userFrom',$this->userFrom);

    $query->execute();

    return $query->rowCount()>0;
  }

  public function deleteSubscribe(){
    $query=$this->con->prepare("DELETE FROM subscribers WHERE userTo=:userTo AND userFrom=:userFrom");

    $query->bindParam(':userTo',$this->userTo);
    $query->bindParam(':userFrom',$this->userFrom);

    $query->execute();

    return $query->rowCount();
  }

  public function addSubscribe(){
    $query=$this->con->prepare("INSERT INTO subscribers(userTo,userFrom)VALUES(:userTo,:userFrom)");

    $query->bindParam(':userTo',$this->userTo);
    $query->bindParam(':userFrom',$this->userFrom);

    $query->execute();

    return $query->rowCount();

  }
  public function countSubscribers(){
    $query=$this->con->prepare('SELECT * FROM subscribers where userTo=:userTo');

    $query->bindParam(':userTo',$this->userTo);

    $query->execute();

    return $query->rowCount();
  }
  
}

?>

