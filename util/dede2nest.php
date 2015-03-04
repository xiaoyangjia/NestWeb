<?php
require_once('../include/config.php');
$sqlhelper = MySqlHelper::getInstance();

/*
$parm = array(4,7);

$channel_id=$parm[0];
$typeid=$parm[1];

$channel = new Channel($channel_id);
$sql = "select title,body from dedeart where typeid=".$typeid;
$result = $sqlhelper->query($sql);
while($row=mysql_fetch_object($result)){
	$title = $row->title;
	$body = $row->body;
	$channel->addArticle($title,$body);
}
*/
//将DEDE的所有域名注入了NEST系统
$domain = new Domain(null);
$sql = "select typename,seotitle,description,keywords from dede_arctype where topid=0";
$result = $sqlhelper->query($sql);
while($row=mysql_fetch_object($result)){
	$typename = $row->typename;
	$seotitle = $row->seotitle;
	$description = $row->description;
	$keywords = $row->keywords;
	$domain->addDomain($typename,$typename,$seotitle,$keywords,$description,null,null,0);
}
?>