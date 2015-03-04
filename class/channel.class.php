<?php
require_once('../include/MySqlHelper.php');
require_once('../include/StringUtil.php');
require_once('../include/FileUtil.php');
require_once('../class/pager.class.php');

class Channel
{
	var $channel_id;
	var $sqlhelper;
	var $smarty;
	var $fileutil;
	
	var $channel_base;
	var $domain_root; //域名包含http:
	var $channel_root;
	
	//静态化模式 add 增量静态;all 全部静态
	var $staticType='all';
	function __construct($channel_id,$init=true) {
       $this->channel_id = $channel_id;
	   $this->sqlhelper = MySqlHelper::getInstance();
	   $this->fileutil = new FileUtil;
	   if($init){
	   	  $this->initBase();
	   }
    }
	
	private function initBase()
	{
		$sql = "select channel_name,channel_desc,channel_alias from #@__channel where id=".$this->channel_id;
		$result = $this->sqlhelper->query($sql);
		$row = mysql_fetch_row($result);
		$this->channel_base['channel_name'] = $row[0];
		$this->channel_base['channel_desc'] = $row[1];
		$this->channel_base['channel_alias'] = $row[2];
	}
	
	//设置模板引擎
	function setSmarty($smarty)
	{
	 	$this->smarty = $smarty;
	}
	
	//设置静态化模式
	function setStaticType($type)
	{
	 	$this->staticType = $type;
	}
	
	//添加文章
	public function addArticle($title,$content,$artcle_no=null)
	{
		$array['channel_id'] =$this->channel_id;
		$array['title'] =$title;
		$array['content'] =$content;
		if($artcle_no==null)
		{
			$artcle_no = getUUID();
		}
		$array['artcle_no'] = $artcle_no;
		$array['file_path'] = $artcle_no.".html";
		$this->sqlhelper->insert('#@__articles',$array);
		$this->updateArticleSum();
		return true;
	}
	
	//文章总数加1
	private function updateArticleSum()
	{
		$sql = "update #@__channel set articles_sum=articles_sum+1 where id=".$this->channel_id;
		$this->sqlhelper->query($sql);
	}
	
	//删除文档
	public function cleanHtml($domain)
	{
		$filepath = __ROOT__.'/html/'.$domain->domain_base['domain'].'/'.$this->channel_base['channel_alias'];
		if(file_exists($filepath))
		{
			$this->fileutil->delete($filepath);
		}
	}
	
	
	public function makeHtml($domain)
	{
		$this->domain_root = 'http://'.$domain->domain_base['domain'];
		$this->channel_root = $this->domain_root.'/'.$this->channel_base['channel_alias'];
		$this->makeArticleHtml($domain);
		$this->makeListHtml($domain);
	}


	//生成文章列表静态 分页
	public function makeListHtml($domain)
	{ 
		$page_size=30;//每页多少条
		$page_no=1;//当前页码
		
		$sql = "select title,file_path from #@__articles where channel_id=".$this->channel_id." order by id desc";
		$result = $this->sqlhelper->query($sql);
		while($row=mysql_fetch_assoc($result)){
    		$articles[]=$row;
		}
		if(!empty($articles))
		{
			$sum = count($articles);//总数据条数
			$pages = ceil($sum / $page_size);
			$pager = new Pager($page_size,$sum,$this->channel_root."/");//初始化分页器
			$this->smarty->assign('pager',$pager->pagerStr());	
		}else
		{
			$sum = 0;
			$pages = 1;
			$this->smarty->assign('pager',null);
		}
		//生成页面
		$this->smarty->assign('domain',$this->domain_root);
		$this->smarty->assign('channels',$domain->channels);
		$this->smarty->assign('channel_root',$this->channel_root);
		$this->smarty->assign('channel_name',$this->channel_base['channel_name']);
		$this->smarty->assign('seo_title',$domain->domain_base['seo_title']);
		$this->smarty->assign('seo_keywords',$domain->domain_base['seo_keywords']);
		$this->smarty->assign('seo_description',$domain->domain_base['seo_description']);
		$this->smarty->assign('links',$domain->links);
		$this->smarty->assign('analyze_code',$domain->domain_base['analyze_code']);
		$this->smarty->assign('spec_code',$domain->domain_base['spec_code']);
		$this->smarty->assign('show_target',$domain->domain_base['show_target']);
		for($i=1;$i<=$pages;$i++)
		{
			if($i==1)
			{
				$file_path = "index.html";
			}else
			{
				$file_path = "index_".$i.".html";
			}
			if(isset($pager))
			{
				$pager->setPageNo($i);
				$this->smarty->assign('pager',$pager->pagerStr());	
				$this->smarty->assign('article_list',array_slice($articles,($i-1)*$page_size,$page_size));
			}else
			{
				$this->smarty->assign('article_list',null);
			}
			$filepath=__ROOT__.'/html/'.$domain->domain_base['domain'].'/'.$this->channel_base['channel_alias'].'/'.$file_path;
			$htmlContent=$this->smarty->fetch($domain->list_template);
			$this->fileutil->write($filepath,$htmlContent);		
		}
	}

	//生成文章静态
	public function makeArticleHtml($domain)
	{
		$article_template = $domain->article_template;
		$sql = "select id,title,content,file_path from #@__articles where channel_id=".$this->channel_id." order by id desc";
		//如果等于add 表示增量静态化 只提取今天新增加的 但是无法更新前几天的前面几条数据
		if($this->staticType=='add')
		{
			//$sql = "select id,title,content,file_path from #@__articles where DATE_FORMAT(pub_date,'%Y-%m-%d')=CURRENT_DATE() and channel_id=".$this->channel_id." order by id desc";
			$sql = "select id,title,content,file_path from #@__articles where (to_days(now())-to_days(pub_date))<=2 and channel_id=".$this->channel_id." order by id desc";
			
		}
		
		$result = $this->sqlhelper->query($sql);
		while($row=mysql_fetch_object($result)){
    		$filepath=__ROOT__.'/html/'.$domain->domain_base['domain'].'/'.$this->channel_base['channel_alias'].'/'.$row->file_path;
			$nav_url = $this->getPreAndNext($row->id);
			$this->smarty->assign('domain',$this->domain_root);
			$this->smarty->assign('channels',$domain->channels);
			$this->smarty->assign('channel_name',$this->channel_base['channel_name']);
			$this->smarty->assign('title',$row->title);
			$this->smarty->assign('content',$row->content);
			$this->smarty->assign('seo_title',$domain->domain_base['seo_title']);
			$this->smarty->assign('analyze_code',$domain->domain_base['analyze_code']);
			$this->smarty->assign('show_target',$domain->domain_base['show_target']);
			$this->smarty->assign('spec_code',$domain->domain_base['spec_code']);
			
			$this->smarty->assign('pre',$nav_url['pre']);
			$this->smarty->assign('next',$nav_url['next']);
			
			$this->smarty->assign('links',$domain->links);//友情链接
			$this->smarty->assign('article_url',$this->channel_root.'/'.$row->file_path);//文章完整链接
			
			$htmlContent=$this->smarty->fetch($article_template);
			$this->fileutil->write($filepath,$htmlContent);
		}
	}

	//生成上一页 下一页
	public function getPreAndNext($article_id)
	{
		$array['pre'] = null;
		$array['next'] = null;
		//上一条
		$sql = "SELECT file_path,title from #@__articles WHERE channel_id=".$this->channel_id." and id < ".$article_id." ORDER BY id DESC LIMIT 1";
		$result = $this->sqlhelper->query($sql);
		$row = mysql_fetch_row($result);
		if($row!=null)
		{
			$array['pre'] = '<a href="'.$this->channel_root.'/'.$row[0].'">'.$row[1].'</a>';
		}
		
		//下一条
		$sql = "SELECT file_path,title from #@__articles WHERE channel_id=".$this->channel_id." and id > ".$article_id." ORDER BY id ASC LIMIT 1";
		$result = $this->sqlhelper->query($sql);
		$row = mysql_fetch_row($result);
		if($row!=null)
		{
			$array['next'] = '<a href="'.$this->channel_root.'/'.$row[0].'">'.$row[1].'</a>';
		}
		return $array;
	}
	
	//判断channedesc是否为空
	public function isChannelDescEmpty()
	{
		return isNotBlank($this->channel_base['channel_desc']);
	}
	
	//设置分类描述
	public function setChannelDesc($desc)
	{
		$sql = "update #@__channel set channel_desc='".$desc."' where id=".$channel_id;
		$this->sqlhelper->query($sql);
	}
}
?>