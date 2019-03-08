<?php
ob_start();
session_start();
date_default_timezone_set("Africa/Cairo");

try{
$con=new PDO('mysql:host=localhost;dbname=videotube', 'root', '');
$con->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);
}
catch(PDOException $ex){
  echo "Connection failed ".$ex->getMessage();
}
?>