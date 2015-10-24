<?php
require_once('activerecord.php');
class Category
{
	public function getCategory()
	{
		$blog = new ActiveRecord();
		$blog -> connectPdo('blogdb','categorytable','readonly','readonly');
		$categories = $blog -> getValueList('category');
		$result = array();
		foreach (array_reverse($categories) as $category)
		{
			$result[] = array(
				'title' => $category,
				'url' => sprintf("/?category=%s",urlencode($category)),
			);
		}
		return $result;
	}
}
?>
