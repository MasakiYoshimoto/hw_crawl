<?php
require_once(__DIR__ . "/common.php");
$_BASE_URL = "https://www.hellowork.go.jp/servicef/";
$_SEARCH_URL = "130050.do";
$_REF_URL = "https://www.hellowork.go.jp/servicef/130020.do?action=initDisp&screenId=130020";
$_SERCH_DATA = array(
  "kyushokuNumber1" => "",
  "kyushokuNumber2" => "",
  "kyushokuUmu" => "2",
  "jigyoshomei" => "",
  "kiboSangyo1" => "D",
  "kiboSangyo2" => "",
  "kiboSangyo3" => "",
  "chiku1" => "",
  "ensen1_1" => "",
  "ensen1_2" => "",
  "kiboShokushu1" => "",
  "kiboShokushu2" => "",
  "kiboShokushu3" => "",
  "nenrei" => "",
  "license1" => "",
  "license2" => "",
  "license3" => "",
  "gekkyuKagen" => "",
  "teate" => "1",
  "koyoKeitai" => "1",
  "shukyuFutsuka" => "0",
  "nenkanKyujitsu" => "",
  "rdoJkgi" => "9",
  "shugyoJikanKaishiHH" => "",
  "shugyoJikanKaishiMM" => "",
  "shugyoJikanShuryoHH" => "",
  "shugyoJikanShuryoMM" => "",
  "freeWordType" => "0",
  "freeWord" => "現場管理 監理 監督 技術 設計 施工図 プラント",
  "freeWordRuigigo" => "1",
  "notFreeWord" => "厨房",
  "fwListNowPage" => "1",
  "fwListLeftPage" => "1",
  "fwListNaviCount" => "11",
  "kyujinShuruiHidden" => "1",
  "todofukenHidden" => "",
  "kyushokuUmuHidden" => "2",
  "kiboSangyo1Hidden" => "D",
  "kiboShokushu1Hidden" => "",
  "teateHidden" => "1",
  "koyoKeitaiHidden" => "1",
  "shukyuFutsukaHidden" => "0",
  "rdoJkgiHidden" => "9",
  "freeWordHidden" => "現場管理 監理 監督 技術 設計 施工図 プラント",
  "freeWordRuigigoHidden" => "1",
  "freeWordTypeHidden" => "0",
  "notFreeWordHidden" => "厨房",
  "actionFlgHidden" => "1",
  "nowPageNumberHidden" => "1",
  "screenId" => "130050",
  "action" => "",
  "codeAssistType" => "",
  "codeAssistKind" => "",
  "codeAssistCode" => "",
  "codeAssistItemCode" => "",
  "codeAssistItemName" => "",
  "codeAssistDivide" => "",
  "codeAssistRankLimit" => "",
  "xab_vrbs" => "commonNextScreen,detailJokenChangeButton,commonDetailInfo,commonSearch,commonDelete"
);

$cate_pref = array(
  '11' => 'A','12' => 'A',
  '13' => 'A','14' => 'A',
  '26' => 'B','27' => 'B',
  '28' => 'B','29' => 'B'
);

$cate_work = array(
  '09' => 'B','70' => 'J1',
  '71' => 'J2','72' => 'J3',
  '73' => 'J4'
);

$count_code_percent = array(
  "A-B" => 0.40,
  "A-J1" => 0.03,
  "A-J2" => 0.03,
  "A-J3" => 0.03,
  "A-J4" => 0.03,
  "C-B" => 0.40,
  "C-J1" => 0.03,
  "C-J2" => 0.03,
  "C-J3" => 0.03,
  "C-J4" => 0.03,
);

/**
 * サイトクッキー取得
 */
function getCookie(){
  global $_REF_URL;
  //クッキー取得のためのアクセス
  $curl=curl_init();//初期化
  curl_setopt($curl,CURLOPT_URL,$_REF_URL);//cookieを取りに行くURL
  curl_setopt($curl,CURLOPT_HEADER,false);//httpヘッダ情報は表示しない
  curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);//データをそのまま出力
  curl_setopt($curl,CURLOPT_COOKIEJAR, __DIR__ . '/cookie.txt');//$cookieから取得した情報を保存するファイル名
  curl_setopt($curl,CURLOPT_FOLLOWLOCATION,true);//Locationヘッダの内容をたどっていく
  curl_exec($curl);
  curl_close($curl);//いったん終了
}

/**
 * HTML情報取得
 * @param  String $url  取得ページURL
 * @param  Array  $data POSTデータ
 * @param  String $ref  遷移元ページ
 * @return Object $html 取得ページ
 */
function getHtml($url, $data, $ref){

  $curl = curl_init();
  curl_setopt($curl,CURLOPT_URL,$url);
  curl_setopt($curl,CURLOPT_POST, true);
  if(!empty($data)){
    curl_setopt($curl,CURLOPT_POSTFIELDS, http_build_query($data));
  }
  curl_setopt($curl,CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($curl,CURLOPT_SSL_VERIFYHOST, false);
  curl_setopt($curl,CURLOPT_RETURNTRANSFER, true);
  curl_setopt($curl,CURLOPT_COOKIEJAR, __DIR__ . '/cookie.txt');//$cookieから取得した情報を保存するファイル名
  curl_setopt($curl,CURLOPT_COOKIEFILE, __DIR__ . '/cookie.txt');
  curl_setopt($curl,CURLOPT_FOLLOWLOCATION, true);
  curl_setopt($curl,CURLOPT_AUTOREFERER, $ref);
  curl_setopt($curl,CURLOPT_USERAGENT, 'Mozilla/5.0');
  $html = curl_exec($curl);
  curl_close($curl);

  return $html;
}


/**
 * 対象都道府県取得
 * @return Array 都道府県情報
 */
function getTargetPref(){
  $target_pref = array();
  $con = connect_db();//Db接続

  $sql = "SELECT `pref_code`,`pref` FROM mst_target_pref WHERE target_flg=1";
  $result = $con->query($sql);
  while($row = $result->fetch_assoc()){
    $target_pref[] = $row;
  }
  return $target_pref;
}


/**
 * 対象職種取得
 * @return Array 職種情報
 */
function getTargetWork(){
  $target_work = array();
  $con = connect_db();//Db接続

  $sql = "SELECT `work_code`,`work` FROM mst_target_work WHERE target_flg=1";
  $result = $con->query($sql);

  while($row = $result->fetch_assoc()){
    $row['work_code'] = sprintf("%02d", $row['work_code']);
    $target_work[] = $row;
  }

  return $target_work;
}

/**
 * ハローワークの項目とサイト項目の置き換え
 * @param  String $value ハローワーク項目名
 * @return String $field サイト内項目名(DB)
 */
function getField($value){
  $field = "";
  if(strpos($value, "求人番号")!==false): $field = "code";
  elseif(strpos($value, "職種")!==false): $field = "title";
  elseif(strpos($value, "仕事の内容")!==false): $field = "detail_info";
  elseif(strpos($value, "就業場所")!==false): $field = "address";
  elseif(strpos($value, "職種")!==false): $field = "occupation";
  elseif(strpos($value, "雇用形態")!==false): $field = "emp_status";
  elseif(strpos($value, "賃金形態")!==false): $field = "salary";
  elseif(strpos($value, "必要な免許・資格")!==false): $field = "certificate";
  elseif(strpos($value, "加入保険等")!==false): $field = "benefit";
  elseif(strpos($value, "通勤手当")!==false): $field = "compensation";
  elseif(strpos($value, "就業時間")!==false): $field = "working_hour";
  elseif(strpos($value, "週休二日")!==false): $field = "holiday";
  elseif(strpos($value, "産業")!==false): $field = "industry";
  endif;

  return $field;
}


function chkExistData($ads_info){
  if(empty($ads_info['code']['value'])) return false;
  $con = connect_db();//Db接続
  $sql = "SELECT count(id) cnt FROM job_ads WHERE code='{$ads_info['code']['value']}'";
  $sql_res = $con->query($sql);
  $res = $sql_res->fetch_assoc();

  if($res["cnt"]>0){
    return false;
  } else {
    return true;
  }
}

function insAdsRecode($ads_info){
  $con = connect_db();//Db接続
  $sql_strings = makeSqlStringsByArray($ads_info, "insert");
  $sql = "INSERT INTO job_ads ({$sql_strings['fields']}) VALUES ({$sql_strings['values']})";
  $con->query($sql);
}

function makeSqlStringsByArray($array, $sql_type){
  $sql_strings = array("fields" => "", "values" => "");

  switch ($sql_type) {
    case 'insert':
      foreach ($array as $key => $data) {
        if(!empty($sql_strings["fields"])) $sql_strings["fields"] .= ",";
        if(!empty($sql_strings["values"])) $sql_strings["values"] .= ",";
        $sql_strings["fields"] .= "`".$key."`";
        $sql_strings["values"] .= "'".$data["value"]."'";
      }
      break;
  }
  return $sql_strings;
}

function getLoopCount($max_count){
  global $count_code_percent;
  foreach ($count_code_percent as $key => $value) {
    $loop_cnt_arr[$key] = $max_count*$value;
  }
  return $loop_cnt_arr;
}
?>
