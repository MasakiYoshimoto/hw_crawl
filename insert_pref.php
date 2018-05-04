<?php
$pref_arr = array(
  9=>"建築・土木・測量技術者",
  70=>"建設躯体の職業",
  71=>"建設の職業",
  72=>"電気工事の職業",
  73=>"土木の職業"
);

$mysqli = new mysqli('mysql107.phy.lolipop.lan', 'LAA0520286', 'jfjwxa', 'LAA0520286-hw');
$mysqli->set_charset('utf8');

foreach ($pref_arr as $key => $value) {
  $sql = "INSERT INTO `mst_target_work` (`work_code`, `work`, `target_flg`)
          VALUES ({$key}, '{$value}', 1)";
  $result = $mysqli->query($sql);
}

?>
finish!
