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
	$url = "domains.php?id=".$id;
	if(isNotBlank($act))
	{
		if($act="update")
		{
		$domain =$_REQUEST['domain'];
		$site_name =$_REQUEST['site_name'];
		$seo_title =$_REQUEST['seo_title'];
		$seo_keywords=$_REQUEST['seo_keywords'];
		$target_url = $_REQUEST['target_url'];
		$seo_description =$_REQUEST['seo_description'];
		$analyze_code =$_REQUEST['analyze_code'];
		$show_target =_REQ('show_target');
		if($show_target==null)
			$show_target=0;
		$domain = str_replace('http://','',$domain);
		
		$class->editDomain($domain,$site_name,$seo_title,$seo_keywords,$seo_description,$target_url,$analyze_code,$show_target);
		header("location:".$url); 
		exit;
		}
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>管理-<?php echo $domain_base["domain"];?></title>
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
          <li><a href="<?php echo $url;?>" class="active">基本信息</a></li>
          <li><a href="channels.php?id=<?php echo $id;?>">文章分类</a></li>
          <li><a href="domains_static.php?id=<?php echo $id;?>">生成静态</a></li>
        </ul>
      </div>
      <div id="main">
        <form action="?act=update&id=<?php echo $id;?>" method="post">
          <fieldset>
          <p>
            <label>域名:</label>
            <input type="text" name="domain" class="text-long" value="<?php echo $domain_base["domain"];?>"/>
            <a href="http://<?php echo $domain_base["domain"];?>" target="_blank">预览</a> </p>
          <p>
            <label>网站名称:</label>
            <input type="text" name="site_name" class="text-long" value="<?php echo $domain_base["site_name"];?>"/>
          </p>
          <p>
            <label>SEO标题:</label>
            <input type="text" name="seo_title" class="text-long"  value="<?php echo $domain_base["seo_title"];?>"/>
          </p>
          <p>
            <label>SEO关键字:</label>
            <input type="text" name="seo_keywords" class="text-long"  value="<?php echo $domain_base["seo_keywords"];?>"/>
          </p>
          <p>
            <label>SEO描述:</label>
            <textarea rows="2" name="seo_description" cols="60"><?php echo $domain_base["seo_description"];?></textarea>
          </p>
          <p>
            <label>目标网页地址:</label>
            <input type="text" name="target_url" class="text-long" value="<?php echo $domain_base["target_url"];?>"/>
            显示:
            <?php 
				$check='';
				if($domain_base["show_target"]==1) 
				{
					$check = " checked='checked' ";
				}
			?>
            <input type="checkbox" <?php echo $check;?> name="show_target" value="1" />
          </p>
          <p>
            <label>统计代码:</label>
            <textarea rows="8" name="analyze_code" cols="60"><?php echo $domain_base["analyze_code"];?></textarea>
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
