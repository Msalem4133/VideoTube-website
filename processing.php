<?php require_once("includes/header.php");
      require_once("includes/classes/VideoUploadData.php");
      require_once("includes/classes/VideoProcessor.php");
?>

<?php
  if(!isset($_POST['uploadButton'])){
    echo "You cannot access this page directly";
    exit();
  }
  //1-store data for this upload
  $videoUploadData=new VideoUploadData($_FILES['fileInput'],$_POST['titleInput'],$_POST['descriptionInput'],$_POST['categoryInput'],$_POST['privacyInput'],$userLoggedInObj->getUsername());
  //2-processVidoeDataupload
  $videoProcessor=new VideoProcessor($con);
  $wasSuccessful=$videoProcessor->upload($videoUploadData);

  if($wasSuccessful){
    echo"upload succefull";
  }

?>



 <?php require_once("includes/footer.php") ; ?>