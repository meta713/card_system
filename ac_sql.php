<?php

//エラー非表示
ini_set('display_errors', 0);

//正当アクセスか確認
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

//post値確認
$check	= $_SERVER['HTTP_X_REQUESTED_WITH'];
$id = $_POST["id"];

$data = ["result"=>"failed"];

if($check && $id && strtolower($check) == 'xmlhttprequest'){
    $_SESSION["m_logintime"] = time();
    switch ($id) {
        case 1:
        {
            $today =$_POST["today"];
            $first = "'" . $today . " 00:00:00'";
            $last = "'" . $today . " 23:59:59'";
            $sql = "select kit_user2.personalID , kit_user2.name , kit_user2.tel , kit_user2.mail , CASE ride_log2.busNo WHEN 'B1' THEN '授業' WHEN 'B2' THEN 'サークル' WHEN 'B3' THEN '研究' WHEN 'B4' THEN 'その他' ELSE '実験' END as '目的' , ride_log2.rideDate from kit_user2 , ride_log2 , id_user2 where ride_log2.rideDate between $first and $last and ride_log2.idm = id_user2.idm and id_user2.userNo = kit_user2.userNo and id_user2.stopFlg = 'A' group by ride_log2.rideDate order by ride_log2.rideDate limit 1000;";
            $sql2 = "select kit_user2.personalID , kit_user2.name , CASE ride_log2.busNo WHEN 'B1' THEN '授業' WHEN 'B2' THEN 'サークル' WHEN 'B3' THEN '研究' WHEN 'B4' THEN 'その他' ELSE '実験' END as '目的' , ride_log2.rideDate from kit_user2 , ride_log2 , id_user2 where ride_log2.rideDate between $first and $last and ride_log2.idm = id_user2.idm and id_user2.userNo = kit_user2.userNo and id_user2.stopFlg = 'A' group by ride_log2.rideDate order by ride_log2.rideDate limit 1000;";
            $th = array("学籍番号","氏名","目的","訪問日時");
            $return_data = search_sql($th, $sql , $sql2);
            $return_data["condition"] = "本日(検索後)";
            //$return_data["index"] = "2";
            echo json_encode($return_data,true);
            exit();
            break;
        }

        case 2:
        {
            $month = $_POST["month"];
            $year = $_POST["year"];
            $month = $year ."-" . $month;
            $cat = $_POST["cat"];
            $first = "'" . date('Y-m-d', strtotime('first day of ' . $month)) . " 00:00:00'";
            $last = "'" . date('Y-m-d', strtotime('last day of ' . $month)) . " 23:59:59'";
            $sql = "select kit_user2.personalID , kit_user2.name , kit_user2.tel , kit_user2.mail , CASE ride_log2.busNo WHEN 'B1' THEN '授業' WHEN 'B2' THEN 'サークル' WHEN 'B3' THEN '研究' WHEN 'B4' THEN 'その他' ELSE '実験' END as '目的' , ride_log2.rideDate from kit_user2 , ride_log2 , id_user2 where ride_log2.rideDate between $first and $last and ride_log2.idm = id_user2.idm and id_user2.userNo = kit_user2.userNo and id_user2.stopFlg = 'A' group by ride_log2.rideDate order by ride_log2.rideDate limit 1000;";
            $sql2 = "select kit_user2.personalID , kit_user2.name , CASE ride_log2.busNo WHEN 'B1' THEN '授業' WHEN 'B2' THEN 'サークル' WHEN 'B3' THEN '研究' WHEN 'B4' THEN 'その他' ELSE '実験' END as '目的' , ride_log2.rideDate from kit_user2 , ride_log2 , id_user2 where ride_log2.rideDate between $first and $last and ride_log2.idm = id_user2.idm and id_user2.userNo = kit_user2.userNo and id_user2.stopFlg = 'A' group by ride_log2.rideDate order by ride_log2.rideDate limit 1000;";
            $th = array("学籍番号","氏名","目的","訪問日時");
            if($cat == "week"){
                $sql = "SELECT case DATE_FORMAT(ride_log2.rideDate, '%w') when 1 then '月' when 2 then '火' when 3 then '水' when 4 then '木' when 5 then '金' else '不明' end as '曜日', COUNT(ride_log2.rideDate) as '利用者数' FROM ride_log2 , id_user2 WHERE ride_log2.idm = id_user2.idm and id_user2.stopFlg = 'A' and ride_log2.rideDate BETWEEN $first AND $last GROUP BY DATE_FORMAT(ride_log2.rideDate, '%w') LIMIT 1000;";
                $sql2 = $sql;
                $th = array("曜日","利用者数");
            }else if($cat == "purpose"){
                $sql = "SELECT CASE ride_log2.busNo WHEN 'B1' THEN '授業' WHEN 'B2' THEN 'サークル' WHEN 'B3' THEN '研究' WHEN 'B4' THEN 'その他' ELSE '実験' END as '利用目的', COUNT(ride_log2.busNo) as '利用者数' FROM ride_log2 , id_user2 where ride_log2.idm = id_user2.idm and id_user2.stopFlg = 'A' and ride_log2.rideDate BETWEEN $first AND $last GROUP BY ride_log2.busNo LIMIT 1000;";
                $sql2 = $sql;
                $th = array("利用目的","利用者数");
            }
            $return_data = search_sql($th, $sql , $sql2);
            if($cat !== ""){
                $return_data["index"] = "1";
            }
            $return_data["condition"] = substr($month, 5 ,2) . "月";
            echo json_encode($return_data,true);
            exit();
            break;
        }

        case 3:
        {
            $year = $_POST["year"];
            $cat = $_POST["cat"];
            $first = "'" . $year . "-01-01 00:00:00'";
            $last = "'" . $year . "-12-31 23:59:59'";
            $sql = "select kit_user2.personalID , kit_user2.name , kit_user2.tel , kit_user2.mail , CASE ride_log2.busNo WHEN 'B1' THEN '授業' WHEN 'B2' THEN 'サークル' WHEN 'B3' THEN '研究' WHEN 'B4' THEN 'その他' ELSE '実験' END as '目的' , ride_log2.rideDate from kit_user2 , ride_log2 , id_user2 where ride_log2.rideDate between $first and $last and ride_log2.idm = id_user2.idm and id_user2.userNo = kit_user2.userNo and id_user2.stopFlg = 'A' group by ride_log2.rideDate order by ride_log2.rideDate limit 1000;";
            $sql2 = "select kit_user2.personalID , kit_user2.name , CASE ride_log2.busNo WHEN 'B1' THEN '授業' WHEN 'B2' THEN 'サークル' WHEN 'B3' THEN '研究' WHEN 'B4' THEN 'その他' ELSE '実験' END as '目的' , ride_log2.rideDate from kit_user2 , ride_log2 , id_user2 where ride_log2.rideDate between $first and $last and ride_log2.idm = id_user2.idm and id_user2.userNo = kit_user2.userNo and id_user2.stopFlg = 'A' group by ride_log2.rideDate order by ride_log2.rideDate limit 1000;";
            $th = array("学籍番号","氏名","目的","訪問日時");
            if($cat == "week"){
                $sql = "SELECT case DATE_FORMAT(ride_log2.rideDate, '%w') when 1 then '月' when 2 then '火' when 3 then '水' when 4 then '木' when 5 then '金' else '不明' end as '曜日', COUNT(ride_log2.rideDate) as '利用者数' FROM ride_log2 , id_user2 WHERE ride_log2.idm = id_user2.idm and id_user2.stopFlg = 'A' and ride_log2.rideDate BETWEEN $first AND $last GROUP BY DATE_FORMAT(ride_log2.rideDate, '%w') LIMIT 1000;";
                $sql2 = $sql;
                $th = array("曜日","利用者数");
            }else if($cat == "purpose"){
                $sql = "SELECT CASE ride_log2.busNo WHEN 'B1' THEN '授業' WHEN 'B2' THEN 'サークル' WHEN 'B3' THEN '研究' WHEN 'B4' THEN 'その他' ELSE '実験' END as '利用目的', COUNT(ride_log2.busNo) as '利用者数' FROM ride_log2 , id_user2 where ride_log2.idm = id_user2.idm and id_user2.stopFlg = 'A' and ride_log2.rideDate BETWEEN $first AND $last GROUP BY ride_log2.busNo LIMIT 1000;";
                $sql2 = $sql;
                $th = array("利用目的","利用者数");
            }
            $return_data = search_sql($th, $sql , $sql2);
            $return_data["condition"] = $year . "年";
            if($cat !== ""){
                $return_data["index"] = "1";
            }
            echo json_encode($return_data,true);
            exit();
            break;
        }

        case 4:
        {
            $first_month = $_POST["first_month"];
            $first_day = $_POST["first_day"];
            $last_month = $_POST["last_month"];
            $last_day = $_POST["last_day"];
            $year = $_POST["year"];
            $cat = $_POST["cat"];
            $first = "'" . $year . "-" . $first_month . "-" . $first_day ." 00:00:00'";
            $last = "'" . $year . "-" . $last_month . "-" . $last_day ." 23:59:59'";
            $sql = "select kit_user2.personalID , kit_user2.name , kit_user2.tel , kit_user2.mail , CASE ride_log2.busNo WHEN 'B1' THEN '授業' WHEN 'B2' THEN 'サークル' WHEN 'B3' THEN '研究' WHEN 'B4' THEN 'その他' ELSE '実験' END as '目的' , ride_log2.rideDate from kit_user2 , ride_log2 , id_user2 where ride_log2.rideDate between $first and $last and ride_log2.idm = id_user2.idm and id_user2.userNo = kit_user2.userNo and id_user2.stopFlg = 'A' group by ride_log2.rideDate order by ride_log2.rideDate limit 1000;";
            $sql2 = "select kit_user2.personalID , kit_user2.name , CASE ride_log2.busNo WHEN 'B1' THEN '授業' WHEN 'B2' THEN 'サークル' WHEN 'B3' THEN '研究' WHEN 'B4' THEN 'その他' ELSE '実験' END as '目的' , ride_log2.rideDate from kit_user2 , ride_log2 , id_user2 where ride_log2.rideDate between $first and $last and ride_log2.idm = id_user2.idm and id_user2.userNo = kit_user2.userNo and id_user2.stopFlg = 'A' group by ride_log2.rideDate order by ride_log2.rideDate limit 1000;";
            $th = array("学籍番号","氏名","目的","訪問日時");
            if($cat == "week"){
                $sql = "SELECT case DATE_FORMAT(ride_log2.rideDate, '%w') when 1 then '月' when 2 then '火' when 3 then '水' when 4 then '木' when 5 then '金' else '不明' end as '曜日', COUNT(ride_log2.rideDate) as '利用者数' FROM ride_log2 , id_user2 WHERE ride_log2.idm = id_user2.idm and id_user2.stopFlg = 'A' and ride_log2.rideDate BETWEEN $first AND $last GROUP BY DATE_FORMAT(ride_log2.rideDate, '%w') LIMIT 1000;";
                $sql2 = $sql;
                $th = array("曜日","利用者数");
            }else if($cat == "purpose"){
                $sql = "SELECT CASE ride_log2.busNo WHEN 'B1' THEN '授業' WHEN 'B2' THEN 'サークル' WHEN 'B3' THEN '研究' WHEN 'B4' THEN 'その他' ELSE '実験' END as '利用目的', COUNT(ride_log2.busNo) as '利用者数' FROM ride_log2 , id_user2 where ride_log2.idm = id_user2.idm and id_user2.stopFlg = 'A' and ride_log2.rideDate BETWEEN $first AND $last GROUP BY ride_log2.busNo LIMIT 1000;";
                $sql2 = $sql;
                $th = array("利用目的","利用者数");
            }
            $return_data = search_sql($th, $sql , $sql2);
            $return_data["condition"] = "2016年" . $first_month . "月" . $first_day . "日 から " ."2016年" . $last_month . "月" . $last_day . "日まで";
            if($cat !== ""){
                $return_data["index"] = "1";
            }
            echo json_encode($return_data,true);
            exit();
            break;
        }

        default:
        {
            echo json_encode($data,true);
            exit();
            break;
        }
    }
    exit();
}

//万一の場合
//echo json_encode($data, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);


//データベースの値取得処理
function search_sql($th , $sql , $sql2){
    require_once 'MYDB.php';

    $data = array("search_time"=>date("Y-m-d H:i:s" , time()),
        "result_exist"=>FALSE);

    //$data["csv"] = setcsv($sql2,$th);
    setcsv($sql,$th);

    $pdo = db_connect();
        try {
            $stmh = $pdo->prepare($sql);
            $stmh->execute();
            $count = $stmh->rowCount();
        } catch (Exception $ex) {
            $data["error"] = $ex->getMessage();
            return $data;
        }

        if($count < 1){
            $data["search_result"] = "検索結果がありません";
            return $data;
        }else{
            $data["search_result"] = "検索結果は $count 件です";
            $data["result_exist"] = TRUE;

            $data["sql_table"] = '<table width="100%" border="1" cellspacing="0" cellpadding="8" id="result_table">'
                    . '<tbody><tr>';

            foreach ($th as $value){
                $data["sql_table"] .= "<th>" . $value . "</th>";
            }

            $data["sql_table"] .= "</tr>";

            //インデックス用の変数
            $i = 0;

            while ($row = $stmh->fetch(PDO::FETCH_ASSOC)){
              if(isset($row["mail"]) && isset($row["tel"])){
                $data["sql_table"] = $data["sql_table"] . "<div id='modal-content-$i' class='modal-content'><p style='display:table'>" .
                "<span style='display:table-row;'>氏名 : <span>".htmlspecialchars($row["name"])."</span></span>" .
                "<span style='display:table-row;'>メールアドレス : <span>".htmlspecialchars($row["mail"])."</span></span>" .
                "<span style='display:table-row;'>電話番号 : <span>".htmlspecialchars($row["tel"])."</span></span></p><p><a id='modal-close' class='button-link' style='display:inline;'>閉じる</a></p></div>";
              }
                $data["sql_table"] .= "<tr>";
                if(isset($row["mail"]) && isset($row["tel"])){
                  foreach ($row as $key => $value){
                    if($key !== "mail" && $key !== "tel")
                      $data["sql_table"] .= '<td align="center"><a class="modal-syncer button-link" data-target="modal-content-' .$i. '">' .htmlspecialchars($value)."</a></td>";
                  }
                }else{
                foreach ($row as $value){
                    $data["sql_table"] .= '<td align="center">'.htmlspecialchars($value)."</td>";
                }
              }
                $data["sql_table"] .= "</tr>";
                $i++;
            }

            $data["sql_table"] .= "</tbody></table>";

            //テスト中
            //$data["csv"] = $stmh->fetchAll();

            return $data;
        }
}

//csv出力用のデータ収集関数
function setcsv($sql,$th){
    $pdo = db_connect();
    try {
            $stmh = $pdo->prepare($sql);
            $stmh->execute();
            $count = $stmh->rowCount();
        } catch (Exception $ex) {
            $data = $ex->getMessage();
            return $data;
        }

        $data = $stmh->fetchAll();

        $file_dir = "../../data/";
        $file_name = "data.csv";

        $file_path = $file_dir.$file_name;

        if(!file_exists($file_path)){
            touch($file_path);
            chmod($file_path,0777);
        }
        chmod($file_path,0777);

        file_put_contents($file_path, "");

        $file = fopen($file_path, "w");
        //fwrite($file, '\xEF\xBB\xBF');

        $title = [];

        foreach ($th as $value){
            $title[] = mb_convert_encoding($value,"SJIS","UTF-8");
            //$title[] = mb_convert_encoding($value,"UTF-8","UTF-8");
        }

        fputcsv($file, $title);

        foreach ($data as $value) {
            $ar = array();
            foreach ($value as $key => $v) {
                if(is_numeric($key)){
                    $ar[] = mb_convert_encoding($v,"SJIS","UTF-8");
                    //$ar[] = mb_convert_encoding($v,"UTF-8","UTF-8");
                }
            }

            fputcsv($file, $ar , ',' ,'"');
        }

        fclose($file);

        return $data;
}
