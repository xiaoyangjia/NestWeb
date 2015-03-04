<?php
define('__ROOT__', dirname(dirname(__FILE__))); 
require_once(__ROOT__.'/include/FileUtil.php');
require_once(__ROOT__.'/include/StringUtil.php');
require_once(__ROOT__.'/include/common.php');
require_once(__ROOT__.'/include/MySqlHelper.php');
require_once(__ROOT__.'/include/phpzip.php');
require_once(__ROOT__.'/include/dbmanage.php');

require_once(__ROOT__.'/class/purview.class.php');
require_once(__ROOT__.'/class/domain.class.php');
require_once(__ROOT__.'/class/channel.class.php');
require_once(__ROOT__.'/class/link.class.php');
require_once(__ROOT__.'/class/sitemap.class.php');

$sqlhelper = MySqlHelper::getInstance();
?>