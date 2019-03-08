<?php 

require_once('includes/config.php'); 
require_once('includes/classes/FormSanitizer.php');
require_once('includes/classes/Account.php');
require_once('includes/classes/Constants.php');

$account=new Account($con);

if(isset($_POST["submitButton"]))
{
  //get data from form and sentize it
  $firstName=FormSanitizer::sanitizeFormString($_POST['firstName']);
  $lastName=FormSanitizer::sanitizeFormString($_POST['lastName']);

  $username=FormSanitizer::sanitizeFormUsername($_POST['username']);

  $email=FormSanitizer::sanitizeFormEmail($_POST['email']);
  $email2=FormSanitizer::sanitizeFormEmail($_POST['email2']);

  $password=FormSanitizer::sanitizeFormPassword($_POST['password']);
  $password2=FormSanitizer::sanitizeFormPassword($_POST['password2']);

  $wasSuccessfull=$account->register($firstName,$lastName,$username,$email,$email2,$password,$password2);
  if($wasSuccessfull){
    //success
    $_SESSION["userLoggedIn"]=$username;
    //rediret user to indexpage 
    header("Location:index.php");
  }
  else
  {
    echo "Falid";
  }
  /*
  echo $firstName." ".$lastName." ".$firstName." ".$username." ".$email." ".$email2." ".$password." ".$password2." ";*/


}

function getInputValue($name){
  if(isset($_POST[$name]))echo $_POST[$name];
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>videotube</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
  <link rel="stylesheet" type="text/css" 
  href="assets/css/style.css">
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
        <h3>Sign Up</h3>
        <span>to continue with videoTube</span>
      </div>
      <div class="loginForm">
        <form action="signUp.php" method="POST">

          <?php  echo $account->getError(Constants::$firstNameCharacters); ?>
          <input type="text"name="firstName" placeholder="First name" value="<?php getInputValue("firstName") ?>"autocomplete="off" required>
          <?php  echo $account->getError(Constants::$lastNameCharacters); ?>

          <input type="text"name="lastName" placeholder="Lastt name" value="<?php getInputValue("lastName") ?>" autocomplete="off" required>

          <?php  echo $account->getError(Constants::$usernameCharacters); ?>
          <?php  echo $account->getError(Constants::$usernameTaken); ?>
          <input type="text"name="username" placeholder="Username" value="<?php getInputValue("username") ?>" autocomplete="off" required>

          <?php  echo $account->getError(Constants::$emailDonotMatch); ?>
          <?php  echo $account->getError(Constants::$emailInvalid); ?>
          <?php  echo $account->getError(Constants::$emailTaken); ?>
          <input type="email"name="email" placeholder="Email" value="<?php getInputValue("email") ?>" autocomplete="off" required>
          <input type="email"name="email2" placeholder="Confirm Email" value="<?php getInputValue("email2") ?>"   autocomplete="off" required>

          <?php  echo $account->getError(Constants::$passwordDonotMatch); ?>
          <?php  echo $account->getError(Constants::$passwordNotAlphanumeric); ?>
          <?php  echo $account->getError(Constants::$passwordLength); ?>
          <input type="password"name="password" placeholder="password" autocomplete="off" required>
          <input type="password"name="password2" placeholder="Confirm password" autocomplete="off" required>

          <input type="submit" name="submitButton" value="submit">
        </form>
      </div>

      <a class="signInMesssage" href="signIn.php">Already have  an account? sign in here!</a>

    </div>
  </div>

</html>