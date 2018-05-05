<?php
// error_reporting(0);
require_once(__DIR__ . "/mod_crawl.php");
require_once(__DIR__ . "/../lib/common.php");
require_once(__DIR__ . "/../lib/phpQuery-onefile.php");
set_time_limit(0);
getCookie(); // サイト内のCOOKIEを取得
$max_count = 500; // 取得最大件数
$search_data = $_SERCH_DATA;

$loop_cnt_arr = getLoopCount($max_count);

$pref_list = getTargetPref(); //対象都道府県取得
$work_list = getTargetWork(); //対象職種取得

foreach ($pref_list as $pref) { // 都道府県ループ
  $search_data["todofukenHidden"] = $pref["pref_code"];

  foreach ($work_list as $work) { // 業種ループ
    $search_data["kiboShokushu1Hidden"] = $work["work_code"];
    $search_data["kiboShokushu1"] = $work["work_code"];
    $search_data["commonSearch"] = "検索";
    $count = 0;
    $count_code = $cate_pref[$pref["pref_code"]]."-".$cate_work[$work["work_code"]];

    $url = $_BASE_URL . $_SEARCH_URL;

    $html = getHtml($url, $search_data, $_REF_URL);
    $doc = phpQuery::newDocument($html);
    $all_cnt_str = $doc[".txt90-right"]->text();
    $all_cnt_str = str_replace( "\xc2\xa0", " ", $all_cnt_str );
    $all_cnt = explode(" ",$all_cnt_str);
    $all_page_cnt = ceil($all_cnt[1]/20);

    // 1ページ目 //////////////////////////////////////////
    foreach ($doc[".sole-small #ID_link"] as $value) {
      $data = array();
      $detail_uri = pq($value)->attr("href");
      $detail_uri = str_replace("./", "", $detail_uri);
      $detail_url = $_BASE_URL.$detail_uri;
      $detail_html = getHtml($detail_url, $data, $_REF_URL);
      $detail_doc = phpQuery::newDocument($detail_html);

      //詳細ページ内の項目ごとにデータを抽出
      $item_arr = array();
      $is_table0 = $detail_doc["table:eq(0) tr"][0];
      $is_table1 = $detail_doc["table:eq(1) tr"][0];

      if(!empty($is_table0)){
        foreach ($detail_doc["table:eq(0) tr"] as $detail_tr) {
          $base_field = pq($detail_tr)->find("th:eq(0)")->text();
          if(strpos($base_field,"賃金賃金形態")!==false) $base_field = "賃金形態";
          $field = getField($base_field);
          if(!empty($field)){
            $item_arr[$field]["name"] = $base_field;
            $item_arr[$field]["value"] = trim(pq($detail_tr)->find("td:eq(0)")->text());
            if(strpos($base_field, "職種")!==false){
              $field = "occupation";
              $item_arr[$field]["name"] = $base_field;
              $item_arr[$field]["value"] = trim(pq($detail_tr)->find("td:eq(0)")->text());
            }
          }
        }
      } elseif(!empty($is_table1)){
        foreach ($detail_doc["table:eq(1) tr"] as $detail_tr) {
          $base_field = pq($detail_tr)->find("th:eq(0)")->text();
          if(strpos($base_field,"賃金賃金形態")!==false) $base_field = "賃金形態";
          $field = getField($base_field);
          if(!empty($field)){
            $item_arr[$field]["name"] = $base_field;
            $item_arr[$field]["value"] = trim(pq($detail_tr)->find("td:eq(0)")->text());
            if(strpos($base_field, "職種")!==false){
              $field = "occupation";
              $item_arr[$field]["name"] = $base_field;
              $item_arr[$field]["value"] = trim(pq($detail_tr)->find("td:eq(0)")->text());
            }
          }
        }
      }
      //データが存在しなければ登録処理
      if(chkExistData($item_arr)){
        insAdsRecode($item_arr);
        $count++;
        if($count == $loop_cnt_arr[$count_code]) break;
      }
    }
    // 1ページ目 //////////////////////////////////////////

    // 2ページ目以降 //////////////////////////////////////
    for ($i=2; $i <= $all_page_cnt; $i++) {
      unset($search_data["commonSearch"]);
      if(!empty($j))unset($search_data["fwListNaviBtn".$j]);
      $j = $i;
      $search_data["fwListNaviBtn".$i] = (String)$i;
      $html = getHtml($url, $search_data, $url);

      $doc = phpQuery::newDocument($html);

      foreach ($doc[".sole-small #ID_link"] as $value) {
        $data = array();
        $detail_uri = pq($value)->attr("href");
        $detail_uri = str_replace("./", "", $detail_uri);
        $detail_url = $_BASE_URL.$detail_uri;
        $detail_html = getHtml($detail_url, $data, $_REF_URL);
        $detail_doc = phpQuery::newDocument($detail_html);

        $item_arr = array();
        $is_table0 = $detail_doc["table:eq(0) tr"][0];
        $is_table1 = $detail_doc["table:eq(1) tr"][0];

        if(!empty($is_table0)){
          foreach ($detail_doc["table:eq(0) tr"] as $detail_tr) {
            $base_field = pq($detail_tr)->find("th:eq(0)")->text();
            if(strpos($base_field,"賃金賃金形態")!==false) $base_field = "賃金形態";
            $field = getField($base_field);
            if(!empty($field)){
              $item_arr[$field]["name"] = $base_field;
              $item_arr[$field]["value"] = trim(pq($detail_tr)->find("td:eq(0)")->text());
              if(strpos($base_field, "職種")!==false){
                $field = "occupation";
                $item_arr[$field]["name"] = $base_field;
                $item_arr[$field]["value"] = trim(pq($detail_tr)->find("td:eq(0)")->text());
              }
            }
          }
        } elseif(!empty($is_table1)){
          foreach ($detail_doc["table:eq(1) tr"] as $detail_tr) {
            $base_field = pq($detail_tr)->find("th:eq(0)")->text();
            if(strpos($base_field,"賃金賃金形態")!==false) $base_field = "賃金形態";
            $field = getField($base_field);
            if(!empty($field)){
              $item_arr[$field]["name"] = $base_field;
              $item_arr[$field]["value"] = trim(pq($detail_tr)->find("td:eq(0)")->text());
              if(strpos($base_field, "職種")!==false){
                $field = "occupation";
                $item_arr[$field]["name"] = $base_field;
                $item_arr[$field]["value"] = trim(pq($detail_tr)->find("td:eq(0)")->text());
              }
            }
          }
        }

        //データが存在しなければ登録処理
        if(chkExistData($item_arr)){
          insAdsRecode($item_arr);
          $count++;
          if($count == $loop_cnt_arr[$count_code]) break;
        }

      }
    }
    // 2ページ目以降 //////////////////////////////////////
  } // 業種ループ
} // 都道府県ループ
?>
