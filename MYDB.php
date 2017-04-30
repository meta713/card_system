<?php
 if(!isset($_SESSION["user_name"]) || !isset($_SESSION["user_password"])){
     header("Location: index.html");
     session_destroy();
     exit;
 }

function db_connect(){
     $db_user = "design";
     $db_pass = "ds_master";
     $db_host = "10.0.1.51";
     $db_name = "design_studio";


   //$db_user = "sample";
   //$db_pass = "doyadoya4141";
   //$db_host = "localhost";
    // $db_host = "192.168.24.2";
   //$db_name = "test";


    $db_type = "mysql";

    $dsn = "$db_type:host=$db_host;dbname=$db_name;charset=utf8";
    try{
        $pdo = new PDO($dsn , $db_user , $db_pass);
        $pdo->setAttribute(pdo::ATTR_ERRMODE, pdo::ERRMODE_EXCEPTION);
        $pdo->setAttribute(pdo::ATTR_EMULATE_PREPARES, false);
    } catch (Exception $ex) {
        die("エラー:".$ex->getMessage());
    }
    return $pdo;
}
?>
