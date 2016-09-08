<?php   define("APPID", "wx848a34fbbf98d75e"); 
define("APPSECRET", "b911cb26201718e4025ea8154d597bfb");  
$token_access_url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . APPID . "&secret=" . APPSECRET; $res = file_get_contents($token_access_url); 
 //获取文件内容或获取网络请求的内容 
 $result = json_decode($res, true);  
 //接受一个 JSON 格式的字符串并且把它转换为 PHP 变量
  $access_token = $result['access_token'];   
  $make_menu_url = "https://api.weixin.qq.com/cgi-bin/menu/get?access_token=" . $access_token;   $menu_json = file_get_contents($make_menu_url);  
 echo $menu_json; 

?>