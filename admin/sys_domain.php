<?php 
header("content-type:text/html; charset=utf-8");
require('../include/config.php');
require_once('verify.php');

if( isset($_REQUEST["act"]) && !empty($_REQUEST["act"]) )
{
	$act = $_REQUEST["act"];
	$id = $_REQUEST["id"];
	if($act=='edit')
	{
		
	}else if($act=='static')
	{
		$domain = new Domain($id);
		$domain->init();
		$result = $domain->makeHtml();
		if($result)
		{
			echo "域名".$domain->domain_base['domain']."静态化成功";
		}
	}else if($act=='delDomain')
	{
		$domain = new Domain($id);
		$domain->delDomainAll();
		header("location:sys_domain.php"); 
		exit;
	}
	header("location:domains.php"); 
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
          <li><a href="sys_domain.php"  class="active">域名列表</a></li>
          <li><a href="sys_domain_add.php">添加域名</a></li>
		  <li><a href="sys_links.php">链接管理</a></li>
		  <li><a href="sys_config.php">系统配置</a></li>
        </ul>
      </div>
      <div id="main">
          <table cellpadding="0" cellspacing="0">
            <tr class="header">
              <td>域名</td>
              <td>名称</td>
              <td>目标站</td>
              <td class="action">操作</td>
            </tr>
            <?php
$sql = "select d.domain,d.site_name,d.target_url,d.id,(select count(*) from #@__channel c where c.domain_id=d.id) channel_count from #@__domain d order by d.id asc";
$result = $sqlhelper->query($sql); 
$i=1;
  while($row=mysql_fetch_object($result)){
    if($i%2==0){
	   echo  "<tr>";
	 }else
	 {
	    echo  "<tr class='odd'>";
	 }
   echo  "<td><a href='domains.php?id=".$row->id."'>".$row->domain."</a></td>";
   echo  "<td>".$row->site_name."</td>";
   echo  "<td>".$row->target_url."</td>";
   echo  "<td class='action'><a href='channels.php?id=".$row->id."'>分类(".$row->channel_count.")</a>&nbsp;<a href='http://".$row->domain."' target='_blank'>预览</a>&nbsp;<a href='http://seo.chinaz.com/?host=".$row->domain."' target='_blank'>SEO</a>&nbsp;<a href='#' onclick=delConfirm('确认删除".str_replace(" ","",$row->domain)."的所有内容','sys_domain.php?act=delDomain&id=".$row->id."')>删除</a></td>";
   echo  "</tr>";
   $i = $i + 1;
  }
 ?>
          </table>
      </div>
      <div class="clear"></div>
    </div>
  </div>
</div>
</body>
</html>
