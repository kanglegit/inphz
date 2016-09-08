<?php
/**
 * ThinkSAAS单入口
 * copyright (c) 2010-3000 ThinkSAAS All Rights Reserved
 * author QiuJun
 * Email:inphz@qq.com
 */
// 定义网站根目录,APP目录,DATA目录，ThinkSAAS核心目录
define('IN_TS', true);
error_reporting(E_ALL); //E_ALL

//防御CC攻击
date_default_timezone_set('PRC');
function show_shutdown_error() {
 
    $_error = error_get_last();
 
    if ($_error && in_array($_error['type'], array(1, 4, 16, 64, 256, 4096, E_ALL))) {
        echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
        echo '<font color=red>网站程序出错了：</font></br>';
        echo '致命错误:' . $_error['message'] . '</br>';
        echo '文件:' . $_error['file'] . '</br>';
        echo '在第' . $_error['line'] . '行</br>';
        echo '请报告给网站管理员 358276571@qq.com</br>';
    }
}

function log_ip($remote_ip,$real_ip)
{
    $temp_time = date("Y-m-d G:i:s");
    $temp_result = $temp_time.",".$real_ip.",".$remote_ip."\n";
    if(!file_exists("cclogs"))
    {
    mkdir("cclogs");	
    }
    $fhandle=fopen("cclogs/".date("Y-m-d")."_cc_log.txt","a+");
    if($fhandle){
        // print "error";
        // exit;
        fwrite($fhandle,$temp_result);
        fclose($fhandle);
    }

}
function log_ip_count($real_ip)
{
    $temp_time = date("Y-m-d G:i:s");
    $url='http://'.$_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"];
    $temp_result = $temp_time.",".$real_ip.",".$url."\n";
    if(!file_exists("cclogs"))
    {
    mkdir("cclogs");	
    }
    if(!file_exists("cclogs/ips"))
    {
    mkdir("cclogs/ips");	
    }
    
    $fhandle=fopen("cclogs/ips/".$real_ip,"a+");
    if($fhandle){
        fwrite($fhandle,$temp_result);
        fclose($fhandle);
    }
}
function log_ip_deny($real_ip)
{
    $temp_result = "iptables -I INPUT -s ".$real_ip." -j DROP\n";
    if(!file_exists("cclogs"))
    {
   		 mkdir("cclogs");	
    }
    //暂时不开启CC攻击检测功能
     if(!$fhandle=fopen("cclogs/deny_all.sh","a+")){
         print "error";
         exit;
     }
	fwrite($fhandle,$temp_result);
	fclose($fhandle);
}

function get_real_ip()
{
	$ip=false;
	if(!empty($_SERVER["HTTP_CLIENT_IP"])){
	  $ip = $_SERVER["HTTP_CLIENT_IP"];
	}
	if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
	  $ips = explode (", ", $_SERVER['HTTP_X_FORWARDED_FOR']);
	  if($ip){
	   array_unshift($ips, $ip); $ip = FALSE;
	  }
	  for($i = 0; $i < count($ips); $i++){
	   if (!eregi ("^(10|172\.16|192\.168)\.", $ips[$i])){
	    $ip = $ips[$i];
	    break;
	   }
	  }
	}
	return($ip ? $ip : $_SERVER['REMOTE_ADDR']);
}
$realip=get_real_ip();
if($realip<>""){
	$fileName="cclogs/ips/".$realip;
    $remoteip=$_SERVER['REMOTE_ADDR'];
    $filesize=filesize($fileName);

	$mtime=filemtime($fileName);

    log_ip($remoteip,$realip);

    //写入CC记录,并阻止访问
	log_ip_count($realip);
    if($filesize>10000)
    {
    	//记录到屏蔽IP地址里面去
    	//iptables -I INPUT -s ***.***.***.*** -j DROP
    	log_ip_deny($realip);
    }
}
//防御CC攻击结束

register_shutdown_function("show_shutdown_error");
define('THINKROOT', dirname(__FILE__));
define('THINKAPP', THINKROOT . '/app');
define('THINKDATA', THINKROOT . '/data');
define('THINKSAAS', THINKROOT . '/thinksaas');
define('THINKINSTALL', THINKROOT . '/install');
define('THINKPLUGIN', THINKROOT . '/plugins');

// 装载ThinkSAAS核心

include THINKSAAS.'/thinksaas.php';

unset($GLOBALS);
