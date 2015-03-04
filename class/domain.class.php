<?php
require_once('../include/MySqlHelper.php');
require_once('../class/channel.class.php');
require_once('../include/FileUtil.php');
require_once('../include/StringUtil.php');
require_once('../include/zh2py.php');
require_once('../Smarty/libs/Smarty.class.php');
class Domain
{
	var $domain_id;
	var $sqlhelper;
	var $smarty;
	var $fileutil;
	
	var $index_template;
	var $list_template;
	var $article_template;
	var $links;
	var $channels;
	
	var $domain_base;
	
	var $mode = 'product'; //dev product
	
	function __construct($id) {
		$this->domain_id=$id;
		$this->sqlhelper = MySqlHelper::getInstance();
		$this->fileutil = new FileUtil;
    }
	
	//工作模式
	public function setMode($mode)
	{
		$this->mode = $mode;
	}
	
	//设置模板引擎
	public function initSmarty()
	{
		$this->smarty = new Smarty;
		$this->smarty->template_dir =__ROOT__."/templates/";
		$this->smarty->compile_dir =__ROOT__."/templates_c/";//模板编译目录
		$this->smarty->debugging = false;
		$this->smarty->caching = false;
		$this->smarty->cache_lifetime = 120;
		
	}

	// 添加域名
	public function addDomain($domain,$site_name,$seo_title,$seo_keywords,$seo_description,$target_url,$analyze_code,$show_target)
	{
		$array['domain'] =$domain;
		$array['site_name'] =$site_name;
		$array['seo_title'] =$seo_title;
		$array['seo_keywords']=$seo_keywords;
		$array['seo_description'] =$seo_description;
		$array['target_url'] = $target_url;
		$array['analyze_code'] =$analyze_code;
		$array['show_target'] =$show_target;
		$array['spec_code'] =randomkeys();
		$this->sqlhelper->insert('#@__domain',$array);
	}
	
	//使用url编码加密目标地址
	public function encyTargetUrl($target)
	{
		if(strstr($target,'/')==true)
		{
			$url = substr($target,0,strrpos($target,'/')); 
			$ency_str = asc2Url($url);
			$result = str_replace($url,$ency_str,$target);
		}
		else
		{
			$result = asc2Url($target);
		}
		return $result;
	}
	
	//修改
	public function editDomain($domain,$site_name,$seo_title,$seo_keywords,$seo_description,$target_url,$analyze_code,$show_target)
	{
		$array['domain'] =$domain;
		$array['site_name'] =$site_name;
		$array['seo_title'] =$seo_title;
		$array['seo_keywords'] =$seo_keywords;
		$array['seo_description'] =$seo_description;
		$array['target_url'] =$target_url;
		$array['analyze_code'] =$analyze_code;
		$array['show_target'] =$show_target;
		$this->sqlhelper->update('#@__domain',$array,'id='.$this->domain_id);		
	}
	
	//删除
	public function delDomain($domain_id)
	{
		$sql = "delete from #@__domain where id=".$domain_id;
		$this->sqlhelper->query($sql);
	}
	
	//设置默认模板
	public function setDefaultTemplate($domain)
	{
		$sql = "select id from #@__domain where domain='".$domain."'";
		$result = $this->sqlhelper->query($sql);
		$row = mysql_fetch_row($result);
		$id = $row[0];
		$this->setTemplate($id,"default/index.htm","default/list.htm","default/article.htm");
	}
	
	public function setTemplate($domain_id,$index_template,$list_template,$article_template)
	{
		$array['domain_id'] =$domain_id;
		$array['index_template'] =$index_template;
		$array['list_template'] =$list_template;
		$array['article_template'] =$article_template;
		$this->sqlhelper->insert('#@__templates',$array);
	}
	
	//更新.htaccess配置文件
	public function makeHtaccess()
	{
		$sql = "select domain from #@__domain order by id";
		$result = $this->sqlhelper->query($sql);
		while($row=mysql_fetch_object($result)){
			$url = $row->domain;
			$domain = explode('.',$url);
			$temp = '';
			$temp  .= "RewriteCond %{HTTP_HOST} ^(".$domain[0].".)?".$domain[1].".".$domain[2]."$\n";
			$temp  .= "RewriteCond %{REQUEST_URI} !^/".$url."/\n";
			$temp  .= "RewriteCond %{REQUEST_FILENAME} !-f\n";
			$temp  .= "RewriteCond %{REQUEST_FILENAME} !-d\n";
			$temp  .= "RewriteRule ^(.*)$ html/".$url."/$1\n";
			$temp  .= "RewriteCond %{HTTP_HOST} ^(".$domain[0].".)?".$domain[1].".".$domain[2]."$\n";
			$temp  .= "RewriteRule ^(/)?$ html/".$url."/index.html [L]\n\r";			
			$htaccess[] = $temp;
		}
		$filepath = __ROOT__.'/.htaccess';
		$this->smarty->assign('htaccess',$htaccess);
		$htmlContent=$this->smarty->fetch('htaccess.htm');
		$this->fileutil->write($filepath,$htmlContent);
	}
	
	public function init()
	{	
		$this->initSmarty();
		$this->initBase();
		$this->initTemplates();
		$this->initLinks();
		$this->initChannels();
	}
	
	//基本信息
	public function initBase()
	{
		$sql = "select domain,site_name,seo_title,seo_keywords,seo_description,target_url,analyze_code,show_target,spec_code from #@__domain where id=".$this->domain_id;
		$result = $this->sqlhelper->query($sql);
		$row = mysql_fetch_row($result);
		$this->domain_base['domain'] = $row[0];
		$this->domain_base['site_name'] = $row[1];
		$this->domain_base['seo_title'] = $row[2];
		$this->domain_base['seo_keywords'] = $row[3];
		$this->domain_base['seo_description'] = $row[4];
		$this->domain_base['target_url'] = $row[5];
		$this->domain_base['analyze_code'] = $row[6];
		$this->domain_base['show_target'] = $row[7];
		$this->domain_base['spec_code'] = $row[8];
		
		if(!isset($this->domain_base['analyze_code']))
			$this->domain_base['analyze_code'] = '';
		return $this->domain_base;
	}
	//初始化模板 
	private function initTemplates()
	{
		$sql = "select index_template,list_template,article_template from #@__templates where domain_id=".$this->domain_id;
		
		$result = $this->sqlhelper->query($sql);
		$row = mysql_fetch_row($result);
		$this->index_template = $row[0];
		$this->list_template = $row[1];
		$this->article_template = $row[2];
	}
	
	//获得当前所有域名
	private function getDomainLinks()
	{
		$links = array();
		$sql = "select site_name,CONCAT('http://',domain) as site_url from #@__domain";
		$result = $this->sqlhelper->query($sql);
		while($row=mysql_fetch_assoc($result)){
			$links[]=$row;
		}
		return $links;
	}
	
	//初始化友情链接
	private function initLinks()
	{
		$sql = "select site_name,site_url from #@__links";
		$result = $this->sqlhelper->query($sql);
		while($row=mysql_fetch_assoc($result)){
			$this->links[]=$row;
		}
		//数组拼接
		$domainLinks = $this->getDomainLinks();
		if(count($domainLinks)>0)
		{
			$this->links = array_merge($this->links,$domainLinks);
		}
	}
	
	//初始化频道
	public function initChannels()
	{
		$sql = "select id,channel_name,channel_desc,channel_alias from #@__channel where domain_id=".$this->domain_id;
		$result = $this->sqlhelper->query($sql);
		while($row=mysql_fetch_assoc($result)){
    		$items[]=$row;
		}
		$this->channels = $items;
	}
	
	//添加文章分类
	public function addChannel($channel_name,$channel_alias,$channel_desc)
	{
		$array['channel_name'] =$channel_name;
		if(isNotBlank($channel_alias))
		{
			$array['channel_alias'] = $channel_alias;	
		}else
		{
			//转化为拼音
			$array['channel_alias'] = zh2py::lowerConv($channel_name);
		}
		$array['channel_desc'] =$channel_desc;
		$array['domain_id'] =$this->domain_id;
		$array['articles_sum'] = 0;
		$this->sqlhelper->insert('#@__channel',$array);
	}
	
	public function editChannel($channel_id,$channel_desc,$channel_name=null,$channel_alias=null)
	{
		$array['channel_desc'] =$channel_desc;
		if($channel_name!=null)
		{
			$array['channel_name'] =$channel_name;
		}
		if($channel_alias!=null)
		{
			$array['channel_alias'] =$channel_alias;
		}
		$this->sqlhelper->update('#@__channel',$array,'id='.$channel_id);
	}
	
	//删除文章分类
	public function delChannel($channel_id,$delArticles=true)
	{
		//删除静态文件
		$channel = new Channel($channel_id);
		$channel->cleanHtml($this);
		
		$sql = "delete from #@__channel where id=".$channel_id;
		$this->sqlhelper->query($sql);
		//删除文章
		if($delArticles){
			$sql = "delete from #@__articles where channel_id=".$channel_id;
			$this->sqlhelper->query($sql);
		}
		return true;
	}
	
	//生成所有静态文件
	//静态化模式 add 增量静态;all 全部静态
	public function makeHtml($staticType)
	{
		if($this->mode == "product")
		{
			$this->makeHtaccess();
		}
		foreach ($this->channels as $item)
		{
			$channel_id = $item['id'];
			$channel = new Channel($channel_id);
			$channel->setSmarty($this->smarty);
			$channel->setStaticType($staticType);
			$channel->makeHtml($this);
		}
		$this->makeIndexHtml();
		$this->makeTargetJS();
		$this->makeCss();
		$this->makeSitemap();
		$this->makeRobots();
		return true;
	}
	
	//生成首页
	public function makeIndexHtml()
	{
		//首页内容包含前5条文章
		foreach ($this->channels as $item)
		{
			$channel_id = $item['id'];
			$sql = "select title,file_path from #@__articles where channel_id=".$channel_id."  order by id desc limit 5";
			$articles = null;
			$result = $this->sqlhelper->query($sql);
			while($row=mysql_fetch_assoc($result)){
    			$articles[]=$row;
			}
			$index['channels'] = $item;
			if(empty($articles))
			$articles=null;
			
			$index['articles'] = $articles;
			$indexChannel[] = $index;
		}
		
		$filepath = __ROOT__.'/html/'.$this->domain_base['domain'].'/index.html';
		$this->smarty->assign('domain','http://'.$this->domain_base['domain']);
		$this->smarty->assign('links',$this->links);
		$this->smarty->assign('channels',$this->channels);
		$this->smarty->assign('indexChannel',$indexChannel);
		$this->smarty->assign('seo_title',$this->domain_base['seo_title']);
		$this->smarty->assign('seo_keywords',$this->domain_base['seo_keywords']);
		$this->smarty->assign('seo_description',$this->domain_base['seo_description']);
		$this->smarty->assign('show_target',$this->domain_base['show_target']);
		$this->smarty->assign('links',$this->links);
		$this->smarty->assign('analyze_code',$this->domain_base['analyze_code']);
		$this->smarty->assign('spec_code',$this->domain_base['spec_code']);
		$htmlContent=$this->smarty->fetch($this->index_template);
		$this->fileutil->write($filepath,$htmlContent);
	}
	
	//生成目标页面JS代码
	public function makeTargetJS()
	{
		$filepath = __ROOT__.'/html/'.$this->domain_base['domain'].'/'.$this->domain_base['spec_code'].'.js';
		//加密目标地址
		$target_url = $this->encyTargetUrl($this->domain_base['target_url']);
		$this->smarty->assign('target_url',$target_url);
		$htmlContent=$this->smarty->fetch("targetJs.htm");
		$this->fileutil->write($filepath,$htmlContent);
	}
	
	public function makeCss()
	{
		$source =  __ROOT__."/templates/style.css";
		$filepath = __ROOT__.'/html/'.$this->domain_base['domain'].'/style.css';			        $this->smarty->assign('spec_code',$this->domain_base['spec_code']);
		$htmlContent=$this->smarty->fetch($source);
		$this->fileutil->write($filepath,$htmlContent);
	}
	
	//创建sitemap
	public function makeSitemap()
	{
		$domain = $this->domain_base['domain'];
		$filepath = __ROOT__.'/html/'.$domain.'/sitemap.xml';
		$sitemap = new Sitemap();
		$sitemap->create($filepath,$domain,$this->domain_id);
	}
	
	//创建robots.txt
	public function makeRobots()
	{
		$sitemap = 'http://'.$this->domain_base['domain'].'/sitemap.xml';
		$filepath = __ROOT__.'/html/'.$this->domain_base['domain'].'/robots.txt';
		$this->smarty->assign('sitemap',$sitemap);
		$htmlContent=$this->smarty->fetch("robots.htm");
		$this->fileutil->write($filepath,$htmlContent);
	}
	
	
	//删除所有channel以及静态文件
	public function delDomainAll()
	{
		$this->initBase();
		$filepath = __ROOT__.'/html/'.$this->domain_base['domain'];
		if(file_exists($filepath))
		{
			$this->fileutil->delete($filepath);
		}
		$sql = "delete from #@__articles where channel_id in (select id from #@__channel where domain_id =".$this->domain_id.")";
		$this->sqlhelper->query($sql);
		$sql = "delete from #@__channel where domain_id=".$this->domain_id;
		$this->sqlhelper->query($sql);
		$sql = "delete from #@__domain where id=".$this->domain_id;
		$this->sqlhelper->query($sql);
	}	
}
?>
