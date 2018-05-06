<?php
require_once(__DIR__ . "/common.php");
$error_msg ="";
if(empty($_POST['chk_delete'])){
  $error = true;
  $error_msg = "削除するデータを選択してください。";
}else{
  $db = connect_db();
  $target_date = "";

  //フォームからのデータを整形
  foreach ($_POST['chk_delete'] as $date) {
    if(!empty($target_date)) $target_date .= ",";
    $target_date .= "'".date("Y-m-d", strtotime($date))."'";
  }
  //データ取得
  $sql = "UPDATE
            `job_ads`
          SET
            `del_flg`=1
          WHERE
            DATE_FORMAT(create_at,'%Y-%m-%d') IN ({$target_date})";
  $db->query($sql);
  header("location: /");
  exit();
}
?>
<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <title>deleted</title>
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
