<?php
require_once(__DIR__ . "/../lib/common.php");
require_once(__DIR__ . "/mod_crawl.php");

$path = __DIR__ . "/do_crawl.php";

$db = connect_db();

foreach ($cate_pref as $key => $value) {
  //全ての都道府県を対象外とする
  $sql = "UPDATE mst_target_pref SET target_flg=0";
  $db->query($sql);

  $sql = "UPDATE mst_target_pref SET target_flg=1 WHERE pref_code='{$key}'";
  $db->query($sql);

  $res = system("nohup /usr/local/php7.1/bin/php '{$path}' > /dev/null &");

  sleep(3);
}

echo "以下のファイルをバックグラウンドで実行しました。<br>";
echo $path;
?>
