<?php  

require('../include/config.php');

$act = _REQ("act");
if(isNotBlank($act))
{
	if($act=='login'){
		$username = $_REQUEST["username"];
		$password = $_REQUEST["password"];
		$purview = new Purview($username);
		if($purview->login($password))
		{
			header("location:sys_domain.php");
			exit; 
		}
		header("location:index.php");
		exit;
	}
	
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>登录</title>
</head>
<body>
<form action="?act=login" method="post">
  用户名<br/>
  <input type="text" name="username" />
  <br/>
  密码<br/>
  <input type="text" name="password" />
  <br/>
  <input type="submit" value="提交" />
</form>
</body>
</html>
