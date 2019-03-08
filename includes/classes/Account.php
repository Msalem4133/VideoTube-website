<?php
class Account{
  private $con;
  private $errorArray=array();
  public function __construct($con){
    $this->con=$con;
  }
   /*************for login or sign in *********/
   public function login($un,$pw){
     $pw=hash("sha512",$pw);
     $query=$this->con->prepare("SELECT * from users WHERE username=:un and password=:pw");
     $query->bindParam(":un",$un);
     $query->bindParam(":pw",$pw);
     $query->execute();
     if($query->rowCount()==1){
       return true;
     }
     else
     {
       array_push($this->errorArray,Constants::$loginFailed);
       return false;
     }
   }

  /************for Sign Up */
  public function register($fn,$ln,$un,$em,$em2,$pw,$pw2){
    $this->validateFirstName($fn);
    $this->validateLastName($ln);
    $this->validateUsername($un);
    $this->validateEmail($em,$em2);
    $this->validatePassword($pw,$pw2);

    if(empty($this->errorArray)){
      return $this->insertUserDetails($fn,$ln,$un,$em,$pw);
    }
    {
      return false;
    }
  }
  private function insertUserDetails($fn,$ln,$un,$em,$pw)
  {
    $pw =hash("sha512",$pw);
    $profilePic="assets/images/profilePictures/default.png";
    $query=$this->con->prepare("INSERT INTO users(firstName,lastName,username,email,password,profilePic)
    values(:fn,:ln,:un,:em,:pw,:pic)");
    $query->bindParam(":fn",$fn);
    $query->bindParam(":ln",$ln);
    $query->bindParam(":un",$un);
    $query->bindParam(":em",$em);
    $query->bindParam(":pw",$pw);
    $query->bindParam(":pic",$profilePic);
    return $query->execute();
  }
  private function validateFirstName($fn)
  {
    $lengthFirstName=strlen($fn);
    if($lengthFirstName>25 || $lengthFirstName<2)
    {
      array_push($this->errorArray,Constants::$firstNameCharacters);
    }
  }

  private function validateLastName($ln)
  {
    $lengthFirstName=strlen($ln);
    if($lengthFirstName>25 || $lengthFirstName<2)
    {
      array_push($this->errorArray,Constants::$lastNameCharacters);
    }
  }

  private function validateUsername($un)
  {
    $lengthFirstName=strlen($un);
    if($lengthFirstName>25 || $lengthFirstName<5)
    {
      array_push($this->errorArray,Constants::$usernameCharacters);
      return;
    }
    $query=$this->con->prepare("SELECT * FROM users where username=:un");
    $query->bindParam(":un",$un);
    $query->execute();
    if($query->rowCount()!=0)
    {
      array_push($this->errorArray,Constants::$usernameTaken);
      return;
    }
  }

  private function validateEmail($em,$em2)
  {
    if($em!=$em2)
    {
      array_push($this->errorArray,Constants::$emailDonotMatch);
      return;
    }
    if(!filter_var($em,FILTER_VALIDATE_EMAIL))
    {
      array_push($this->errorArray,Constants::$emailInvalid);
      return;
    }

    $query=$this->con->prepare("SELECT * FROM users where email=:em");
    $query->bindParam(":em",$em);
    $query->execute();
    if($query->rowCount()!=0)
    {
      array_push($this->errorArray,Constants::$emailTaken);
      return;
    }
  }

  private function validatePassword($pw,$pw2)
  {
    if($pw!=$pw2)
    {
      array_push($this->errorArray,Constants::$passwordDonotMatch);
      return;
    }

    if(preg_match("/[^A-Za-z0-9]/",$pw)){
      array_push($this->errorArray,Constants::$passwordNotAlphanumeric);
      return;
    }

    if(strlen($pw)>30||strlen($pw)<5){
      array_push($this->errorArray,Constants::$passwordLength);
      return;
    }
  }

  public function getError($error)
  {
    if(in_array($error,$this->errorArray))
    {
      return "<span class='errorMessage'>$error</span>";
    }
  }



}