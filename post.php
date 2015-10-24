<?php
require_once('activerecord.php');

$ar = new ActiveRecord();
$ar -> connectPdo('blogdb','blog',$_POST['username'],$_POST['password']);
$postid = $ar -> size() + 1;
$ar -> pagenum = $postid;
$ar -> author = $_POST['username'];
$ar -> date = date('Y/m/d');
$ar -> title = $_POST['title'];
$ar -> text = nl2br($_POST['text']);

try {
	$ar -> save();
} catch(Exception $e) {
	echo $e->getMessage();
	return;
}

$ar -> connectPdo('blogdb','categorytable',$_POST['username'],$_POST['password']);
$categories = explode(',',$_POST["category"]);
foreach ($categories as $category) {
	$ar -> postid = $postid;
	$ar -> category = $category;

	try {
		$ar -> save();
	} catch(Exception $e) {
		echo $e->getMessage();
		$ar -> connectPdo('blogdb','blog',$_POST['username'],$_POST['password']);
		$postid = $ar -> size();
		$ar -> delete($postid);
		return;
	}
}

echo "{$postid}番目の記事を投稿しました";

?>
