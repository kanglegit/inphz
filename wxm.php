<?php   define("APPID", "wx848a34fbbf98d75e"); 
define("APPSECRET", "b911cb26201718e4025ea8154d597bfb");  
$token_access_url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . APPID . "&secret=" . APPSECRET; $res = file_get_contents($token_access_url); 
 //��ȡ�ļ����ݻ��ȡ������������� 
 $result = json_decode($res, true);  
 //����һ�� JSON ��ʽ���ַ������Ұ���ת��Ϊ PHP ����
  $access_token = $result['access_token'];   
  $make_menu_url = "https://api.weixin.qq.com/cgi-bin/menu/get?access_token=" . $access_token;   $menu_json = file_get_contents($make_menu_url);  
 echo $menu_json; 

?>