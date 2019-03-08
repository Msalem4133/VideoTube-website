<?php
class videoDetailsFormProvider{

  private $con;
  public function __construct($con){
    $this->con=$con;
  }
  public  function creatUploadForm()
  {
    $fileInput=$this->creatFileInput();
    $titleInput=$this->creatTitleInput();
    $descriptionInput=$this->creatDescriptionInput();
    $privacyInput=$this->creatPrivacyInput();
    $categoryInput=$this->creatCategoriesInput();
    $uploadButton=$this->creatLoadButton();
    return "<form action='processing.php'method='POST' enctype='multipart/form-data'>
              $fileInput
              $titleInput
              $descriptionInput
              $categoryInput
              $privacyInput
              $uploadButton
            </form>";
  }
  private function creatFileInput(){
    return "<div class='form-group '>
              <input class='form-control-file'type='file'name='fileInput' required>
              <small class='form-text text-muted' id='fileHelp'>Max 3mb size</small>
            </div>";
  }
  private function creatTitleInput(){
    return"<div class='form-group'>
              <input class='form-control' type='text' placeholder='Enter Title'name='titleInput'>
            </div>";
  }
  private function creatDescriptionInput(){
    return "<div class='form-group'>
              <textarea class='form-control'name='descriptionInput'placeholder='Description'rows='3'></textarea>
            </div>";
  }
  private function creatPrivacyInput(){
    return"<div class='form-group '>
              <select class='form-control'      name='privacyInput'>
                <option value='0'>Private</option>
                <option value='1'>Public</option>
              </select>
            </div>";
  }
  private function creatCategoriesInput(){
    $getCategoriesData=$this->con->prepare("select * from categories");
    $getCategoriesData->execute();

    $html="<div class='form-group '>
    <select class='form-control'name='categoryInput'>";
   
    while($row=$getCategoriesData->fetch(PDO::FETCH_ASSOC))
    {
      $name=$row['name'];
      $id=$row['id'];

      $html.="<option value='$id'>$name</option>";
    }
    /*
    foreach( $getCategoriesDataRows as $row)
    {
      $part2=$part2."<option value=".$row['id'].">".$row['name']."</option>";
    }
    */
    $html.="</select>
          </div>";
          return $html;
  }
  private function creatLoadButton(){
      return "<button type='submit' class='btn btn-primary'name='uploadButton'>Upload</button>";
  }
}
?>