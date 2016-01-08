<?php
require_once('activerecord.php');
class LatestPages
{
	public function getPages() {
		$blog = new ActiveRecord();
		$num = $blog -> size();
		$pages = $blog->pickout($num-5,5);
		$result = array();
		for ($i=0; $i < 5; $i++) {
			$result[$i] = array(
				'title' => $pages[4-$i]->title,
				'url' => "/?page={$pages[4-$i]->pagenum}",
			);
		}
		return $result;
	}
}
