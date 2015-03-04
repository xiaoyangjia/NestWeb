<?php
require_once('../include/MySqlHelper.php');

class Link
{
	var $id;
	var $sqlhelper;
	var $fileutil;
	
	function __construct($id) {
       $this->id = $id;
	   $this->sqlhelper = MySqlHelper::getInstance();
	   $this->fileutil = new FileUtil;
    }
	
	//添加链接
	public function add($site_name,$site_url)
	{
		$site_url = str_replace('http://','',$site_url);
		$site_url = "http://".$site_url;
		
		$array['site_name'] =$site_name;
		$array['site_url'] = $site_url;
		$this->sqlhelper->insert('#@__links',$array);
		return true;
	}
	
	//删除链接
	public function del()
	{
		$sql = "delete from #@__links where id=".$this->id;
		$this->sqlhelper->query($sql);
		return true;
	}
}
?>