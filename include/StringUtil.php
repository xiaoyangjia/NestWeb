<?php
//随机数
function randomkeys()
{
	$length =5;
	$key='';
 	$pattern='abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLOMNOPQRSTUVWXYZ';
 	for($i=0;$i<$length;$i++)
 	{
   		$key .= $pattern{mt_rand(0,35)};    //生成php随机数
 	}
 	return $key;
}

//唯一ID
function getUUID()
{
	return date("YmdGis").rand(100,999);
}

function _REQ($str)
{
	if(isset($_REQUEST[$str]) && !empty($_REQUEST[$str]))
 	{
  		return trim($_REQUEST[$str]); 
 	}else
	{
		return null;
	}
}
function isNotBlank($str)
{
	if( isset($str) && !empty($str))
		return true;
	return false;
}

//ascii对应的url编码
function asc2Url($str)
{
	$array = str_split($str);
	$temp = '';
	foreach($array as $item)
	{
		$temp .= '%'.dechex(ord($item));
	}
	return $temp;
}

//函数名: compress_html
//参数: $string
//返回值: 压缩后的$string
function compress_html($string) {
    $string = str_replace("\r\n", '', $string); //清除换行符
    $string = str_replace("\n", '', $string); //清除换行符
    $string = str_replace("\t", '', $string); //清除制表符
    $pattern = array (
                    "/> *([^ ]*) *</", //去掉注释标记
                    "/[\s]+/",
                    "/<!--[\\w\\W\r\\n]*?-->/",
                    "/\" /",
                    "/ \"/",
                    "'/\*[^*]*\*/'"
                    );
    $replace = array (
                    ">\\1<",
                    " ",
                    "",
                    "\"",
                    "\"",
                    ""
                    );
    return preg_replace($pattern, $replace, $string);
} 

//下载网页
function httpRequest($url, $file="", $timeout=60) {
    $file = empty($file) ? pathinfo($url,PATHINFO_BASENAME) : $file;
    $dir = pathinfo($file,PATHINFO_DIRNAME);
    !is_dir($dir) && @mkdir($dir,0755,true);
    $url = str_replace(" ","%20",$url);

    if(function_exists('curl_init')) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $temp = curl_exec($ch);
        if(@file_put_contents($file, $temp) && !curl_error($ch)) {
            return $file;
        } else {
            return false;
        }
    } else {
        $opts = array(
            "http"=>array(
            "method"=>"GET",
            "header"=>"",
            "timeout"=>$timeout)
        );
        $context = stream_context_create($opts);
        if(@copy($url, $file, $context)) {
            //$http_response_header
            return $file;
        } else {
            return false;
        }
    }
}

//POST请求
function do_post_request($url, $data, $optional_headers = null)
  {
     $params = array('http' => array(
                  'method' => 'POST',
                  'content' => $data
               ));
     if ($optional_headers !== null) {
        $params['http']['header'] = $optional_headers;
     }
     $ctx = stream_context_create($params);
     $fp = @fopen($url, 'rb', false, $ctx);
     if (!$fp) {
        throw new Exception("Problem with $url, $php_errormsg");
     }
     $response = @stream_get_contents($fp);
     if ($response === false) {
        throw new Exception("Problem reading data from $url, $php_errormsg");
     }
     return $response;
  }
 
//过滤空格
function filter($str)
{
	$str=trim(trim($str,"　"));  
	$result = preg_replace("/(\s+)/",'',$str);
	return $result;
}
?>