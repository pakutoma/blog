<?php
require_once('activerecord.php');

class Archive
{
	public function getArchive()
	{
		$blog = new ActiveRecord();
		$blog -> connectPdo('blogdb','blog','readonly','readonly');
		$dates = $blog -> getValueList('date');
		$ym = array();
		foreach ($dates as $date)
		{
			$ym[] = sprintf("%s-%s",explode('/',$date)[0],explode('/',$date)[1]);
		}
		$ym = array_unique($ym);
		asort($ym);
		$result = array();
		foreach (array_reverse($ym) as $date)
		{
			$result[] = array(
				'title' => sprintf("%s年%s月",explode('-',$date)[0],explode('-',$date)[1]),
				'url' => sprintf("/?archive=%s",urlencode($date)),
			);
		}
		return $result;
	}
}
?>
