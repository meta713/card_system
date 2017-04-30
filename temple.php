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

</head>
<body>
  <div class="wrapper" style="min-height: 675px;">
    <div class="row">
      <!-- SideContent -->
      <div class="col-md-3 col-sm-4 col-xs-4" style="min-height:625px;margin-top:55px;padding-left:40px;z-index:999;">
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
      <div class="col-md-9 col-sm-8 col-xs-8">
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
            <p>一覧</p>
            <div class="row" style="margin-top:20px;padding-right:20px;">
              <div class="col-md-5 col-md-offset-1 col-sm-5 col-sm-offset-1 col-xs-12" style="padding:14px;">
                <a href="?page=home" class="btn btn-default btn-lg btn-block" style="padding:50px;font-weight:bold;color:rgb(109,109,109);">ホーム</a>
              </div>
              <div class="col-md-5 col-md-offset-1 col-sm-5 col-sm-offset-1 col-xs-12" style="padding:14px;">
                <a href="?page=regist" class="btn btn-default btn-lg btn-block" style="padding:50px;font-weight:bold;color:rgb(109,109,109);">登録</a>
              </div>
              <div class="col-md-5 col-md-offset-1 col-sm-5 col-sm-offset-1 col-xs-12" style="padding:14px;">
                <a href="?page=change" class="btn btn-default btn-lg btn-block" style="padding:50px;font-weight:bold;color:rgb(109,109,109);">変更</a>
              </div>
              <div class="col-md-5 col-md-offset-1 col-sm-5 col-sm-offset-1 col-xs-12" style="padding:14px;">
                <a href="?page=use" class="btn btn-default btn-lg btn-block" style="padding:50px;font-weight:bold;color:rgb(109,109,109);">工房利用</a>
              </div>
            </div>
          </div>
          <!-- HomeContent End -->
          <!-- RegistContent -->
          <div class="row" style="margin-top:20px;padding-left:10px;<?php if($page != "regist" && $page != "change"){ print("display:none;"); }?>margin-bottom:20px;">
            <div class="alert alert-info alert-dismissible col-md-10  col-sm-10" role="alert" style="display:none;" id="alert_connecting">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <strong>info:</strong> サーバとの通信を確立しています。しばらくお待ちください。
            </div>
            <div class="alert alert-dismissible col-md-10  col-sm-10" role="alert" style="display:none;" id="alert_res">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <strong>Success!</strong> カードリーダーの準備ができました！
            </div>
            <form class="col-md-9  col-sm-10" style="border:solid 1px #c9c9c9;border-radius:6px;padding:20px;display:none;" onsubmit="return false;" id="regist_form">
              <div class="form-group">
                <label for="InputUserName">氏名</label>
                <input type="email" class="form-control" id="user_name" placeholder="User name" style="width:30%;" maxlength="15">
              </div>
              <div class="form-group">
                <label for="InputStudentNumber">学籍番号</label>
                <input type="text" class="form-control" id="student_number" placeholder="Student number" style="width:30%;" maxlength="10">
                <!-- <p class="help-block">Example block-level help text here.</p> -->
              </div>
              <div class="form-group">
                <label for="InputPhoneNumber">電話番号</label>
                <input type="text" class="form-control" id="phone_number" placeholder="Phone number" style="width:30%;" maxlength="15">
                <p class="help-block" style="font-size:12px;">（左ヅメ、ハイフンなし）</p>
              </div>
              <div class="form-group">
                <label for="InputEmail">eメールアドレス</label>
                <input type="email" class="form-control" id="email" placeholder="Email address" style="width:50%;" maxlength="35">
              </div>
              <button type="submit" class="btn btn-default" style="margin:10px 0;">確認</button>
            </form>
          </div>
          <!-- RegistContent End -->
          <!-- UseContent -->
          <div style="<?php if($page != "use"){ print("display:none;"); }?>">
            <p>利用目的をクリックしてください</p>
            <div class="row" style="margin-top:20px;padding-right:20px;">
              <div class="col-md-5 col-md-offset-1 col-sm-5 col-sm-offset-1" style="padding:14px;">
                <a href="#" class="btn btn-default btn-lg btn-block" style="padding:50px;font-weight:bold;color:rgb(109,109,109);">授業利用</a>
              </div>
              <div class="col-md-5 col-md-offset-1 col-sm-5 col-sm-offset-1" style="padding:14px;">
                <a href="#" class="btn btn-default btn-lg btn-block" style="padding:50px;font-weight:bold;color:rgb(109,109,109);">サークル利用</a>
              </div>
              <div class="col-md-5 col-md-offset-1 col-sm-5 col-sm-offset-1" style="padding:14px;">
                <a href="#" class="btn btn-default btn-lg btn-block" style="padding:50px;font-weight:bold;color:rgb(109,109,109);">研究利用</a>
              </div>
              <div class="col-md-5 col-md-offset-1 col-sm-5 col-sm-offset-1" style="padding:14px;">
                <a href="#" class="btn btn-default btn-lg btn-block" style="padding:50px;font-weight:bold;color:rgb(109,109,109);">その他</a>
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
  <script src="bootstrap/js/jquery.min.js"></script>

  <!-- Plugin JavaScript -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>

  <!-- Bootstrap Core JavaScript -->
  <script src="bootstrap/js/bootstrap.min.js"></script>

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
