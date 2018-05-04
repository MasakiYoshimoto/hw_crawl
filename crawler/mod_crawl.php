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
  "kiboSangyo1" => "",
  "kiboSangyo2" => "",
  "kiboSangyo3" => "",
  "chiku1" => "",
  "ensen1_1" => "",
  "ensen1_2" => "",
  "kiboShokushu1" => "",
  "kiboShokushu2" => "",
  "kiboShokushu3" => "",
  "kiboShokushu1Hidden" => "",
  "kiboShokushu2Hidden" => "",
  "kiboShokushu3Hidden" => "",
  "nenrei" => "",
  "license1" => "",
  "license2" => "",
  "license3" => "",
  "gekkyuKagen" => "",
  "teate" => "1",
  "shukyuFutsuka" => "0",
  "nenkanKyujitsu" => "",
  "rdoJkgi" => "9",
  "shugyoJikanKaishiHH" => "",
  "shugyoJikanKaishiMM" => "",
  "shugyoJikanShuryoHH" => "",
  "shugyoJikanShuryoMM" => "",
  "freeWordType" => "0",
  "freeWord" => "",
  "freeWordRuigigo" => "1",
  "notFreeWord" => "",
  "commonSearch" => "検索",
  "fwListNowPage" => "1",
  "fwListLeftPage" => "1",
  "fwListNaviCount" => "11",
  "kyujinShuruiHidden" => "1", // 雇用形態(正社員固定)
  "todofukenHidden" => "", //都道府県
  "teateHidden" => "1",
  "freeWordRuigigoHidden" => "1",
  "actionFlgHidden" => "0",
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
  ini_set('safe_mode', false);

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

  // データベースと接続
  $con = connect_db();

  $sql = "SELECT `pref_code`,`pref` FROM mst_target_pref WHERE target_flg=1";

  $result = $con->query($sql);
  while($row = $result->fetch_assoc()){
    $target_pref[] = $row;
  }
  return $target_pref;
}

/**
 * 対象職種取得
 * @return Array 都道府県情報
 */
function getTargetWork(){
  $target_work = array();

  // データベースと接続
  $con = connect_db();

  $sql = "SELECT `work_code`,`work` FROM mst_target_work WHERE target_flg=1";

  $result = $con->query($sql);
  while($row = $result->fetch_assoc()){
    $target_work[] = $row;
  }
  var_dump($target_work);exit();
  return $target_work;
}

?>
