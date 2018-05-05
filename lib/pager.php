<?php
/**
 * ページャーデータ作成用関数
 * @param  [type] $current_page 現在のページ
 * @param  [type] $total_rec    総レコード数
 * @param  [type] $page_rec     １ページに表示するレコード
 * @param  [type] $show_nav    表示するナビゲーションの数
 * @return [type]               [description]
 */
function pager($current_page, $total_rec, $page_rec=30, $show_nav=5) {
  $pager_data = array(
    "total_page" => 0,
    "path" => "",
    "loop_start" => 0,
    "loop_end" => 0
  );
  $pager_data["total_page"] = ceil($total_rec / $page_rec); //総ページ数
  $pager_data["path"] = '?page=';   //パーマリンク

  //全てのページ数が表示するページ数より小さい場合、総ページを表示する数にする
  if ($pager_data["total_page"] < $show_nav) {
    $show_nav = $pager_data["total_page"];
  }
  //トータルページ数が2以下か、現在のページが総ページより大きい場合表示しない
  if ($pager_data["total_page"] <= 1 || $pager_data["total_page"] < $current_page ) return;
  //総ページの半分
  $show_navh = floor($show_nav / 2);
  //現在のページをナビゲーションの中心にする
  $pager_data["loop_start"] = $current_page - $show_navh;
  $pager_data["loop_end"] = $current_page + $show_navh;
  //現在のページが両端だったら端にくるようにする
  if ($pager_data["loop_start"] <= 0) {
    $pager_data["loop_start"]  = 1;
    $pager_data["loop_end"] = $show_nav;
  }
  if ($pager_data["loop_end"] > $pager_data["total_page"]) {
    $pager_data["loop_start"]  = $pager_data["total_page"] - $show_nav +1;
    $pager_data["loop_end"] =  $pager_data["total_page"];
  }
  return $pager_data;
}
?>
