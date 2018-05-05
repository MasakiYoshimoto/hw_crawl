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
require_once(__DIR__ . "/common.php");
require_once(__DIR__ . "/../lib/phpQuery-onefile.php");
set_time_limit(3600);
getCookie(); // サイト内のCOOKIEを取得
$count = 0;

$pref_list = getTargetPref();
$work_list = getTargetWork();

$search_data = $_SERCH_DATA;

// データベースと接続
// $con = connect_db();
foreach ($pref_list as $pref) {
  $search_data["todofukenHidden"] = $pref["pref_code"];

  foreach ($work_list as $work) { // 業種ループ
    $search_data["kiboShokushu1Hidden"] = $work["work_code"];
    $search_data["kiboShokushu1"] = $work["work_code"];

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
var_dump($is_table0);
      exit();
    }
exit();
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

      }
    }
  } // 業種ループ
}
exit;
?>
</body>
</html>
