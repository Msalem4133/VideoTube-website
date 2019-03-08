<?php require_once("includes/header.php") ;  ?>
<div class="videoSection">
  <?php
  $subscriptionsProvider=new SubscriptionProvider($con,$userLoggedInObj);

 $subscriptionVideos= $subscriptionsProvider->getVideos();

 $videoGrid=new VideoGrid($con,$userLoggedInObj);
 
 if(User::isLoggedIn()&&sizeof($subscriptionVideos)>0){
  echo $videoGrid->create($subscriptionVideos,"Subscribers",false);
 }
  echo $videoGrid->create(null,"Recommended",false);

  ?>
</div>
 <?php require_once("includes/footer.php") ; ?>