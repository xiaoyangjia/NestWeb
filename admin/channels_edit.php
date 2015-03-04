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
			$channel_id =$_REQUEST["channel_id"];
			$sum = count($channel_id);
			for($i=0;$i<$sum;$i++)
			{
				$class->editChannel($channel_id[$i],$channel_desc[$i],$channel_name[$i],$channel_alias[$i]);
			}
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
        <form action="?act=add&id=<?php echo $id;?>" method="post">
          <table cellpadding="0" cellspacing="0">
            <tr class="header">
              <td>分类名称</td>
              <td>英文别名</td>
              <td>分类描述</td>
            </tr>
            <?php
$sql = "select id,channel_name,channel_alias,channel_desc from #@__channel where domain_id=".$id;
$result = $sqlhelper->query($sql); 
$i=1;
  while($row=mysql_fetch_object($result)){
  $tempStr = "<tr class='odd'>";
  if($i%2==0){
	  $tempStr = "<tr>";
   }
   	echo  $tempStr.'<td><input type="text" name="channel_name[]" value="'.$row->channel_name.'" class="text-long" /><input type="hidden" name="channel_id[]" value="'.$row->id.'" /></td>';
  	echo '<td><input type="text" name="channel_alias[]" value="'.$row->channel_alias.'" class="text-long" /></td>';
	echo '<td><textarea rows="5" name="channel_desc[]" cols="30">'.$row->channel_desc.'</textarea></td>';
   echo  "</tr>";   
   $i = $i + 1;
  }
 ?>
          </table>
          <input type="submit" value="保存修改" />
        </form>
      </div>
      <div class="clear"></div>
    </div>
  </div>
</div>
</body>
</html>
