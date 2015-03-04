<?PHP
require_once('../include/common.php');
require_once('../include/MySqlHelper.php');
require_once('../include/FileUtil.php');

class Sitemap
{

	var $base='<?xml version="1.0" encoding="UTF-8"?>
<urlset
    xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
       http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
';
	var $fileutil;
	var $sqlhelper;
	function __construct() {
		$this->sqlhelper = MySqlHelper::getInstance();
		$this->fileutil = new FileUtil;
    }
	
	function create($path,$domain,$domain_id)
	{
		$url = "http://".$domain."/";
		$sql = "select date_format(pub_date,'%Y-%m-%d') as lastmod,Concat('".$url."',c.channel_alias,'/',a.file_path) as loc from #@__articles a,#@__channel c where a.channel_id=c.id and c.domain_id=".$domain_id;
		$result = $this->sqlhelper->query($sql);
		$content = '';
		while($row=mysql_fetch_row($result)){
			$content.=$this->create_item($row);
		}
		if(!empty($content)){
			$content =$this->base.$content.'</urlset>';
			$this->fileutil->write($path,$content);
		}
	}

	function create_item($data){
    	$item="<url>\n";
    	$item.="<loc>".$data[1]."</loc>\n";
    	$item.="<lastmod>".$data[0]."</lastmod>\n";
    	$item.="</url>\n";
    	return $item;
	}
}
?>