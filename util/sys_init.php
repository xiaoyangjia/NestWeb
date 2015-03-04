<?php
require('../include/config.php');
$sqlhelper = MySqlHelper::getInstance();
$domain_id=1;
$domain = new Domain($domain_id);

$reid=1;
$sql = "select id,typename,typedir,content from dede_arctype where  reid=".$reid;
$result = $sqlhelper->query($sql);

while($row=mysql_fetch_object($result)){
    		$id = $row->id;
			$typename = $row->typename;
			$channel_alias = explode('/',$row->typedir);
			$content = $row->content;
			$domain->addChannel($typename,$channel_alias[3],$content);
		}
	

function getTypeArts($typeid)
{
	$sql = "select id,title,body,senddate from dede_archives,dede_addonarticle  where dede_archives.typeid=".$typeid." and dede_addonarticle.aid=dede_archives.id";
	
}

function getArtName($datestamp,$artid)
{
	$art_date = date('Ymd',$datestamp);
	$tomorrow  = date('Ymd',strtotime($art_date.'+1 day'));
	return $tomorrow.$artid;
}	
?>