<?php 
header("content-type:text/html; charset=utf-8");
require('../include/config.php');
require_once('verify.php');

if( isset($_REQUEST["act"]) && !empty($_REQUEST["act"]) )
{
	$act = $_REQUEST["act"];
	if($act=='add')
	{
		$site_name =$_REQUEST['site_name'];
		$site_url =$_REQUEST['site_url'];
		$link = new Link(null);
		$link->add($site_name,$site_url);
		
	}else if($act=='del')
	{
		$id = $_REQUEST["id"];
		$link = new Link($id);
		$link->del();
	}
	header("location:sys_links.php"); 
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
          <li><a href="sys_domain.php">域名列表</a></li>
          <li><a href="sys_domain_add.php">添加域名</a></li>
		  <li><a href="sys_links.php" class="active">链接管理</a></li>
		  <li><a href="sys_config.php">系统配置</a></li>
        </ul>
      </div>
      <div id="main">
               <table cellpadding="0" cellspacing="0">
          <tr class="header">
            <td>站点名称</td>
            <td>网址</td>
            <td class="action">操作</td>
          </tr>
          <?php
$sql = "select id,site_name,site_url from #@__links";
$result = $sqlhelper->query($sql); 
$i=1;
  while($row=mysql_fetch_object($result)){
  $tempStr = "<tr class='odd'>";
  if($i%2==0){
	  $tempStr = "<tr>";
   }
   echo  $tempStr."<td>".$row->site_name."</td>";
   echo  "<td>".$row->site_url."</td>";
   echo  "<td class='action'><a href='sys_links.php?act=del&id=".$row->id."'>删除</a></td>";
   echo  "</tr>";
   $i = $i + 1;
  }
 ?>
        </table>
        <br/>
        <form action="?act=add" method="post">
          <fieldset>
          <p>
            <label>站点名称:</label>
            <input type="text" name="site_name" class="text-long" />
          </p>
          <p>
            <label>网址:</label>
            <input type="text" name="site_url" class="text-long" />
          </p>
          <input type="submit" value="添加" />
          </fieldset>
        </form>
      </div>
      <div class="clear"></div>
    </div>
  </div>
</div>
</body>
</html>
