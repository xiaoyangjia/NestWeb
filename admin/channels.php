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
			header("location:channels_edit.php?id=".$id); 
			exit;
		}
		else if($act=="del")
		{	
			$channel_id = _REQ("channel_id");
			$class->delChannel($channel_id);
		}
		header("location:channels.php?id=".$id); 
		exit;
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>文章分类-<?php echo $domain_base["domain"];?></title>
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
            <td>分类名称</td>
            <td>英文别名</td>
			<td>文章数</td>
            <td>分类描述</td>
            <td class="action">操作</td>
          </tr>
          <?php
$sql = "select id,channel_name,channel_alias,articles_sum,channel_desc from #@__channel where domain_id=".$id;
$result = $sqlhelper->query($sql); 
$i=1;
  while($row=mysql_fetch_object($result)){
  $tempStr = "<tr class='odd'>";
  if($i%2==0){
	  $tempStr = "<tr>";
   }
   echo  $tempStr."<td>".$row->channel_name."</td>";
   echo  "<td>".$row->channel_alias."</td>";
   echo  "<td>".$row->articles_sum."</td>";
   if(strlen($row->channel_desc)>0)
   {
   	 echo  "<td>已填写</td>";
   }else
   {
   	 echo  "<td>未填写</td>";
   }
   echo  "<td><a href='channels.php?id=".$id."&act=del&channel_id=".$row->id."'>删</a>|<a href='channels_edit.php?id=".$id."&channel_id=".$row->id."'>修</a>|<a href='articles.php?id=".$id."&channel_id=".$row->id."' target='_blank'>文</a></td>";
   echo  "</tr>";
  // echo $tempStr."<td  colspan='4'>".$row->channel_desc."</td></tr>";
   
   $i = $i + 1;
  }
 ?>
        </table>
        <br/>
        <form action="?act=add&id=<?php echo $id;?>" method="post">
          <fieldset>
          <p>
            <label>分类名称:</label>
            <input type="text" name="channel_name" class="text-long" />
          </p>
          <p>
            <label>英文别名:</label>
            <input type="text" name="channel_alias" class="text-long" />
          </p>
          <p>
            <label>分类描述:</label>
            <textarea rows="4" name="channel_desc" cols="70"></textarea>
          </p>
          <input type="submit" value="添加" />
          </fieldset>
        </form>
		批量增加<br/>
		<form action="?act=batAdd&id=<?php echo $id;?>" method="post">
          <fieldset>
          <p>
            <label>分类名称:</label>
            <input type="text" name="channel_name" class="text-long" />"|"分割
          </p>
          <input type="submit" value="批量增加" />
          </fieldset>
        </form>
      </div>
      <div class="clear"></div>
    </div>
  </div>
</div>
</body>
</html>
