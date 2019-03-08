<?php
class VideoGrid{
  private $con,$userLoggedInObj;
  private $LargeMode=false;
  private $gridClass="videoGrid";

  public function __construct($con,$userLoggedInObj)
  {
    $this->con=$con;
    $this->userLoggedInObj=$userLoggedInObj;

  }

  public function create($videos,$title,$showFilter)
  {
    if($videos==null){
      $gridItems=$this->generateItems();
    }
    else{
      $gridItems=$this->generateItemsFromVideos($videos);
    }
    $header="";
    if($title!=null){
      $header=$this->creatGridHeader($title,$showFilter);
    }

    return "
          $header
          <div class='$this->gridClass'>
            $gridItems
          </div>";
  }

  public function generateItems(){
    $query=$this->con->prepare("SELECT * from videos ORDER BY RAND() LIMIT 15");
    $query->execute();

    $elementsHtml="";

    while($row=$query->fetch(PDO::FETCH_ASSOC)){
      $video =new Video($this->con,$row,$this->userLoggedInObj);
      $item=new videoGridItem($video,$this->LargeMode);
      $elementsHtml.=$item->create();
    }
    return $elementsHtml;
  }
  public function generateItemsFromVideos($videos)
  {
    $elementsHtml="";
    foreach($videos as $video){
      $item=new videoGridItem($video,$this->LargeMode);
      $elementsHtml.=$item->create();
    }
    return $elementsHtml;
  }
  public function creatGridHeader($title,$showFilter){
    $filter="";
    //creat filter
    return"<div class='videoGridHeader'>
                <div class='left'>
                  $title
                </div>
                $filter
            </div>";
  }
}
?>