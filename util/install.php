<?php 
header("Content-Type: text/html; charset=utf-8");
require_once('../include/FileUtil.php');?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>第一步:设置数据库</title>
</head>
<body>
<form action="../?action=setdb" method="post">
  数据库地址:
  <input type="text" name="db_host" />
  <br/>
  数据库名称:
  <input type="text" name="db_name" />
  <br/>
  用户名:
  <input type="text" name="db_username" />
  <br/>
  密码:
  <input type="text" name="db_password" />
  <br/>
  <input type="submit" name="submit" value="下一步" />
</form>
</body>
</html>
<?php
if(!empty($_REQUEST["action"]))
{
	$action = $_REQUEST["action"];
	if($action=='setdb'){
	$db_host = $_REQUEST["db_host"];
	$db_name = $_REQUEST["db_name"];
	$db_username = $_REQUEST["db_username"];
	$db_password = $_REQUEST["db_password"];
	
	$str = "\n\$cfg_db_host='".$db_host."';";
	$str .= "\n\$cfg_db_dbname='".$db_name."';";
	$str .= "\n\$cfg_db_username='".$db_username."';";
	$str .= "\n\$cfg_db_password='".$db_password."';";	
	$content = "<?php".$str."\n?>";
	$filename = "include/common.php";
	$fileutil = new FileUtil();
	$fileutil->write($filename,$content);
	echo "数据库设置成功 <a href='admin/login.php'>进入后台管理</a>";
	}
}

function sqlInit()
{
	$sql = "";
	
}
?>
