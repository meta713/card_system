<?php
 session_start();
 if(!isset($_SESSION["user_name"]) || !isset($_SESSION["user_password"])){
     header("Location: index.html");
     session_destroy();
     exit;
 }
 $page = $_GET["page"];
?>

<!DOCTYPE html>
<html lang="ja">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>System of CardReader</title>

    <!-- Bootstrap Core CSS -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/stylesheet/sweetalert.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/stylesheet/iziToast.min.css">
    <link rel="shortcut icon" href="key.png">
    <!-- Theme CSS -->
    <!-- <link href="css/freelancer.min.css" rel="stylesheet"> -->

    <!-- Custom Fonts -->
    <!-- <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic" rel="stylesheet" type="text/css">
    <link href="css/fullcalendar.min.css" rel="stylesheet" type="text/css"> -->

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>
    /*.btn-under:hover > span {
      border-bottom:solid 2px rgb(255, 85, 210);
    }*/
    .confirm_data{
      padding: 9px 12px;
      text-align: center;
      border-radius: 5px;
      display: none;
    }
    </style>

</head>
<body>
  <div class="wrapper" style="min-height: 675px;">
    <div class="row">
      <!-- SideContent -->
      <div class="col-md-3 col-sm-4 col-xs-4 side_menu" style="min-height:625px;margin-top:55px;padding-left:40px;z-index:999;">
        <div style="border:solid 1.5px #c9c9c9;border-radius:10px;">
          <ul style="list-style:none;margin-top:0px;line-height:40px;padding:0;">
            <li style="padding:33px;font-size:14px;color:#6d6d6d;font-weight:bold;border-bottom:solid 1px #c9c9c9;text-align:center;">
              ログインユーザ <br><span style="text-align:center;"><?php if(isset($_SESSION["user_name"])){ print("工房スタッフ"); }else{ print("unknown"); } ?></span>
            </li>
            <li style="padding:12px;font-size:14px;<?php if($page == "home"){ print("background-color:#E9E9E9;font-weight:bold;"); }?>border-bottom:solid 1px #c9c9c9;text-align:center;">
              <a href="?page=home" style="text-decoration:none;display:block;color:#6d6d6d;">ホーム</a>
            </li>
            <li style="padding:12px;font-size:14px;<?php if($page == "regist"){ print("background-color:#E9E9E9;font-weight:bold;"); }?>text-align:center;border-bottom:solid 1px #c9c9c9;">
              <a href="?page=regist" style="text-decoration:none;display:block;color:#6d6d6d;">登録</a>
            </li>
            <li style="padding:12px;font-size:14px;<?php if($page == "change"){ print("background-color:#E9E9E9;font-weight:bold;"); }?>text-align:center;border-bottom:solid 1px #c9c9c9;">
              <a href="?page=change" style="text-decoration:none;display:block;color:#6d6d6d;">変更</a>
            </li>
            <li style="padding:12px;font-size:14px;<?php if($page == "use"){ print("background-color:#E9E9E9;font-weight:bold;"); }?>text-align:center;border-bottom:solid 1px #c9c9c9;">
              <a href="?page=use" style="text-decoration:none;display:block;color:#6d6d6d;">工房利用</a>
            </li>
          </ul>
          <div style="color:#6d6d6d;text-align:center;padding-top:10px;">
            <h5 style="font-weight:bold;">オプション</h5>
          </div>
          <div style="margin-top:10px;text-align:center;margin-bottom:45px;">
            <a data-toggle="modal" data-target="#myModal" class="btn" style="line-height: 32px;height: 44px;font-size: 12px;font-weight: bold;width: 90%;margin-top: 15px;color: #ffffff;background-color: #4472c4;">
              ログアウト
            </a>
          </div>
        </div>
      </div>
      <!-- SideContent End -->
      <div class="col-md-9 col-sm-8 col-xs-8 main_content">
        <!-- ContentTitle -->
        <header style="border-bottom:solid 1px #c9c9c9;text-align:center;padding: 10px 0;margin: 0 20px;">
          <h3 style="line-height: inherit;font-weight: 400;">Card Reader System</h3>
        </header>
        <!-- ContentTitle End -->
        <div class="container-fluid" style="padding-top:15px;margin: 0 40px 0 20px;">
          <h2><?php switch ( $page ) {
            case 'home':
            {
              print("ホーム");
              break;
            }

            case 'regist':
            {
              print("登録");
              break;
            }

            case 'change':
            {
              print("変更");
              break;
            }

            case 'use':
            {
              print("工房利用");
              break;
            }

            default:
            {
              print("unknown");
              break;
            }
          }
          ?></h2>
          <!-- HomeContent -->
          <div style="<?php if($page != "home"){ print("display:none;"); }?>">
            <p style="font-size: 16px;">操作を選択してください</p>
            <div class="row" style="margin-top:20px;padding-right:20px;">
              <div class="col-md-5 col-md-offset-1 col-sm-5 col-sm-offset-1 col-xs-12" style="padding:14px;">
                <a href="?page=use" class="btn btn-default btn-lg btn-block" style="padding:50px;font-weight:bold;color:rgb(109,109,109);border-radius:12px;">工房利用</a>
              </div>
              <div class="col-md-5 col-md-offset-1 col-sm-5 col-sm-offset-1 col-xs-12" style="padding:14px;">
                <a href="?page=regist" class="btn btn-default btn-lg btn-block" style="padding:50px;font-weight:bold;color:rgb(109,109,109);border-radius:12px;">登録</a>
              </div>
              <div class="col-md-5 col-md-offset-1 col-sm-5 col-sm-offset-1 col-xs-12" style="padding:14px;">
                <a href="?page=change" class="btn btn-default btn-lg btn-block" style="padding:50px;font-weight:bold;color:rgb(109,109,109);border-radius:12px;">変更</a>
              </div>
              <div class="col-md-5 col-md-offset-1 col-sm-5 col-sm-offset-1 col-xs-12" style="padding:14px;">
                <a href="?page=home" class="btn btn-default btn-lg btn-block" style="padding:50px;font-weight:bold;color:rgb(109,109,109);border-radius:12px;">ホーム</a>
              </div>
            </div>
          </div>
          <!-- HomeContent End -->
          <!-- RegistContent -->
          <div class="row regist_form" style="margin-top:20px;padding-left:20px;<?php if($page != "regist" && $page != "change"){ print("display:none;"); }?>margin-bottom:20px;">
            <form class="col-md-8  col-sm-8" style="border:solid 1px #c9c9c9;border-radius:6px;padding:20px;" onsubmit="return false;" id="regist_form">
              <div class="form-group">
                <label for="InputUserName">氏名</label>
                <input type="text" class="form-control input_data" id="user_name" placeholder="User name" style="width:30%;" maxlength="15">
                <p class="bg-info confirm_data"></p>
              </div>
              <div class="form-group">
                <label for="InputStudentNumber">学籍番号</label>
                <input type="text" class="form-control input_data" id="student_number" placeholder="Student number" style="width:30%;" maxlength="10">
                <p class="bg-info confirm_data"></p>
                <!-- <p class="help-block">Example block-level help text here.</p> -->
              </div>
              <div class="form-group">
                <label for="InputPhoneNumber">電話番号</label>
                <input type="text" class="form-control input_data" id="phone_number" placeholder="Phone number" style="width:40%;" maxlength="15">
                <p class="bg-info confirm_data"></p>
                <p class="help-block" style="font-size:12px;">（左ヅメ、ハイフンなし）</p>
              </div>
              <div class="form-group">
                <label for="InputEmail">eメールアドレス</label>
                <input type="text" class="form-control input_data" id="email" placeholder="Email address" style="width:70%;" maxlength="35">
                <p class="bg-info confirm_data"></p>
              </div>
              <button type="submit" class="btn btn-default" style="margin:12px 0;" id="submit_btn">確認</button>
              <div class="btn_group" style="margin:26px 0 8px 0;display:none;float:right;">
                <button type="button" class="btn btn-default" id="back_btn">修正する</button>
                <button type="button" class="btn btn-primary" id="send_btn" style="margin-left:10px;" data-loading-text="送信中..."><?php if($page == "regist"){ print("登録"); }else if($page == "change"){ print("変更"); } ?>する</button>
              </div>
              <div id="error_area" style="margin-top:10px;display:none;">
              </div>
            </form>
            <div class="col-md-4 col-sm-4" id="alert_area">
              <!-- <div class="alert alert-info alert-dismissible col-md-12  col-sm-12" role="alert" style="display:none;" id="alert_connecting">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <strong>info :</strong> サーバとの通信を確立しています。しばらくお待ちください。
              </div>
              <div class="alert alert-success alert-dismissible col-md-12  col-sm-12" role="alert" style="display:none;" id="alert_ready">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <strong>Success! :</strong> カードリーダーの準備ができました！ カードをタッチしてください。
              </div>
              <div class="alert alert-danger alert-dismissible col-md-12  col-sm-12" role="alert" style="display:none;" id="alert_fail">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <strong>Warning! :</strong> サーバとの接続に失敗しました。
              </div>
              <div class="alert alert-danger alert-dismissible col-md-12  col-sm-12" role="alert" style="display:none;" id="alert_timeout">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <strong>Warning! :</strong> タイムアウトです、もう一度最初からお願いします。
              </div>
              <div class="alert alert-success alert-dismissible col-md-12  col-sm-12" role="alert" style="display:none;" id="alert_ok">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <strong>Done! :</strong> カードを確認しました！ 情報を登録してください！
              </div>
              <div class="alert alert-warning alert-dismissible col-md-12  col-sm-12" role="alert" style="display:none;" id="cancel_btn">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <strong>delete! :</strong> 全てのログを削除します。
              </div> -->
            </div>
          </div>
          <!-- RegistContent End -->
          <!-- UseContent -->
          <div style="<?php if($page != "use"){ print("display:none;"); }?>">
            <p style="font-size: 16px;">利用目的をクリックしてください</p>
            <div class="row" style="margin-top:20px;padding-right:20px;">
              <div class="col-md-5 col-md-offset-1 col-sm-5 col-sm-offset-1" style="padding:14px;" btn="B1">
                <a class="btn btn-default btn-lg btn-block btn-under" style="padding:50px;font-weight:bold;color:rgb(109,109,109);border-radius:12px;"><span class="edu">授業利用</span></a>
              </div>
              <div class="col-md-5 col-md-offset-1 col-sm-5 col-sm-offset-1" style="padding:14px;" btn="B2">
                <a class="btn btn-default btn-lg btn-block btn-under" style="padding:50px;font-weight:bold;color:rgb(109,109,109);border-radius:12px;"><span class="cir">サークル利用</span></a>
              </div>
              <div class="col-md-5 col-md-offset-1 col-sm-5 col-sm-offset-1" style="padding:14px;" btn="B3">
                <a class="btn btn-default btn-lg btn-block btn-under" style="padding:50px;font-weight:bold;color:rgb(109,109,109);border-radius:12px;"><span class="exa">研究利用</span></a>
              </div>
              <div class="col-md-5 col-md-offset-1 col-sm-5 col-sm-offset-1" style="padding:14px;" btn="B4">
                <a class="btn btn-default btn-lg btn-block btn-under" style="padding:50px;font-weight:bold;color:rgb(109,109,109);border-radius:12px;"><span class="etc">その他</span></a>
              </div>
            </div>
          </div>
          <!-- UseContent End -->
        </div>
      </div>
    </div>
  </div>
  <!-- Footer -->
  <footer style="padding:13px 0;text-align:center;border-top:solid 1px #c9c9c9;">
    <div class="pull-right"></div>
    <b style="font-weight:400">Copyright &copy; 2017 Design Studio<span style="color:red;">.</span> All Rights Reserved.<b>
  </footer>
  <!-- Footer End -->
  <!-- Modal -->
  <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content" style="border-radius:3px;">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel" style="float:left;padding-top:4px;">ログアウトしますか？</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" style="font-weight:200;">
          <?php if(isset($_SESSION["user_name"])){ print("工房スタッフ"); }else{ print("unknown"); } ?> としてログイン中です。
        </div>
        <div class="modal-footer">
          <a type="button" class="btn btn-default" data-dismiss="modal">キャンセル</a>
          <a href="logout.php" type="button" class="btn btn-primary" style="background-color: #4472c4;">ログアウト</a>
        </div>
      </div>
    </div>
  </div>
  <!-- ModalEnd -->
  <!-- ConfirmModal -->
  <!-- <div class="modal fade" id="ConfirmModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content" style="border-radius:3px;">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">確認</h4>
        </div>
        <div class="modal-body">
          ...
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">キャンセル</button>
          <button type="button" class="btn btn-primary">する</button>
        </div>
      </div>
    </div>
  </div> -->
  <!-- ConfirmModalEnd -->
  <script src="bootstrap/js/jquery.min.js"></script>
  <!-- Plugin JavaScript -->
  <script src="assets/scripts/jquery.easing.min.js"></script>

  <!-- Bootstrap Core JavaScript -->
  <script src="bootstrap/js/bootstrap.min.js"></script>
  <script src="assets/scripts/jquery.formatter.min.js"></script>
  <script src="https://cdn.jsdelivr.net/jquery.loadingoverlay/latest/loadingoverlay.min.js"></script>
  <script src="https://cdn.jsdelivr.net/jquery.loadingoverlay/latest/loadingoverlay_progress.min.js"></script>
  <script src="assets/scripts/sweetalert.min.js"></script>
  <!-- <script src="assets/scripts/sweetalert-dev.js"></script> -->
  <script src="assets/scripts/iziToast.min.js" type="text/javascript"></script>
  <?php
    if( $page == "regist" || $page == "change" || $page == "use" ){
      print("<script type='text/javascript' src='assets/scripts/Data_Format.js'></script>");
    }
  ?>
  <?php
    if( $page == "use" ){
      print("<script type='text/javascript' src='assets/scripts/use.js'></script>");
    }
  ?>

  <!-- Contact Form JavaScript -->
  <!-- <script src="js/jqBootstrapValidation.js"></script>
  <script src="js/contact_me.js"></script> -->

  <!-- Theme JavaScript -->
  <!-- <script src="js/freelancer.min.js"></script>
  <script type="text/javascript" src="js/moment.min.js"></script>
  <script type="text/javascript" src="js/fullcalendar.min.js"></script> -->
  <?php
    if( $page == "regist" || $page == "change" ){
      print("<script type='text/javascript' src='assets/scripts/socket.js'></script>");
    }
  ?>
</body>
</html>
