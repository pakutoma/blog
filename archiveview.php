<?php
require_once('activerecord.php');
class ArchiveView
{
	public function getPages($archive)
	{
		$blog = new ActiveRecord();
		$blog -> connectPdo('blogdb','blog','readonly','readonly');
		$result = $blog -> findLike('date',sprintf("%s/%s",explode('-',$archive)[0],explode('-',$archive)[1]).'%');
		$pagenum_arr = array();
		foreach ($result as $page)
		{
			$pagenum_arr[] = $page -> pagenum;
		}
		array_multisort($pagenum_arr,SORT_ASC,$result);
		return $result;
	}
}
?>
