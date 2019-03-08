<?php 
require_once("includes/header.php") ; 
require_once('includes/classes/videoDetailsFormProvider.php');
?>
<div class="column">
<?php
$FormProvider=new videoDetailsFormProvider($con);
echo $FormProvider->creatUploadForm();
?>
</div>
<script>
$("form").submit(function(){
$("#loadingModel").modal("show");
});
</script>
<div class="modal fade" id="loadingModel" tabindex="-1" role="dialog"           aria-labelledby="loadingModel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-body">
        Please Wait......
        <img src="assets/images/icons/loading-spinner.gif" alt="" srcset="">
      </div>
    </div>
  </div>
</div>
 <?php require_once("includes/footer.php") ; ?>