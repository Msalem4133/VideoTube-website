<?php
require_once('../includes/config.php');
require_once('../includes/classes/Subscribe.php');
if(isset($_POST['userTo'])&&isset($_POST['userFrom'])){
$subscribe=new Subscribe($con,$_POST['userTo'],$_POST['userFrom']);
$isSubscribed=$subscribe->checkIfAlreadySubscribe();
if($isSubscribed){

  $subscribe->deleteSubscribe();
  $count=$subscribe->countSubscribers();
  $result=array(
    "subsribe"=>-1,
    "count"=>$count,
  );
  echo json_encode($result);
}
else
{
  $subscribe->addSubscribe();
  $count=$subscribe->countSubscribers();
  $result=array(
    "subsribe"=>1,
    "count"=>$count,
  );
  echo json_encode($result);
}
}
else{
echo "One or more parameter are not passed into subscribe.php the file";
}
?>