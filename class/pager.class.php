<?php
class Pager
{
	var $page_size;//每页多少条
	var $page_no;//当前页码
	var $sum;//总数据条数
	
	var $pages;
	var $domain;
	var $url;
	function __construct($page_size,$sum,$url='') {
       $this->page_size = $page_size;
	   $this->sum = $sum;
	   $this->pages = ceil($sum / $page_size);//能分多少页
	   $this->url = $url;
    }
	function setPageNo($page_no)
	{
		$this->page_no = $page_no;
	}
	//生成 1 2 3 类似的分页序列
	function orderStr()
	{
		$orderStr = '';
		//若只有一页 不需要显示任何文字
		if($this->pages==1)
			return $orderstr;
		for($i=1;$i<=$this->pages;$i++)
		{
			if($i==1)
			{
				$temp = '<a href="'.$this->url.'index.html">'.$i.'</a>';
			}else
			{
				$temp = '<a href="'.$this->url.'index_'.$i.'.html">'.$i.'</a>';
			}
			if($i==$this->page_no)
			{
				$temp = '<span class="current">'.$i.'</span>';
			}
			$orderStr = $orderStr.$temp;
		}
		return $orderStr;
	}
	
	function pagerStr()
	{
		$preStr = $nextStr = '';
		if($this->pages==1)
			return $preStr;
			
		$preStr = '<a href="'.$this->url.'index_'.($this->page_no-1).'.html">上一页</a>';
		$preStr = '<a href="'.$this->url.'index_'.($this->page_no+1).'.html" class="next">下一页</a>';
		//如果是第一页 则没有上一页
		if($this->page_no==1)
		{
			$preStr = "";		
		}
		//如果在第二页 则上一页应该是index.html
		if($this->page_no==2)
		{
			$preStr = '<a href="'.$this->url.'index.html">上一页</a>';
		}
		//如果是最后一页 则没有下一页
		if($this->page_no == $this->pages)
		{
			$nextStr = '';
		}
		return $preStr.$nextStr.$this->orderStr();
	}
}
?>