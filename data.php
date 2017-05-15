<?php

//アクセスの正当性を確認
session_start();
if(!isset($_SESSION["user_name"]) || !isset($_SESSION["user_password"])){
    header("Location: index.html");
    session_destroy();
    exit;
}

//type : post 確認
$check	= $_SERVER['HTTP_X_REQUESTED_WITH'];
$res_data = $_POST["data"];

if( $check && isset( $res_data ) && strtolower( $check ) == 'xmlhttprequest' ){
  $send_data = array();
  $data = "";
  try {
    $data = json_decode( $res_data , true );
  }catch(Exception $ex){
    $send_data["status"] = "error";
    $send_data["error"] = $ex;
    print( json_encode( $res_data ) );
    exit;
  }
  //動作
  switch( $data["action"] ){
    //登録
    case "regist":
    {
      require_once 'MYDB.php';
      $pdo = db_connect();
      //get new userNo
      $sql = "select max(userNo) + 1 from kit_user2";
      try {
          $stmh = $pdo->prepare( $sql );
          $stmh->execute();
      } catch (Exception $ex) {
          $send_data["status"] = "error1";
          $send_data["error"] = $ex;
          print( json_encode( $res_data ) );
          exit;
      }
      $userNo = sprintf( "%08d" , $stmh->fetchAll()[0][0] );
      //regist sql
      $time = date( "Y-m-d H:i:s" , time() );
      $sql = "insert into id_user2 values('$userNo','$data[idm]','1','$time','A')";
      try {
          $stmh = $pdo->prepare( $sql );
          $stmh->execute();
      } catch (Exception $ex) {
          $send_data["status"] = "error2";
          //$res_data["error"] = $ex;
          print( json_encode( $res_data ) );
          exit;
      }
      $limit = date("Y-03-31",strtotime("+1 year"));
      $sql = "insert into kit_user2 values('$userNo','03','$data[student_number]','$data[user_name]','$data[email]','$data[phone_number]','$limit','0',NULL,NULL,NULL,NULL)";
      try {
          $stmh = $pdo->prepare( $sql );
          $stmh->execute();
      } catch (Exception $ex) {
          $send_data["status"] = "error3";
          //$res_data["error"] = $ex;
          print( json_encode( $send_data ) );
          exit;
      }
      $send_data["status"] = "ok";
      $send_data["code"] = $sql;
      print( json_encode( $send_data ) );
      exit;
    }
    //更新
    case "change":
    {
      require_once 'MYDB.php';
      $pdo = db_connect();
      //get new userNo
      $sql = "select max(userNo) + 1 from kit_user2";
      try {
          $stmh = $pdo->prepare( $sql );
          $stmh->execute();
      } catch (Exception $ex) {
          $send_data["status"] = "error1";
          $send_data["error"] = $ex;
          print( json_encode( $res_data ) );
          exit;
      }
      $userNo = sprintf( "%08d" , $stmh->fetchAll()[0][0] );
      //get old userNo and registTimes
      $sql = "select userNo , registTimes + 1 from id_user2 where idm = '$data[idm]' and stopFlg = 'A'";
      try {
          $stmh = $pdo->prepare( $sql );
          $stmh->execute();
      } catch (Exception $ex) {
          $send_data["status"] = "error1";
          $send_data["error"] = $ex;
          print( json_encode( $res_data ) );
          exit;
      }
      list( $old_userNo , $new_registTimes ) = $stmh->fetchAll()[0];
      //新規登録
      $time = date( "Y-m-d H:i:s" , time() );
      $sql = "insert into id_user2 values('$userNo','$data[idm]','$new_registTimes','$time','A')";
      try {
          $stmh = $pdo->prepare( $sql );
          $stmh->execute();
      } catch (Exception $ex) {
          $send_data["status"] = "error1";
          $send_data["error"] = $ex;
          print( json_encode( $res_data ) );
          exit;
      }
      //引き継ぎ用のデータを確保
      $sql = "select couponBalance,startDate1,endDate1,startDate2,endDate2 from kit_user2 where userNo = '$old_userNo'";
      try {
          $stmh = $pdo->prepare( $sql );
          $stmh->execute();
      } catch (Exception $ex) {
          $send_data["status"] = "error1";
          $send_data["error"] = $ex;
          print( json_encode( $res_data ) );
          exit;
      }
      list( $couponBalance , $startDate1 , $endDate1 , $startDate2 , $endDate2 ) = $stmh->fetchAll()[0];
      $limit = date("Y-03-31",strtotime("+1 year"));
      $sql = "insert into kit_user2 values('$userNo','04','$data[student_number]','$data[user_name]','$data[email]','$data[phone_number]','$limit','$couponBalance',NULL,NULL,NULL,NULL)";
      try {
          $stmh = $pdo->prepare( $sql );
          $stmh->execute();
      } catch (Exception $ex) {
          $send_data["status"] = "error1";
          $send_data["error"] = $ex;
          print( json_encode( $res_data ) );
          exit;
      }
      //データの更新 stopFlg = "U"にする
      $sql = "update id_user2 set stopFlg = 'U' where userNo = '$old_userNo'";
      try {
          $stmh = $pdo->prepare( $sql );
          $stmh->execute();
      } catch (Exception $ex) {
          $send_data["status"] = "error1";
          $send_data["error"] = $ex;
          print( json_encode( $res_data ) );
          exit;
      }
      $send_data["status"] = "ok";
      $send_data["code"] = $sql;
      print( json_encode( $send_data ) );
      exit;
    }
    //利用
    case "use":
    {
      require_once 'MYDB.php';
      $pdo = db_connect();
      //get new logNo
      $sql = "select count(logNo) + 1 from ride_log2";
      try {
          $stmh = $pdo->prepare( $sql );
          $stmh->execute();
      } catch (Exception $ex) {
          $send_data["status"] = "error1";
          $send_data["error"] = $ex;
          print( json_encode( $res_data ) );
          exit;
      }
      $logNo = $stmh->fetchAll()[0][0];
      //すでに登録済みか確認
      $first_time = date( "Y-m-d 00:00:00" , time() );
      $last_time = date( "Y-m-d 23:59:59" , time() );
      $sql = "select logNo from ride_log2 where idm = '$data[idm]' and rideDate between '$first_time' and '$last_time'";
      try {
          $stmh = $pdo->prepare( $sql );
          $stmh->execute();
      } catch (Exception $ex) {
          $send_data["status"] = "error1";
          $send_data["error"] = $ex;
          print( json_encode( $res_data ) );
          exit;
      }
      $ride_info = $stmh->fetchAll();
      if( count($ride_info) >= 1 ){
        //更新
        $old_logNo = $ride_info[0][0];
        $sql = "update ride_log2 set busNo = '$data[recog]' where logNo = '$old_logNo'";
      }else{
        //登録
        // current time
        $time = date( "Y-m-d H:i:s" , time() );
        $sql = "insert into ride_log2 values('$logNo','$data[idm]','$data[recog]','C','$time')";
      }
      try {
          $stmh = $pdo->prepare( $sql );
          $stmh->execute();
      } catch (Exception $ex) {
          $send_data["status"] = "error1";
          $send_data["error"] = $ex;
          print( json_encode( $res_data ) );
          exit;
      }
      $send_data["status"] = "ok";
      $send_data["code"] = $sql;
      print( json_encode( $send_data ) );
      exit;
    }
    //エラー
    default :
    {
      print("error");
      exit;
    }
  }

}else{
  print("no");
}

?>
