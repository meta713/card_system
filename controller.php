<?php

//ログイン処理(値確認)
$input_username = htmlspecialchars($_POST["username"]);
$input_password = htmlspecialchars($_POST["password"]);

$ds_username = "design";
$ds_password = "ds_master";

$array = array();

if(!($ds_username == $input_username) || !($ds_password == $input_password)){
  $array["status"] = "deny";
  print(json_encode($array));
  //header("Location: index.html");
  exit;
}

//セッション値の格納
session_start();
$_SESSION["user_name"] = $input_username;
$_SESSION["user_password"] = $input_password;
$_SESSION["m_logintime"] = time();
$_SESSION["c_logintime"] = time();

$array["status"] = "ok";
$array["url"] = "temple.php?page=home";
print(json_encode($array));
//header("Location: temple.html");
exit;

 ?>
