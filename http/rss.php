<?php
    define('SMARTY_DIR', '/usr/share/php/smarty3/');
    require_once('activerecord.php');
    require_once(SMARTY_DIR . 'Smarty.class.php');

    $smarty = new Smarty();
    $smarty->template_dir = '/srv/smarty/templates';
    $smarty->compile_dir = '/srv/smarty/templates_c';
    $smarty->config_dir = '/srv/smarty/configs';
    $smarty->cache_dir = '/srv/smarty/cache';

    $blog = new ActiveRecord();
    $blog -> connectPdo('blogdb','blog','readonly','readonly');
    $num = $blog -> size();
    $pages = $blog->pickout($num-10,10);
    $items = array();
    $category = new ActiveRecord();
    $category -> connectPdo('blogdb','categorytable','readonly','readonly');
    $categories = $category -> find($num - 2) -> category;
    for ($i=0; $i < 10; $i++) {
        $categorystr = $category -> find($num - (9 - $i)) -> category;
        $datetime = new DateTime($pages[9-$i]->date);
        $items[$i] = array(
            'title' => $pages[9-$i]->title,
            'link' => "http://pakutoma.pw/?page={$pages[9-$i]->pagenum}",
            'category' => $categorystr,
            'description' => mb_substr(str_replace("\n"," ",strip_tags($pages[9-$i]->text)),0,40).'...',
            'pubDate' => $datetime->format(DATE_RSS),
        );
    }
    $smarty->assign('items',$items);
    $smarty->display('rss.tpl');
