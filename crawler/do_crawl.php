<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <body>
<?php
error_reporting(0);
require_once(__DIR__ . "/mod_crawl.php");
require_once(__DIR__ . "/../lib/phpQuery-onefile.php");
set_time_limit(3600);
getCookie(); // サイト内のCOOKIEを取得
$count = 0;

// $cnt = 0; // test

$search_data = $_SERCH_DATA;

$data_count = array(
  60, // 看護師・正社員
  350, // 介護士・正社員
  30, // 看護師・パート
  60, // 介護士・パート
);

// データベースと接続
$con = pg_connect("dbname=crie-re-co-jp01 user=crie-re-co-jp01 password=t9LnpcX3J4CEiQnG");

  foreach ($_CODE_HW_EMPTYPE as $emptype) { // 職種ループ
    $search_data["kyujinShuruiHidden"] = $emptype["id"];
    foreach ($_CODE_OCCUPATION as $occupation) { // 業種ループ
      // echo "職種 : ".$emptype["id"]."<br>";
      // echo "業種 : ".$occupation["id"]."<br>";
// $cnt++;
      $search_data["kiboShokushu1Hidden"] = $occupation["id"];
      $search_data["kiboShokushu1"] = $occupation["id"];

      $url = $_BASE_URL . $_SEARCH_URL;

      $html = getHtml($url, $search_data, $_REF_URL);

      $doc = phpQuery::newDocument($html);
      $all_cnt_str = $doc[".txt90-right"]->text();
      $all_cnt_str = str_replace( "\xc2\xa0", " ", $all_cnt_str );
      $all_cnt = explode(" ",$all_cnt_str);
      $all_page_cnt = ceil($all_cnt[1]/20);

      // 1ページ目
      foreach ($doc[".sole-small #ID_link"] as $value) {
        $data = array();
        $detail_uri = pq($value)->attr("href");
        $detail_uri = str_replace("./", "", $detail_uri);
        $detail_url = $_BASE_URL.$detail_uri;
        $detail_html = getHtml($detail_url, $data, $_REF_URL);
        $detail_doc = phpQuery::newDocument($detail_html);

        $item_arr = array();
        $is_table0 = getField($detail_doc["table:eq(0) tr"][0]);
        $is_table1 = getField($detail_doc["table:eq(1) tr"][0]);

        if(!empty($is_table0)){
          foreach ($detail_doc["table:eq(0) tr"] as $key => $detail_tr) {
            $item_arr[$key]["name"] = pq($detail_tr)->find("th:eq(0)")->text();
            $item_arr[$key]["value"] = pq($detail_tr)->find("td:eq(0)")->text();

            if(strpos($item_arr[$key]["name"],"賃金賃金形態")!==false){
              $item_arr[$key]["name"]="賃金形態";
            }
          }
        } elseif(!empty($is_table1)){
          foreach ($detail_doc["table:eq(1) tr"] as $key => $detail_tr) {
            $item_arr[$key]["name"] = pq($detail_tr)->find("th:eq(0)")->text();
            $item_arr[$key]["value"] = pq($detail_tr)->find("td:eq(0)")->text();

            if(strpos($item_arr[$key]["name"],"賃金賃金形態")!==false){
              $item_arr[$key]["name"]="賃金形態";
            }
          }
        }
        $query = "INSERT INTO hw_crawl (%s) VALUES (%s)";
        $query_fields = "";
        $query_value = "";
        if(!empty($item_arr)){

          foreach ($item_arr as $k => $value) {
            $field = getField($value["name"]);
            if(!empty($field)){
              if($field == "hw_cate"){
                $key++;
                $item_arr[$key]["name"] = "Crie対応業種";
                $item_arr[$key]["value"] = getCrieCategory($value["value"]);
              }elseif($field == "hw_emp_type"){
                $key++;
                $item_arr[$key]["name"] = "Crie対応雇用形態";
                $item_arr[$key]["value"] = getCrieEmpType($value["value"]);
              }elseif($field == "hw_wayside"){
                $key++;
                $item_arr[$key]["name"] = "Crie対応沿線";
                $item_arr[$key]["value"] = getCrieLine($value["value"]);
              }elseif($field == "hw_location"){
                $key++;
                $item_arr[$key]["name"] = "Crie対応勤務地";
                $item_arr[$key]["value"] = getCrieArea($value["value"]);
              }
            }
          }

          $insert_job_flg = array(0,0);
          foreach ($item_arr as $key => $value) {
            $field = getField($value["name"]);
            if($field=="hw_emp_type") {
              if(strpos($value["value"],"パート労働者")!==false || strpos($value["value"],"正社員")!==false) {
                if(strpos($value["value"],"正社員以外")===false){
                  $insert_job_flg[0] = 1;
                }
              }
            }
            if($field=="hw_cate") {
              $crie_cate = getCrieCategory($value["value"]);
              if($crie_cate!=0){
                  $insert_job_flg[1] = 1;
                }
              }
            if(!empty($field)){
              if($key>0){
                $query_fields .= ", ";
                $query_value .= ", ";
              }
              $query_fields .= sprintf(" %s",$field);
              $value["value"] = ltrim($value["value"]);
              $value["value"] = rtrim($value["value"]);

              if($field=="crie_cate"){
                $query_value .= sprintf(" %d", $value["value"]);
              }elseif($field=="crie_emp_type"){
                $query_value .= sprintf(" %d", $value["value"]);
              }elseif($field=="crie_wayside"){
                $query_value .= sprintf(" %d", $value["value"]);
              }elseif($field=="crie_location"){
                $query_value .= sprintf(" %d", $value["value"]);
              } else {
                $query_value .= sprintf(" '%s'", $value["value"]);
              }
            }
          }

          $select_query="SELECT hw_no FROM hw_crawl WHERE hw_no=".$item_arr[0];
          $select_result =  pg_query($con, $select_result);
          $select_num = pg_num_rows($select_result);

          if(!empty($query_fields) && $insert_job_flg[0] && $insert_job_flg[1] && $select_num==0){
            if($con) {
            	// データを登録するためのSQLを作成
            	$query = sprintf($query, $query_fields.", detail_url", $query_value.", '".$detail_url."'");
            	// SQLクエリ実行
// echo $query."<br>";
              $res = pg_query($con, $query);

            	if($res===false){
                echo "データ保存処理において問題が発生しました。システム管理者に確認してください。";
                echo "<br>";
                echo $query;
                echo "<br>";
                exit;
              }
            } else {
            	echo "DBへの接続ができませんでした。システム管理者に確認してください。";
              exit;
            }
            $data_count[$count]--;
            if($data_count[$count]==0) break;
          }
        }
      }

      // 2ページ目以降
      for ($i=2; $i <= $all_page_cnt; $i++) {
        $search_data["nowPageNumberHidden"] = $i;
        $html = getHtml($url, $search_data, $_REF_URL);
        $doc = phpQuery::newDocument($html);

        foreach ($doc[".sole-small #ID_link"] as $value) {
          $data = array();
          $detail_url = pq($value)->attr("href");
          $detail_url = str_replace("./", "", $detail_url);
          $detail_url = $_BASE_URL.$detail_url;
          $detail_html = getHtml($detail_url, $data, $_REF_URL);
          $detail_doc = phpQuery::newDocument($detail_html);

          $item_arr = array();
          $is_table0 = getField($detail_doc["table:eq(0) tr"][0]);
          $is_table1 = getField($detail_doc["table:eq(1) tr"][0]);

          if(!empty($is_table0)){
            foreach ($detail_doc["table:eq(0) tr"] as $key => $detail_tr) {
              $item_arr[$key]["name"] = pq($detail_tr)->find("th:eq(0)")->text();
              $item_arr[$key]["value"] = pq($detail_tr)->find("td:eq(0)")->text();

              if(strpos($item_arr[$key]["name"],"賃金賃金形態")!==false){
                $item_arr[$key]["name"]="賃金形態";
              }
            }
          } elseif(!empty($is_table1)) {
            foreach ($detail_doc["table:eq(1) tr"] as $key => $detail_tr) {
              $item_arr[$key]["name"] = pq($detail_tr)->find("th:eq(0)")->text();
              $item_arr[$key]["value"] = pq($detail_tr)->find("td:eq(0)")->text();

              if(strpos($item_arr[$key]["name"],"賃金賃金形態")!==false){
                $item_arr[$key]["name"]="賃金形態";
              }
            }
          }

          $query = "INSERT INTO hw_crawl (%s) VALUES (%s)";
          $query_fields = "";
          $query_value = "";
          if(!empty($item_arr)){

            foreach ($item_arr as $k => $value) {
              $field = getField($value["name"]);
              if(!empty($field)){
                if($field == "hw_cate"){
                  $key++;
                  $item_arr[$key]["name"] = "Crie対応業種";
                  $item_arr[$key]["value"] = getCrieCategory($value["value"]);
                }elseif($field == "hw_emp_type"){
                  $key++;
                  $item_arr[$key]["name"] = "Crie対応雇用形態";
                  $item_arr[$key]["value"] = getCrieEmpType($value["value"]);
                }elseif($field == "hw_wayside"){
                  $key++;
                  $item_arr[$key]["name"] = "Crie対応沿線";
                  $item_arr[$key]["value"] = getCrieLine($value["value"]);
                }elseif($field == "hw_location"){
                  $key++;
                  $item_arr[$key]["name"] = "Crie対応勤務地";
                  $item_arr[$key]["value"] = getCrieArea($value["value"]);
                }
              }
            }

            $insert_job_flg = array(0,0);
            foreach ($item_arr as $key => $value) {
              $field = getField($value["name"]);
              if($field=="hw_emp_type") {
                if(strpos($value["value"],"パート労働者")!==false || strpos($value["value"],"正社員")!==false) {
                  if(strpos($value["value"],"正社員以外")===false){
                    $insert_job_flg[0] = 1;
                  }
                }
              }
              if($field=="hw_cate") {
                $crie_cate = getCrieCategory($value["value"]);
                if($crie_cate!=0){
                    $insert_job_flg[1] = 1;
                  }
              }
              if(!empty($field)){
                if($key>0){
                  $query_fields .= ", ";
                  $query_value .= ", ";
                }
                $query_fields .= sprintf(" %s",$field);
                $value["value"] = ltrim($value["value"]);
                $value["value"] = rtrim($value["value"]);

                if($field=="crie_cate"){
                  $query_value .= sprintf(" %d", $value["value"]);
                }elseif($field=="crie_emp_type"){
                  $query_value .= sprintf(" %d", $value["value"]);
                }elseif($field=="crie_wayside"){
                  $query_value .= sprintf(" %d", $value["value"]);
                }elseif($field=="crie_location"){
                  $query_value .= sprintf(" %d", $value["value"]);
                } else {
                  $query_value .= sprintf(" '%s'", $value["value"]);
                }
              }
            }

            $select_query="SELECT hw_no FROM hw_crawl WHERE hw_no=".$item_arr[0];
            $select_result =  pg_query($con, $select_result);
            $select_num = pg_num_rows($select_result);

            if(!empty($query_fields) && $insert_job_flg[0] && $insert_job_flg[1] && $select_num==0){
              // データベースと接続
              if($con) {
                // データを登録するためのSQLを作成
                $query = sprintf($query, $query_fields.", detail_url", $query_value.", '".$detail_url."'");
                // SQLクエリ実行
// echo $query."<br>";
                $res = pg_query($con, $query);
                // データベースの接続を切断
                if($res===false){
                  echo "データ保存処理において問題が発生しました。システム管理者に確認してください。";
                  echo "<br>";
                  echo $query;
                  echo "<br>";
                  exit;
                }
              } else {
                echo "DBへの接続ができませんでした。システム管理者に確認してください。";
                exit;
              }
              $data_count[$count]--;
              if($data_count[$count]==0) break;
            }
          }
        }
        if($data_count[$count]==0) break;
      }
      $count++;
      if($data_count[3]==0) break;
    } // 業種ループ
    if($data_count[3]==0) break;
  } // 職種ループ
// var_dump($data_count);
// echo "<br>";
// echo "loop cnt : ".$cnt;
//   exit();
//最新500件を残し、残りを論理削除
  // $query = "SELECT currval('hw_crawl_id') AS currval";
  // $res = pg_query($con, $query);
  // $res_arr = pg_fetch_array($res, NULL, PGSQL_ASSOC);
  // $last_id = $res_arr["currval"];
  // $del_id = $last_id-500;
  //
  // $query = "UPDATE hw_crawl SET del_flg=1 WHERE id<=$del_id";
  // pg_query($con, $query);
exit;
?>
</body>
</html>
