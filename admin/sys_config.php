<?php 
header("content-type:text/html; charset=utf-8");
require('../include/config.php');
require('verify.php');
$act = _REQ("act");
if(isNotBlank($act))
{
	$download = _REQ("download");
	if($act=='backup')
	{
		//下载
		$db = new DBManage();
		$db->backup(null,null,null);
		if($download==1)
		{
			$filename = __ROOT__.'/backup/20130608075700_all_v1.sql';  
			header('Content-type: application/sql');  
			header('Content-Disposition: attachment; filename="nest.sql"'); 
			readfile("$filename"); 
			exit(); 
		}
	}elseif($act=='setTarget')
	{
		$target_url = _REQ("target_url");
		if(isNotBlank($target_url))
		{
			
		}
	}
	header("location:sys_config.php"); 
	exit;
	
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>系统配置</title>
<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/common.js"></script>
<link href="style/css.css" rel="stylesheet" type="text/css" media="screen" />
</head>
<body>
<div id="wrapper">
  <h1><a href="#"><span>管理</span></a></h1>
  <ul id="mainNav">
    <li>
      <select name="domain_id" id="domain_id">
        <option value="">选择域名</option>
        <?php
$sql = "select domain,site_name,seo_title,id from #@__domain";
$result = $sqlhelper->query($sql); 
  while($row=mysql_fetch_object($result)){
 	echo  "<option value='".$row->id."'>".$row->domain."</option>";
  }
 ?>
      </select>
    </li>
    <li><a href="sys_domain.php" class="active">管理</a></li>
    <li class="logout"><a href="logout.php">注销</a></li>
  </ul>
  <div id="containerHolder">
    <div id="container">
      <div id="sidebar">
        <ul class="sideNav">
          <li><a href="sys_domain.php">域名列表</a></li>
          <li><a href="sys_domain_add.php">添加域名</a></li>
          <li><a href="sys_links.php">链接管理</a></li>
          <li><a href="sys_config.php"  class="active">系统配置</a></li>
        </ul>
      </div>
      <div id="main">
        <form action="?act=backup" method="post">
          <fieldset>
          <p>
            <label>数据文件名称:</label>
            <input type="text" name="file_name" class="text-long" value="<?php echo $cfg_admin_domain;?>.sql"/>
            <input type="checkbox" name="download" value="1"/>
            备份并下载 </p>
          <input type="submit" value="备份" />
		  </fieldset>
        </form>
		 <hr />
		  <form action="?act=setTarget" method="post">
          <fieldset>
          <p>
            <label>全局目标站:(所有的站都会指向这个地址，慎用！)</label>
            <input type="text" name="target_url" class="text-long" />
           </p>
          <input type="submit" value="确定" />
          <hr />
		  </fieldset>
        </form>
      </div>
      <div class="clear"></div>
    </div>
  </div>
</div>
</body>
</html>
