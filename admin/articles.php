<?php 
header("content-type:text/html; charset=utf-8");
require('../include/config.php');
require('../include/page.php');
require_once('verify.php');

$id = _REQ("id");
$act = _REQ("act");
$channel_id = _REQ("channel_id");
if(isNotBlank($id))
{
	$class = new Domain($id);
	$domain_base = $class->initBase();
	if(isNotBlank($act))
	{
		if($act=="add")
		{
			$channel_name =$_REQUEST['channel_name'];
			$channel_alias =$_REQUEST['channel_alias'];
			$channel_desc =$_REQUEST['channel_desc'];
			$class->addChannel($channel_name,$channel_alias,$channel_desc);
		}
		else if($act=="batAdd")
		{	
			$channel_name =$_REQUEST['channel_name'];
			$channel = explode('|',$channel_name);
			foreach($channel as $str){
				$class->addChannel($str,null,'');
			}
		}
		else if($act=="del")
		{	
			$channel_id = _REQ("channel_id");
			$class->delChannel($channel_id);
		}
		//header("location:channels.php?id=".$id); 
		//exit;
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>文章管理-<?php echo $domain_base["domain"];?></title>
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
          <li><a href="channels.php?id=<?php echo $id;?>"  class="active">文章分类</a></li>
          <li><a href="domains_static.php?id=<?php echo $id;?>">生成静态</a></li>
        </ul>
      </div>
      <div id="main">
        <table cellpadding="0" cellspacing="0">
          <tr class="header">
            <td>标题</td>
            <td>发布时间</td>
            <td class="action">操作</td>
          </tr>
          <?php
$sql = "select title,id,pub_date as pubdate from #@__articles where channel_id = ".$channel_id;
$result = $sqlhelper->query($sql); 
$i=1;
  while($row=mysql_fetch_object($result)){
  $tempStr = "<tr class='odd'>";
  if($i%2==0){
	  $tempStr = "<tr>";
   }
   echo  $tempStr."<td>".$row->title."</td>";
   echo  "<td>".$row->pubdate."</td>";
   echo  "<td><a href='articles.php?art_id=".$id."&act=del&channel_id=".$row->id."'>删</a></td>";
   echo  "</tr>";
   $i = $i + 1;
  }
 ?>
          <tr>
            <td colspan="3" align="center"></td>
          </tr>
        </table>
        <br/>
        <form action="?act=add&id=<?php echo $id;?>" method="post">
          <fieldset>
          <p>
            <label>标题:</label>
            <input type="text" name="title" class="text-long" />
          </p>
          <p>
            <label>内容:</label>
            <textarea rows="6" name="content" cols="70"></textarea>
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
