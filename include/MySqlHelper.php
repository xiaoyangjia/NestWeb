<?php
/**
 * mysql数据库操作类
 * 
 * @author 周郎(zhoulang),web@zhoulang.net
 * @copyright 岁月联盟 版权所有
 * @link http://www.syue.com  http://bbs.syue.com
 * 
 */

class MySqlHelper {	
	private $link = null;
	private static $_instance = null;

	/**
	 * 私有构造函数
	 *
	 */
	private function __construct() {
	 $this->connect($GLOBALS['cfg_db_host'], $GLOBALS['cfg_db_username'], $GLOBALS['cfg_db_password'], $GLOBALS['cfg_db_dbname']);
	}
	/**
	 * Singleton instance
	 *
	 * @return MySqlHelper
	 */
	public static function getInstance() {
		if(self::$_instance == null) {
			self::$_instance = new self();
		}
		
		return self::$_instance;
	}
	
	/**
	 * 连接mysql
	 * 
	 */
	private function connect($dbhost, $dbuser, $dbpw, $dbname) {
		//连接mysql
		$linkResult = $this->link = mysql_connect($dbhost, $dbuser, $dbpw);
		//如果连接不成功，抛出异常
		if(!$linkResult) {
			throw new Exception('Can not connect to MySQL server', 0);
		}
		//选择mysql数据库
		$dbResult = mysql_select_db($dbname, $this->link);
		//如果连接不成功，抛出异常
		if(!$dbResult) {
			throw new Exception('Could not select database', 0);
		}
		//设定数据库编码为utf8
		mysql_query('SET NAMES UTF8');
	}
	
	/**
	 * 从结果集中取得一行作为关联数组，或数字数组，或二者兼有
	 *
	 */
	public function fetch_array($query, $result_type = MYSQL_ASSOC) {
		return mysql_fetch_array($query, $result_type);
	}
	
	/**
	 * 发送一条 MySQL 查询
	 *
	 */
	public function query($sql) {
		//执行sql
		$query = mysql_query($this->setQuery($sql));
		//如果执行sql，抛出异常
		if(!$query) {
			throw new Exception('Could not query sql', 0);
		}
		return $query;
	}

    /**
	 * 把SQL语句里的#@__替换为$this->dbPrefix(在配置文件中为$cfg_dbprefix)
	 *
	 */
	 function setQuery($sql)
    {
        $prefix="#@__";
        $sql = str_replace($prefix,$GLOBALS['cfg_db_prefix'],$sql);
        return $sql;
    }
	
		/**
	 * 添加一条数据
	 * @param $tablename
	 * @param $array
	 * @return Integer
	 */
	public function insert($tablename, $array)
	{
		return $this->query("INSERT INTO `$tablename`(`".implode('`,`', array_keys($array))."`) VALUES('".implode("','", $array)."')");
	}
	
	/**
	 * 修改一条数据
	 * @param $tablename
	 * @param $array
	 * @param $where
	 * @return Integer
	 */
	public function update($tablename, $array, $where = '')
	{
		if($where)
		{
			$sql = '';
			foreach($array as $k=>$v)
			{
				$sql .= ", `$k`='$v'";
			}
			$sql = substr($sql, 1);
			$sql = "UPDATE `$tablename` SET $sql WHERE $where";
		}
		else
		{
			$sql = "REPLACE INTO `$tablename`(`".implode('`,`', array_keys($array))."`) VALUES('".implode("','", $array)."')";
		}
		return $this->query($sql);
	}
	
	/**
	 * 取得前一次 MySQL 操作所影响的记录行数
	 *
	 */
	public function affected_rows() {
		return mysql_affected_rows($this->link);
	}

	/**
	 * 取得结果集中行的数目
	 *
	 */
	public function num_rows($query) {
		$query = mysql_num_rows($query);
		return $query;
	}

	/**
	 * 取得结果数据
	 *
	 */
	public function result($query, $row = 0) {
		$query = @mysql_result($query, $row);
		return $query;
	}

	/**
	 * 取得结果集中字段的数目
	 *
	 */
	public function num_fields($query) {
		return mysql_num_fields($query);
	}

	/**
	 * 释放结果内存
	 *
	 */
	public function free_result($query) {
		return mysql_free_result($query);
	}

	/**
	 * 取得最后添加时的id编号
	 *
	 */
	public function insert_id() {
		return ($id = mysql_insert_id($this->link)) >= 0 ? $id : $this->result($this->query("SELECT last_insert_id()"), 0);
	}

	/**
	 * 从结果集中取得一行作为枚举数组
	 *
	 */
	public function fetch_row($query) {
		$query = mysql_fetch_row($query);
		return $query;
	}

	/**
	 * 从结果集中取得列信息并作为对象返回
	 *
	 */
	public function fetch_fields($query) {
		return mysql_fetch_field($query);
	}

	/**
	 * 关闭数据库连接
	 *
	 */
	public function close() {
		return mysql_close($this->link);
	}
}
?>