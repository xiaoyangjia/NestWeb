<?php 
header("content-type:text/html; charset=utf-8");
require('../include/config.php');
require_once('verify.php');

$id = _REQ("id");
$act = _REQ("act");
if(isNotBlank($id))
{
	$class = new Domain($id);
	$domain_base = $class->initBase();
	if(isNotBlank($act))
	{
		if($act="static")
		{
			$mode  = _REQ("mode");
			$sitemap  = _REQ("sitemap");
			$static_type =_REQ("static_type");
			$class->init();
			if($mode=='dev')
				$class->setMode('dev');
			if($sitemap=='1')
				$class->makeSitemap();
				
			if($static_type=='all')
			{
				$class->makeHtml('all');
			}elseif($static_type=='home')
			{
				$class->makeIndexHtml();
			}
			elseif($static_type=='add')
			{
				$class->makeHtml('add');
			}
			header("location:domains_static.php?id=".$id); 
			exit;
		}
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>文章静态化-<?php echo $domain_base["domain"];?></title>
<link href="style/css.css" rel="stylesheet" type="text/css" media="screen" />
</head>
<body>
<div id="wrapper">
  <h1><a href="#"><span>管理</span></a></h1>
  <ul id="mainNav">
    <li><a href="#" class="active">当前域名:<?php echo $domain_base["domain"];?></a></li>
    <li><a href="sys_domain.php">管理</a></li>
    <li class="logout"><a href="logout.php">注销</a></li>
  </ul>
  <div id="containerHolder">
    <div id="container">
      <div id="sidebar">
        <ul class="sideNav">
          <li><a href="domains.php?id=<?php echo $id;?>">基本信息</a></li>
          <li><a href="channels.php?id=<?php echo $id;?>">文章分类</a></li>
          <li><a href="domains_static.php?id=<?php echo $id;?>"  class="active">生成静态</a></li>
        </ul>
      </div>
      <div id="main">
        <form action="?act=static&id=<?php echo $id;?>" method="post">
          <fieldset>
          <p>
		  <input type="radio" name="static_type" value="add"/>
            增量静态化
            <input checked="checked" type="radio" name="static_type" value="all"/>
            静态化全部
            <input type="radio" name="static_type"  value="home"/>
            静态化首页 </p>
          <p>
            <input type="checkbox" name="mode" value="dev"/>
            开发模式(不生成.htaccess)</p>
          <p>
            <input type="checkbox" name="sitemap" checked="checked" value="1"/>
            生成sitemap</p>
          <input type="submit" value="确定" />
		  <p><a href="http://<?php echo $domain_base["domain"];?>" target="_blank">预览</a>
          </fieldset>
        </form>
      </div>
      <div class="clear"></div>
    </div>
  </div>
</div>
</body>
</html>
