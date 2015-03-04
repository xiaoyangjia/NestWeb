<?php
require('../include/config.php');
$dedes = array(4,5,6,7);
$channels=array(1,2,3,4);

for($i=0;$i<count($channels);$i++)
{
	$typeid = $dedes[$i];
	$channel_id = $channels[$i];
	getDedeArt($typeid,$channel_id);
}

function getDedeArt($typeid,$channel_id)
{
$sqlhelper = MySqlHelper::getInstance();
$sql = "select id,title,body,senddate from dedeart where typeid=".$typeid;
$result = $sqlhelper->query($sql);
while($row=mysql_fetch_object($result)){
    	$id = $row->id;
		$title = $row->title;
		$content = $row->body;
		$senddate = $row->senddate;
		
		$file_path = getArtName($senddate,$id);
		insertNest($channel_id,$title,$content,$file_path);
		
	}
	
}

function insertNest($channel_id,$title,$content,$file_path)
{
		echo $file_path;
		$channel = new Channel($channel_id);
		return $channel->addArticle($title,$content,$file_path);
}

function getArtName($datestamp,$artid)
{
	$art_date = date('Ymd',$datestamp);
	$tomorrow  = date('Ymd',strtotime($art_date.'+1 day'));
	return $tomorrow.$artid;
}	
?>