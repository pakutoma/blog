<?php
define('SMARTY_DIR', '/usr/local/lib/Smarty-3.1.21/libs/');
require_once('activerecord.php');
require_once('latestpages.php');
require_once('category.php');
require_once('archive.php');
require_once('categoryview.php');
require_once('archiveview.php');
require_once(SMARTY_DIR . 'Smarty.class.php');

$smarty = new Smarty();

$smarty->template_dir = '/srv/smarty/templates';
$smarty->compile_dir = '/srv/smarty/templates_c';
$smarty->config_dir = '/srv/smarty/configs';
$smarty->cache_dir = '/srv/smarty/cache';

if (isset($_GET['page'])) {
	$postid = $_GET['page'];
	$blog = new ActiveRecord();
	$blog -> connectPdo('blogdb','blog','readonly','readonly');
	$data = $blog -> find($postid);
	if (!isset($data->pagenum)) {
		echo "(データが)ないです。";
		return;
	}
	$latest = new LatestPages();
	$category = new Category();
	$archive = new Archive();
	$main[] = array(
		'title' => $data -> title,
		'date' => $data -> date,
		'text' => $data -> text,
	);
	$smarty -> assign('title',$data -> title);
	if ($postid + 1 <= $blog -> size()) {
		$smarty -> assign('next',$blog -> find($postid + 1));
	}
	if ($postid - 1 > 0) {
		$smarty -> assign('prev',$blog -> find($postid - 1));
	}
} else if (isset($_GET['category'])) {
	$category = $_GET['category'];
	$categoryview = new CategoryView();
	$data = $categoryview -> getPages($category);
	if (!isset($data[0]->pagenum)) {
		echo "(データが)ないです。";
		return;
	}
	$latest = new LatestPages();
	$category = new Category();
	$archive = new Archive();
	foreach (array_reverse($data) as $page) {
		$main[] = array(
			'title' => $page -> title,
			'date' => $page -> date,
			'text' => $page -> text,
		);
	}
	$smarty -> assign('title','カテゴリ:'.$_GET['category']);
} else if (isset($_GET['archive'])) {
	$archive = $_GET['archive'];
	$archiveview = new ArchiveView();
	$data = $archiveview -> getPages($archive);
	if (!isset($data[0]->pagenum)) {
		echo "(データが)ないです。";
		return;
	}
	$latest = new LatestPages();
	$category = new Category();
	$archive = new Archive();
	foreach (array_reverse($data) as $page) {
		$main[] = array(
			'title' => $page -> title,
			'date' => $page -> date,
			'text' => $page -> text,
		);
	}
	$smarty->assign('title','アーカイブ:'.$_GET['archive']);
} else {
	$blog = new ActiveRecord();
	$blog -> connectPdo('blogdb','blog','readonly','readonly');
	$data = $blog -> find($blog -> size());
	if (!isset($data->pagenum)) {
		echo "(データが)ないです。";
		return;
	}
	$latest = new LatestPages();
	$category = new Category();
	$archive = new Archive();
	$main[] = array(
		'title' => $data -> title,
		'date' => $data -> date,
		'text' => $data -> text,
	);
	$smarty -> assign('prev',$blog -> find($blog -> size() - 1));
}

$smarty->assign('main',$main);
$smarty->assign('latest',$latest -> getPages());
$smarty->assign('category',$category -> getCategory());
$smarty -> assign('archive',$archive -> getArchive());
$smarty->display('blog.tpl');
