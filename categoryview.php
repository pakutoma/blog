<?php
require_once('activerecord.php');
class CategoryView
{
	public function getPages($category) {
		$blog = new ActiveRecord();
		$blog -> connectPdo('blogdb','categorytable','readonly','readonly');
		$data = $blog -> findFromKey('category',$category);
		$ids = array();
		foreach ($data as $row) {
			$ids[] = $row -> postid;
		}
		$blog -> connectPdo('blogdb','blog','readonly','readonly');
		$result = array();
		foreach ($ids as $pagenum) {
			$result[] = $blog -> find($pagenum);
		}
		return $result;
	}
}
