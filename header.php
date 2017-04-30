<?php
//パス

session_start();
 if(!isset($_SESSION["user_name"]) || !isset($_SESSION["user_password"])){
     header("Location: index.html");
     session_destroy();
     exit;
 }  else {
     

 
$fpath = '../../data/data.csv';
//ファイル名
$fname = 'data.csv';

header('Content-Type: application/force-download');
header('Content-Length: '.filesize($fpath));
header('Content-disposition: attachment; filename="'.$fname.'"');
readfile($fpath);

}
