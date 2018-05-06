<?php
require_once(__DIR__ . "/common.php");

if(empty($_POST['chk_download'])){
  $error = true;
  $error_msg = "取得するデータを選択してください。";
}else{
  $sql = "SELECT
            `code`,`title`,`detail_info`,`address`,
            `occupation`,`emp_status`,`salary`,`certificate`,
            `benefit`,`compensation`,`working_hour`,`holiday`,
            `industry`
          FROM
            `job_ads`
          WHERE
            update_at=";


  // sampleCsv();
}




function sampleCsv() {

	try {

		//CSV形式で情報をファイルに出力のための準備
		$csvFileName = __DIR__.'/../tmp/' . time() . rand() . '.csv';
		$res = fopen($csvFileName, 'w');
		if ($res === FALSE) {
			throw new Exception('ファイルの書き込みに失敗しました。');
		}

		// データ一覧。この部分を引数とか動的に渡すようにしましょう
		$dataList = array(
			array('hogehoge','mogemoge','mokomoko','aaa'),
			array('ddd','sss','eeeeee','ffff'),
		);

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
		echo $e;

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
