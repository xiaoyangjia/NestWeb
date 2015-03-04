<?php
/************************************************************************************************
  ** 说明：获取中文拼音首字母
  ** 注意：接受的字符编码为UTF-8，但只转换GB2312字符集内的字符。
  ** 作者：upall
  ** 日期：2010-01-28
  ** ---------------------------------------------------------------------------------------------
  ** @param String $sourcestr 需要处理的字符串
  ** ---------------------------------------------------------------------------------------------
  ** 例子：字符串“很多 很多　的汉字，编码是UTF-8”将返回“HD HD　DHZ，BMSUTF-8”。
  ************************************************************************************************/
//有BUG 例句:优生妈咪乳钙
function pingyinFirstChar($sourcestr){ 
	$returnstr=''; 	
	$i=0; 
	$n=0; 
	$str_length=strlen($sourcestr);//字符串的字节数 
	while ($i<=$str_length) { 
		$temp_str=substr($sourcestr,$i,1); 
		$ascnum=Ord($temp_str);//得到字符串中第$i位字符的ascii码 
		if ($ascnum>=224){  //如果ASCII位高与224，
			$returnstr=$returnstr.getHanziInitial(substr($sourcestr,$i,3)); //根据UTF-8编码规范，将3个连续的字符计为单个字符			
			$i=$i+3;				//实际Byte计为3
		}else if($ascnum>=192){  //如果ASCII位高与192，
			$returnstr=$returnstr.getHanziInitial(substr($sourcestr,$i,2)); //根据UTF-8编码规范，将2个连续的字符计为单个字符 
			$i=$i+2;				//实际Byte计为2
		}else if($ascnum>=65 && $ascnum<=90){  //如果是大写字母，
			$returnstr=$returnstr.substr($sourcestr,$i,1); 
			$i=$i+1;				//实际的Byte数仍计1个
		}else{  //其他情况下，包括小写字母和半角标点符号，
			$returnstr=$returnstr.strtoupper(substr($sourcestr,$i,1));  //小写字母转换为大写
			$i=$i+1;				//实际的Byte数计1个
		} 
	} 
	return $returnstr; 
}

function getHanziInitial($s0){
	if(ord($s0) >= "1" and ord($s0) <= ord("z")){
		return strtoupper($s0);
	}
	$s = iconv("UTF-8", "gb2312//IGNORE", $s0); // 不要转换成GB2312内没有的字符哦，^_^
	$asc = @ord($s{0}) * 256 + @ord($s{1})-65536;
	if($asc >= -20319 and $asc <= -20284)return "A";
	if($asc >= -20283 and $asc <= -19776)return "B";
	if($asc >= -19775 and $asc <= -19219)return "C";
	if($asc >= -19218 and $asc <= -18711)return "D";
	if($asc >= -18710 and $asc <= -18527)return "E";
	if($asc >= -18526 and $asc <= -18240)return "F";
	if($asc >= -18239 and $asc <= -17923)return "G";
	if($asc >= -17922 and $asc <= -17418)return "H";
	if($asc >= -17417 and $asc <= -16475)return "J";
	if($asc >= -16474 and $asc <= -16213)return "K";
	if($asc >= -16212 and $asc <= -15641)return "L";
	if($asc >= -15640 and $asc <= -15166)return "M";
	if($asc >= -15165 and $asc <= -14923)return "N";
	if($asc >= -14922 and $asc <= -14915)return "O";
	if($asc >= -14914 and $asc <= -14631)return "P";
	if($asc >= -14630 and $asc <= -14150)return "Q";
	if($asc >= -14149 and $asc <= -14091)return "R";
	if($asc >= -14090 and $asc <= -13319)return "S";
	if($asc >= -13318 and $asc <= -12839)return "T";
	if($asc >= -12838 and $asc <= -12557)return "W";
	if($asc >= -12556 and $asc <= -11848)return "X";
	if($asc >= -11847 and $asc <= -11056)return "Y";
	if($asc >= -11055 and $asc <= -10247)return "Z";
	return $s0; // 返回原字符，不作转换。（标点、空格、繁体字都会直接返回）
}


function getfirstchar($s0){

        if($fchar>=ord("a") and $fchar<=ord("Z") )return strtoupper($s0{0});
        //$s=iconv("UTF-8","gb2312", $s0);
        $asc=ord($s{0})*256+ord($s{1})-65536;
        if($asc>=-20319 and $asc<=-20284)return "A";
        if($asc>=-20283 and $asc<=-19776)return "B";
        if($asc>=-19775 and $asc<=-19219)return "C";
        if($asc>=-19218 and $asc<=-18711)return "D";
        if($asc>=-18710 and $asc<=-18527)return "E"; 
        if($asc>=-18526 and $asc<=-18240)return "F"; 
        if($asc>=-18239 and $asc<=-17923)return "G"; 
        if($asc>=-17922 and $asc<=-17418)return "I";              
        if($asc>=-17417 and $asc<=-16475)return "J";              
        if($asc>=-16474 and $asc<=-16213)return "K";              
        if($asc>=-16212 and $asc<=-15641)return "L";              
        if($asc>=-15640 and $asc<=-15166)return "M";              
        if($asc>=-15165 and $asc<=-14923)return "N";              
        if($asc>=-14922 and $asc<=-14915)return "O";              
        if($asc>=-14914 and $asc<=-14631)return "P";              
        if($asc>=-14630 and $asc<=-14150)return "Q";              
        if($asc>=-14149 and $asc<=-14091)return "R";              
        if($asc>=-14090 and $asc<=-13319)return "S";              
        if($asc>=-13318 and $asc<=-12839)return "T";              
        if($asc>=-12838 and $asc<=-12557)return "W";              
        if($asc>=-12556 and $asc<=-11848)return "X";              
        if($asc>=-11847 and $asc<=-11056)return "Y";              
        if($asc>=-11055 and $asc<=-10247)return "Z";  
        return null;
}

//输出小写
function lowerPingYin($sourcestr)
{
	return strtolower(pingyinFirstChar($sourcestr));
}
?>