<?php  
$isLogin = Purview::isLogin();
if(!$isLogin)
{
	header("location:index.php"); 
	exit;
}
?>