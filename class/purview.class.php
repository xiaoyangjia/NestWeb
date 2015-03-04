<?php
require_once('../include/MySqlHelper.php');
require_once('../include/StringUtil.php');
require_once('../include/FileUtil.php');

class Purview
{
	var $username;
	var $sqlhelper;
	var $fileutil;
	
	var $channel_base;
	function __construct($username) {
       $this->username = $username;
    }
	
	public static function isLogin()
	{
		session_start();
		return isset($_SESSION['admin']);
	}
	
	//登录验证
	public function login($password)
	{
		if(($password==$GLOBALS['cfg_admin_password'])&&($this->username==$GLOBALS['cfg_admin_username']))
		{
			session_start();
			$_SESSION['admin']=$GLOBALS['cfg_admin_username'];
			return true;
		}
		return false;
	}
}
?>