<?php
require_once('activerecord.php');
$ar = new ActiveRecord();
$ar -> connectPdo('blogdb','blog',$_POST['username'],$_POST['password']);
$ar -> delete($_POST['postid']);
$ar -> connectPdo('blogdb','categorytable',$_POST['username'],$_POST['password']);
$ar -> delete($_POST['postid']);
?>
