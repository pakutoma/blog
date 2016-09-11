<?php
require_once('activerecord.php');

class Archive
{
    public function getArchive()
    {
        $months = $this->getAllMonths();
        foreach ($months as $date) {
            $years[] = $date["year"];
        }
        $years = array_unique($years);
        foreach (array_reverse($years) as $year) {
            $result[$year] = array();
            foreach (array_reverse($months) as $date) {
                if ($year === $date["year"]) {
                    $result[$year][] = $this->createLinkData($date);
                }
            }
        }
        return $result;
    }

    private function createLinkData($date)
    {
        $url = ["archive" => $date["year"] . "-" . $date["month"]];
        return [
            'title' => $date["year"] . "年" . $date["month"] . "月",
            'url' => "/?" . http_build_query($url),
        ];
    }

    private function getAllMonths()
    {
        $blog = new ActiveRecord();
        $blog -> connectPdo('blogdb','blog','readonly','readonly');
        $dates = $blog -> getValueList('date');
        foreach ($dates as $date) {
            $ym[] = explode('/',$date)[0] . "-" . explode('/',$date)[1];
        }
        $ym = array_unique($ym);
        asort($ym);
        foreach ($ym as $date) {
            $result[] = [ 
                "year" => explode('-',$date)[0],
                "month" => explode('-',$date)[1]
            ];
        }
        return $result;
    }
}
