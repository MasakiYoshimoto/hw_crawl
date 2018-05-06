<?php
require_once(__DIR__ . "/common.php");
$error_msg ="";
if(empty($_POST['chk_download'])){
  $error = true;
  $error_msg = "取得するデータを選択してください。";
}else{
  $db = connect_db();
  $target_date = "";

  //フォームからのデータを整形
  foreach ($_POST['chk_download'] as $date) {
    if(!empty($target_date)) $target_date .= ",";
    $target_date .= "'".date("Y-m-d", strtotime($date))."'";
  }
  //データ取得
  $sql = "SELECT
            `code`,`title`,`detail_info`,`address`,
            `occupation`,`emp_status`,`salary`,`certificate`,
            `benefit`,`compensation`,`working_hour`,`holiday`,
            `industry`
          FROM
            `job_ads`
          WHERE
            DATE_FORMAT(create_at,'%Y-%m-%d') IN ({$target_date})
          ORDER BY create_at DESC";
  $res = $db->query($sql);

  //取得フラグ更新
  $update_sql = "UPDATE `job_ads`
                SET downloaded_flg=1
                WHERE DATE_FORMAT(create_at,'%Y-%m-%d') IN ({$target_date})";
  $db->query($update_sql);

  //CSV出力用データ生成
  $dataList = array(
    0 => array(
      "管理番号","仕事タイトル","ポイント","お仕事詳細","勤務地","職種","雇用形態",
      "給与","経験・資格","福利厚生","手当","勤務時間","休日","事業内容")
  );

  while ($row = $res->fetch_assoc()) {
    array_splice($row,2,0,"");
    $dataList[] = $row;
  }

  //CSVデータ出力
  sampleCsv($dataList);

  exit();
}

function sampleCsv($dataList) {

	try {

		//CSV形式で情報をファイルに出力のための準備
		$csvFileName = __DIR__.'/../tmp/' . time() . rand() . '.csv';
		$res = fopen($csvFileName, 'w');
		if ($res === FALSE) {
			throw new Exception('ファイルの書き込みに失敗しました。');
		}

		// ループしながら出力
		foreach($dataList as $dataInfo) {

			// 文字コード変換。エクセルで開けるようにする
			mb_convert_variables('SJIS', 'UTF-8', $dataInfo);

			// ファイルに書き出しをする
			fputcsv($res, $dataInfo);
		}

		// ハンドル閉じる
		fclose($res);

		// ダウンロード開始
		header('Content-Type: application/octet-stream');

		// ここで渡されるファイルがダウンロード時のファイル名になる
		header('Content-Disposition: attachment; filename=hw_list_' . date("Ymd") . '.csv');
		header('Content-Transfer-Encoding: binary');
		header('Content-Length: ' . filesize($csvFileName));
		readfile($csvFileName);

	} catch(Exception $e) {

		// 例外処理をここに書きます
		$error_msg = $e;

	}
}
?>
<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <title>download error</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  </head>
  <body>
    <div class="error_msg"><?=$error_msg?></div>
    <button class="btn-back" type="button" name="button">前のページへ戻る</button>
    <script type="text/javascript">
    $('.btn-back').on('click',function(){
      window.location.href = '/';
    });
    </script>
  </body>
</html>
