<?php
header("content-type:text/html; charset=utf-8");
require_once('../include/config.php');
$action = $_REQUEST["action"];
$code = $_REQUEST["code"];

if($code!=$GLOBALS['cfg_api_verify'])
	exit;
if($action=='postActicle')
{	
	$title = $_REQUEST["title"];
	$content = $_REQUEST["content"];
	$﻿channel_id = $_REQUEST["﻿channel_id"];
	postActicle($﻿channel_id,$title,$content);
}else if($action=='static')
{
	$staticType = $_REQUEST["staticType"];
	makeAllStatic($staticType);
}else if($action=='channel')
{
	echo getChannels();
}
else if($action=='random_channel')
{
	echo getRandomChannels();
}
else if($action=='clean')
{
	cleanSystem();
	echo "清理成功";
}

//添加文章
function postActicle($channel_id,$title,$content)
{	
	//客户端并没有传0值 但不知道为何会变成0 只能这样处理	
	if($channel_id==0){
		$channel_id=1;
	}
	$channel = new Channel($channel_id,false);
	$result = $channel->addArticle($title,$content);
	if($result){
		echo "文章分类编号".$channel_id.":文章发布成功";
	}
}

//获得文章分类
function getChannels()
{
	$sqlhelper = MySqlHelper::getInstance();
	$sql = "select id from #@__channel";
	$result = MySqlHelper::getInstance()->query($sql);
	$temp = "";
	while($row=mysql_fetch_object($result)){
    		$temp .= $row->id.",";
	}
	return $temp;
}

function getRandomChannels()
{
	$sqlhelper = MySqlHelper::getInstance();
	$sql = "select id from #@__domain";
	$result = $sqlhelper->query($sql);
	while($row=mysql_fetch_object($result)){
		$domain_id = $row->id;
		$temp .= getOneChannel($domain_id,$sqlhelper).",";
	}
	return $temp;
}

function getOneChannel($domain_id,$sqlhelper)
{
	$sql = "select id from #@__channel where domain_id=".$domain_id." order by rand() limit 1";
	$result = $sqlhelper->query($sql);
	$row = mysql_fetch_row($result);
	return $row[0];
}
//获得所有DOMIAN
function makeAllStatic($staticType)
{
	$sqlhelper = MySqlHelper::getInstance();
	$sql = "select id from #@__domain";
	$result = $sqlhelper->query($sql);
	while($row=mysql_fetch_object($result)){
		$domain = new Domain($row->id);
		$domain->init();
		$domain->makeHtml($staticType);	
	}
	$str = "全部静态化成功";
	if($staticType=='add')
	{
		$str = "增量静态化成功";
	}
	echo $str;
	
}


//清理
function cleanSystem()
{

	$filepath = __ROOT__.'/html';
	$fileutil = new FileUtil;
	if(file_exists($filepath))
	{
		$fileutil->delete($filepath);
	}
	
	$filepath = __ROOT__.'/.htaccess';
	if(file_exists($filepath))
	{
		$fileutil->delete($filepath);
	}
		
}
?>