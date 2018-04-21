<?php
$_BASE_URL = "https://www.hellowork.go.jp/servicef/";
$_SEARCH_URL = "130050.do";
$_REF_URL = "https://www.hellowork.go.jp/servicef/130020.do?action=initDisp&screenId=130020";
$_CODE_PREF = array(
  array("id" => "13", "name" => "東京都")
);
$_CODE_HW_EMPTYPE = array(
  array("id"=>"1", "name" => "一般（フルタイム）"),
  array("id"=>"2", "name" => "一般（パート）")
);
$_CODE_OCCUPATION = array(
  array("id" => "13", "name" => "保健師、助産師、看護師"),
  array("id" => "36", "name" => "介護サービスの職業")
);
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
  "kyujinShuruiHidden" => "",
  "todofukenHidden" => "13",
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

$CATE_ID_NAME = array(
  1 => "介護職・ヘルパー",
  2 => "管理職・管理職候補",
  3 => "ケアマネージャー",
  4 => "サービス提供責任者",
  5 => "相談員・営業職",
  6 => "看護師",
  7 => "理学療法士・作業療法士",
  8 => "事務職",
  9 => "栄養士",
  10 => "その他"
);

$EMPTYPE_ID_NAME = array(
  1 => "常勤（正社員）",
  2 => "非常勤（パート）",
  3 => "派遣社員",
  4 => "紹介予定派遣",
  5 => "その他",
);

$TERM_ID_NAME = array(
  1 => "長期",
  2 => "短期",
  3 => "単発・その他",
);

function getField($value){
  $field = "";
  if(strpos($value, "求人番号")!==false): $field = "hw_no";
  elseif(strpos($value, "Crie対応業種")!==false): $field = "crie_cate";
  elseif(strpos($value, "Crie対応雇用形態")!==false): $field = "crie_emp_type";
  elseif(strpos($value, "Crie対応沿線")!==false): $field = "crie_wayside";
  elseif(strpos($value, "Crie対応勤務地")!==false): $field = "crie_location";
  elseif(strpos($value, "職種")!==false): $field = "hw_cate";
  elseif(strpos($value, "雇用形態")!==false): $field = "hw_emp_type";
  elseif(strpos($value, "就業時間")!==false): $field = "hw_hours";
  elseif(strpos($value, "賃金形態")!==false): $field = "hw_wage";
  elseif(strpos($value, "週休二日")!==false): $field = "hw_two_holiday";
  elseif(strpos($value, "就業場所")!==false): $field = "hw_location";
  elseif(strpos($value, "沿線")!==false): $field = "hw_wayside";
  elseif(strpos($value, "加入保険等")!==false): $field = "hw_insurance";
  elseif(strpos($value, "通勤手当")!==false): $field = "hw_transportation";
  elseif(strpos($value, "仕事の内容")!==false): $field = "hw_work_contents";
  elseif(strpos($value, "必要な免許・資格")!==false): $field = "hw_license";
  elseif(strpos($value, "事業所名")!==false): $field = "hw_campany_name";
  elseif(strpos($value, "電話番号")!==false): $field = "hw_tel_no";
  elseif(strpos($value, "所在地")!==false): $field = "hw_campany_address";
  elseif(strpos($value, "代表者名")!==false): $field = "hw_organizer";
  endif;

  return $field;
}

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
  return;
}

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

function getCrieCategory($hw_cate){
  $cate = "";
  if(strpos($hw_cate, "看護スタッフ")!==false): $cate = 6;
  elseif(strpos($hw_cate, "看護師")!==false): $cate = 6;
  elseif(strpos($hw_cate, "看護職員")!==false): $cate = 6;
  elseif(strpos($hw_cate, "医療的ケア")!==false): $cate = 6;
  elseif(strpos($hw_cate, "看護業務")!==false): $cate = 6;
  elseif(strpos($hw_cate, "看護職")!==false): $cate = 6;
  elseif(strpos($hw_cate, "看護のお仕事")!==false): $cate = 6;
  elseif(strpos($hw_cate, "訪問看護")!==false): $cate = 6;
  elseif(strpos($hw_cate, "看護助手")!==false): $cate = 6;
  elseif(strpos($hw_cate, "看護パート")!==false): $cate = 6;
  elseif(strpos($hw_cate, "外来透析看護")!==false): $cate = 6;
  elseif(strpos($hw_cate, "看護夜勤パート")!==false): $cate = 6;
  elseif(strpos($hw_cate, "ヘルパー")!==false): $cate = 1;
  elseif(strpos($hw_cate, "夜勤専門スタッフ")!==false): $cate = 1;
  elseif(strpos($hw_cate, "介護スタッフ")!==false): $cate = 1;
  elseif(strpos($hw_cate, "介護職員")!==false): $cate = 1;
  elseif(strpos($hw_cate, "ホームヘルパー")!==false): $cate = 1;
  elseif(strpos($hw_cate, "リビングスタッフ")!==false): $cate = 1;
  elseif(strpos($hw_cate, "介護員")!==false): $cate = 1;
  elseif(strpos($hw_cate, "パートヘルパー")!==false): $cate = 1;
  elseif(strpos($hw_cate, "介護")!==false): $cate = 1;
  elseif(strpos($hw_cate, "介護ヘルパー")!==false): $cate = 1;
  elseif(strpos($hw_cate, "介護職")!==false): $cate = 1;
  elseif(strpos($hw_cate, "ケアスタッフ")!==false): $cate = 1;
  elseif(strpos($hw_cate, "ケアマネージャー")!==false): $cate = 1;
  elseif(strpos($hw_cate, "訪問ヘルパー")!==false): $cate = 1;
  elseif(strpos($hw_cate, "生活支援員")!==false): $cate = 1;
  elseif(strpos($hw_cate, "介護士")!==false): $cate = 1;
  elseif(strpos($hw_cate, "生活サポート職員")!==false): $cate = 1;
  elseif(strpos($hw_cate, "訪問介護")!==false): $cate = 1;
  elseif(strpos($hw_cate, "介護業務")!==false): $cate = 1;
  elseif(strpos($hw_cate, "介護補助")!==false): $cate = 1;
  elseif(strpos($hw_cate, "介護福祉士")!==false): $cate = 1;
  elseif(strpos($hw_cate, "グループホームスタッフ")!==false): $cate = 1;
  elseif(strpos($hw_cate, "ケアワーカー")!==false): $cate = 1;
  elseif(strpos($hw_cate, "介護パート")!==false): $cate = 1;
  elseif(strpos($hw_cate, "ケアアシスタント")!==false): $cate = 1;
  elseif(strpos($hw_cate, "介護助手")!==false): $cate = 1;
  else: $cate =0;
  endif;

  return $cate;
}

function getCrieArea($hw_area){
  $area = "";
  if(strpos($hw_area, "千代田区")!==false): $area = 1;
  elseif(strpos($hw_area, "中央区")!==false): $area = 2;
  elseif(strpos($hw_area, "港区")!==false): $area = 3;
  elseif(strpos($hw_area, "新宿区")!==false): $area = 4;
  elseif(strpos($hw_area, "文京区")!==false): $area = 5;
  elseif(strpos($hw_area, "台東区")!==false): $area = 6;
  elseif(strpos($hw_area, "墨田区")!==false): $area = 7;
  elseif(strpos($hw_area, "江東区")!==false): $area = 8;
  elseif(strpos($hw_area, "品川区")!==false): $area = 9;
  elseif(strpos($hw_area, "目黒区")!==false): $area = 10;
  elseif(strpos($hw_area, "大田区")!==false): $area = 11;
  elseif(strpos($hw_area, "世田谷区")!==false): $area = 12;
  elseif(strpos($hw_area, "渋谷区")!==false): $area = 13;
  elseif(strpos($hw_area, "中野区")!==false): $area = 14;
  elseif(strpos($hw_area, "杉並区")!==false): $area = 15;
  elseif(strpos($hw_area, "豊島区")!==false): $area = 16;
  elseif(strpos($hw_area, "北区")!==false): $area = 17;
  elseif(strpos($hw_area, "荒川区")!==false): $area = 18;
  elseif(strpos($hw_area, "板橋区")!==false): $area = 19;
  elseif(strpos($hw_area, "練馬区")!==false): $area = 20;
  elseif(strpos($hw_area, "足立区")!==false): $area = 21;
  elseif(strpos($hw_area, "葛飾区")!==false): $area = 22;
  elseif(strpos($hw_area, "江戸川区")!==false): $area = 23;
  elseif(strpos($hw_area, "三鷹市")!==false): $area = 24;
  elseif(strpos($hw_area, "武蔵野市")!==false): $area = 25;
  elseif(strpos($hw_area, "小金井市")!==false): $area = 26;
  elseif(strpos($hw_area, "国分寺市")!==false): $area = 27;
  elseif(strpos($hw_area, "調布市")!==false): $area = 28;
  elseif(strpos($hw_area, "府中市")!==false): $area = 29;
  elseif(strpos($hw_area, "狛江市")!==false): $area = 30;
  elseif(strpos($hw_area, "立川市")!==false): $area = 31;
  elseif(strpos($hw_area, "国立市")!==false): $area = 32;
  elseif(strpos($hw_area, "八王子市")!==false): $area = 33;
  elseif(strpos($hw_area, "日野市")!==false): $area = 34;
  elseif(strpos($hw_area, "昭島市")!==false): $area = 35;
  elseif(strpos($hw_area, "多摩市")!==false): $area = 36;
  elseif(strpos($hw_area, "稲城市")!==false): $area = 37;
  elseif(strpos($hw_area, "川崎市多摩区")!==false): $area = 38;
  elseif(strpos($hw_area, "川崎市麻生区")!==false): $area = 39;
  elseif(strpos($hw_area, "相模原市")!==false): $area = 40;
  elseif(strpos($hw_area, "町田市")!==false): $area = 41;
  elseif(strpos($hw_area, "川崎市高津区")!==false): $area = 42;
  elseif(strpos($hw_area, "川崎市中原区")!==false): $area = 43;
  elseif(strpos($hw_area, "福生市")!==false): $area = 44;
  elseif(strpos($hw_area, "青梅市")!==false): $area = 45;
  elseif(strpos($hw_area, "羽村市")!==false): $area = 46;
  elseif(strpos($hw_area, "あきる野市")!==false): $area = 47;
  elseif(strpos($hw_area, "武蔵村山市")!==false): $area = 48;
  elseif(strpos($hw_area, "小平市")!==false): $area = 49;
  elseif(strpos($hw_area, "東大和市")!==false): $area = 50;
  elseif(strpos($hw_area, "東村山市")!==false): $area = 51;
  elseif(strpos($hw_area, "西東京市")!==false): $area = 52;
  elseif(strpos($hw_area, "東久留米市")!==false): $area = 53;
  elseif(strpos($hw_area, "清瀬市")!==false): $area = 54;
  elseif(strpos($hw_area, "埼玉県")!==false): $area = 55;
  elseif(strpos($hw_area, "神奈川県")!==false): $area = 56;
  elseif(strpos($hw_area, "千葉県")!==false): $area = 57;
  endif;
  return $area;
}

function getCrieLine($hw_line){
  $line = "";
  if(strpos($hw_line, "京王・京王高尾線")!==false): $line = 1;
  elseif(strpos($hw_line, "八王子・京王八王子駅")!==false): $line = 1;
  elseif(strpos($hw_line, "橋本駅")!==false): $line = 1;
  elseif(strpos($hw_line, "京王相模原線")!==false): $line = 2;
  elseif(strpos($hw_line, "調布駅")!==false): $line = 2;
  elseif(strpos($hw_line, "永山駅")!==false): $line = 2;
  elseif(strpos($hw_line, "小田急小田原（新宿〜町田）・多摩線")!==false): $line = 3;
  elseif(strpos($hw_line, "中央・総武線（高円寺以西）")!==false): $line = 4;
  elseif(strpos($hw_line, "吉祥寺駅")!==false): $line = 4;
  elseif(strpos($hw_line, "立川・立川北・立川南駅")!==false): $line = 4;
  elseif(strpos($hw_line, "国分寺駅")!==false): $line = 4;
  elseif(strpos($hw_line, "西国分寺駅")!==false): $line = 4;
  elseif(strpos($hw_line, "武蔵境駅")!==false): $line = 4;
  elseif(strpos($hw_line, "高尾駅")!==false): $line = 4;
  elseif(strpos($hw_line, "南武線")!==false): $line = 5;
  elseif(strpos($hw_line, "東急田園都市線・こどもの国線")!==false): $line = 6;
  elseif(strpos($hw_line, "横浜線")!==false): $line = 7;
  elseif(strpos($hw_line, "西武多摩川線")!==false): $line = 9;
  elseif(strpos($hw_line, "武蔵野線（東京）")!==false): $line = 10;
  elseif(strpos($hw_line, "秋津・新秋津駅")!==false): $line = 10;
  elseif(strpos($hw_line, "多摩都市モノレール")!==false): $line = 11;
  elseif(strpos($hw_line, "多摩川上水駅")!==false): $line = 11;
  elseif(strpos($hw_line, "多摩センター駅")!==false): $line = 11;
  elseif(strpos($hw_line, "青梅・五日市線")!==false): $line = 17;
  elseif(strpos($hw_line, "拝島駅")!==false): $line = 17;
  elseif(strpos($hw_line, "西武池袋・有楽町・豊島園線")!==false): $line = 18;
  elseif(strpos($hw_line, "秋津・新秋津駅")!==false): $line = 18;
  elseif(strpos($hw_line, "西武新宿（東京）・拝島・国分寺・多摩湖・西武園線")!==false): $line = 19;
  elseif(strpos($hw_line, "京王井の頭線")!==false): $line = 20;
  elseif(strpos($hw_line, "吉祥寺駅")!==false): $line = 20;
  elseif(strpos($hw_line, "小田急小田原（新宿〜町田）・多摩線")!==false): $line = 21;
  elseif(strpos($hw_line, "永山駅")!==false): $line = 21;
  elseif(strpos($hw_line, "町田駅")!==false): $line = 21;
  endif;

  return $line;
}

function getCrieEmpType($hw_emptype){
  $emptype = "";
  if(strpos($hw_emptype, "正社員")!==false): $emptype = 1;
  elseif(strpos($hw_emptype, "パート労働者")!==false): $emptype = 2;
  else: $emptype = 0;
  endif;
  return $emptype;
}
?>
