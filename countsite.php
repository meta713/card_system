<?php
session_start();
 if(!isset($_SESSION["user_name"]) || !isset($_SESSION["user_password"])){
     header("Location: index.html");
     session_destroy();
     exit;
 }

 //自動ログアウトセッション
 if(time() >= $_SESSION["m_logintime"] + 60 * 15){
     header("Location: index.html");
     session_destroy();
     exit;
 }else{
     $_SESSION["m_logintime"] = time();
 }
 ?>

<?php
require_once 'MYDB.php';
?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <title>
            DesignStudio
        </title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width">
        <script src="script/jquery-1.12.1.min.js"></script>
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
        <script src="script/ac_control.js"></script>
        <link href="css/countsite.css" rel="stylesheet">
        <link href="css/modal-multi.css" rel="stylesheet">
        <style>
            .top h3 {
                color: #000;
                margin: 0;
                font-size: 18px;
            }

            .top h3 a {
                text-decoration: none;
                color : #666;
            }
        </style>
    </head>
    <body>
        <div style="margin-bottom: 32px; padding-left: 10px; padding-top: 10px; padding-right : 10px;" class="top">
            <h3>ログインユーザー : <?php print $_SESSION["user_name"];?> [<a href="logout.php" style="display:inline;">ログアウト</a>]</h3>
            <?php
            $today = date("Y-m-d H:i:s" , time() - 60 * 60 * 10);
            print "<div id='search_time'>検索時刻 : ".$today."</div>";
            ?>
            <div id="clock"></div>

        <?php

        $pdo = db_connect();
        $day = substr($today, 0, 10);
        try{

            $first = "'" . $day . " 00:00:00'";
            $last = "'" . $day . " 23:59:59'";

            $sql = "select kit_user2.personalID , kit_user2.name , kit_user2.tel , kit_user2.mail , CASE ride_log2.busNo WHEN 'B1' THEN '授業' WHEN 'B2' THEN 'サークル' WHEN 'B3' THEN '研究' WHEN 'B4' THEN 'その他' ELSE '実験' END as '目的' , ride_log2.rideDate from kit_user2 , ride_log2 , id_user2 where ride_log2.rideDate between $first and $last and ride_log2.idm = id_user2.idm and id_user2.userNo = kit_user2.userNo and id_user2.stopFlg = 'A' group by ride_log2.rideDate order by ride_log2.rideDate limit 1000;";
            $stmh = $pdo->prepare($sql);
            $stmh->execute();
            $count = $stmh->rowCount();
        } catch (Exception $ex) {
            print "エラー:".$ex->getMessage()."<br>";
        }
        if($count < 1 ){
            print "<div id='count_of_row'>検索結果がありません</div>";

            ?>
            <br>
            <button type="button" id="search_button">詳細検索</button>
        <div id="selectarea" style="display: none;">
            <label>[検索条件]</label><br>
            <div>
            <label>期間指定 :</label>
            <select name="operate" id="operate" class="selectpicker" style="margin-left: 5px;">
                <option value="0">---条件を選択---</option>
                <option value="1">今日(デフォルト)</option>
                <option value="2">月間</option>
                <option value="3">年間</option>
                <option value="4">任意指定</option>
            </select>
            <div id="addarea" style="display: -webkit-inline-box ;"></div><br>
            <div id="days_area" style="display : -webkit-inline-box;"></div>
            <div id="errorarea"></div>
            </div>
            <button type="button" id="accept">検索</button>
            <button type="button" id="cancel">キャンセル</button>
        </div>
        </div>
        <div id="tablearea" style="padding : 0 8px;">
            <div id="result_search">[本日の工房利用状況]</div><div id="access_sum"></div>
            <div id="error_report"></div>
        </div>

        <?php
        }else{
            print "<div id='count_of_row'>検索結果は $count 件です</div>";
        ?>
            <br>
            <button type="button" id="search_button">詳細検索</button>
        <div id="selectarea" style="display: none;">
            <label>[検索条件]</label><br>
            <div>
            <label>期間指定 :</label>
            <select name="operate" id="operate" class="selectpicker" style="margin-left: 5px;">
                <option value="0">---条件を選択---</option>
                <option value="1">今日(デフォルト)</option>
                <option value="2">月間</option>
                <option value="3">年間</option>
                <option value="4">任意指定</option>
            </select>
            <div id="addarea" style="display: -webkit-inline-box ;"></div><br>
            <div id="days_area" style="display : -webkit-inline-box;"></div>
            <div id="errorarea"></div>
            </div>
            <button type="button" id="accept">検索</button>
            <button type="button" id="cancel">キャンセル</button>
        </div>
        </div>
        <div id="tablearea" style="padding : 0 8px;">
            <div id="result_search">[本日の工房利用状況]</div><div id="access_sum"></div>
        <table width="100%" border="1" cellspacing="0" cellpadding="8" id="result_table">
            <tbody>
                <tr>
                    <th>
                         学籍番号
                    </th>
                    <th>
                        氏名
                    </th>
                    <th>
                        目的
                    </th>
                    <th>
                        訪問日時
                    </th>
                </tr>
                <?php
                $i = 0;
                while($row = $stmh->fetch(PDO::FETCH_ASSOC)){
                ?>
                <div id="modal-content-<?= $i ?>" class="modal-content">
                	<p style="display:table">
                    <span style="display: table-row;">氏名 : <span><?=htmlspecialchars($row["name"])?></span></span>
                    <span style="display: table-row;">メールアドレス : <span><?=htmlspecialchars($row["mail"])?></span></span>
                    <span style="display: table-row;">電話番号 : <span><?=htmlspecialchars($row["tel"])?></span></span>
                  </p>
                	<p><a id="modal-close" class="button-link" style="display:inline;">閉じる</a></p>
                </div>
                <tr>
                    <td align="center"><a class="modal-syncer button-link" data-target="modal-content-<?= $i ?>"><?=  htmlspecialchars($row["personalID"])?></a></td>
                    <td align="center"><a class="modal-syncer button-link" data-target="modal-content-<?= $i ?>"><?= htmlspecialchars($row["name"])?></a></td>
                    <td align="center"><a class="modal-syncer button-link" data-target="modal-content-<?= $i ?>"><?=  htmlspecialchars($row["目的"])?></a></td>
                    <td align="center"><a class="modal-syncer button-link" data-target="modal-content-<?= $i ?>"><?= htmlspecialchars($row["rideDate"])?></a></td>
                </tr>
                <?php
                $i++;
                }
                ?>
            </tbody>
        </table>
        <div id="error_report"></div>
        </div>
        <?php
        }
        ?>
        <script src="script/modal-multi.js"></script>
    </body>
</html>
