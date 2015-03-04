<?php 
header("content-type:text/html; charset=utf-8");
require('../include/config.php');
require_once('verify.php');

if( isset($_REQUEST["act"]) && !empty($_REQUEST["act"]) )
{
	$act = $_REQUEST["act"];
	//保持新域名
	if($act=='save'){
		$domain =$_REQUEST['domain'];
		$site_name =$_REQUEST['site_name'];
		$seo_title =$_REQUEST['seo_title'];
		$seo_keywords=$_REQUEST['seo_keywords'];
		$target_url = $_REQUEST['target_url'];
		$seo_description =$_REQUEST['seo_description'];
		$analyze_code =$_REQUEST['analyze_code'];
		$show_target =$_REQUEST['show_target'];
		if($show_target==null)
			$show_target=0;
		$domain = str_replace('http://','',$domain);
		
		$class = new Domain(null);
		$class->addDomain($domain,$site_name,$seo_title,$seo_keywords,$seo_description,$target_url,$analyze_code,$show_target);
		//设置默认模板
		$class->setDefaultTemplate($domain);
		header("location:sys_domain.php"); 
	}
	exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>管理</title>
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
          <li><a href="sys_domain.php" >域名列表</a></li>
          <li><a href="#" class="active">添加域名</a></li>
          <li><a href="sys_links.php">链接管理</a></li>
          <li><a href="sys_config.php">系统配置</a></li>
        </ul>
      </div>
      <div id="main">
        <form action="?act=save" method="post">
          <fieldset>
          <p>
            <label>域名:</label>
            <input type="text" name="domain" class="text-long" />
          </p>
          <p>
            <label>网站名称:</label>
            <input type="text" name="site_name" class="text-long" />
          </p>
          <p>
            <label>SEO标题:</label>
            <input type="text" name="seo_title" class="text-long" />
          </p>
          <p>
            <label>SEO关键字:</label>
            <input type="text" name="seo_keywords" class="text-long" />
          </p>
          <p>
            <label>SEO描述:</label>
            <textarea rows="3" name="seo_description" cols="50"></textarea>
          </p>
          <p>
            <label>目标网页地址:</label>
            <input type="text" name="target_url" class="text-long" />
            显示:
            <input type="checkbox" name="show_target" value="1" checked="checked"/>
          </p>
          <p>
            <label>统计代码:</label>
            <textarea rows="3" name="analyze_code" cols="50"></textarea>
          </p>
          <input type="submit" value="提交" />
          </fieldset>
        </form>
      </div>
      <div class="clear"></div>
    </div>
  </div>
</div>
</body>
</html>
