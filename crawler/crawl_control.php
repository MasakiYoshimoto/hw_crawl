<?php
$path = __DIR__ . "/do_crawl.php";

$res = system("nohup /usr/local/php7.1/bin/php '{$path}' > /dev/null &", $return_ver);
echo "以下のファイルをバックグラウンドで実行しました。<br>";
echo $path;
?>
