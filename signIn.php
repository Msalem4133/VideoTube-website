<?php 
require_once('includes/config.php'); 
require_once('includes/classes/FormSanitizer.php');
require_once('includes/classes/Account.php');
require_once('includes/classes/Constants.php');

$account=new Account($con);



if(isset($_POST['submitButton'])){
  $username=FormSanitizer::sanitizeFormUsername($_POST['username']);

 $password=FormSanitizer::sanitizeFormPassword($_POST['password']);
  
  $wasSuccessful=$account->login($username,$password);

  if($wasSuccessful){
    $_SESSION['userLoggedIn']=$username;
    header("Location:index.php");
  }
}

function getInputValue($name){
  if(isset($_POST[$name])){
    echo $_POST[$name];
  }

}
?>
<!DOCTYPE html>
<html>
<head>
  <title>videotube</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
  <link rel="stylesheet" type="text/css" 
  href="assets/css/style.css" >
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
</head>
<body>
</body>
  <div class="signInContainer">
    <div class="column">
      <div class="header">
        <img src="assets/images/icons/VideoTubeLogo.png" alt="sitelogo" title="logo" srcset="">
        <h3>Sign In</h3>
        <span>to continue with videoTube</span>
      </div>
      <div class="loginForm">
        <form action="signIn.php" method="POST">

        <?php  echo $account->getError(Constants::$loginFailed); ?>
          <input type="text" name="username" placeholder="Username"  value="<?php getInputValue("username") ?>"autocomplete="off" required>

          <input type="password" name="password"placeholder="Enter Password" required autocomplete="off">

          <input type="submit" value="SUBMIT" name="submitButton">

          <a class="signInMesssage" href="signUp.php">Need an account? sign up here!</a>
        </form>
      </div>

    </div>
  </div>

</html>