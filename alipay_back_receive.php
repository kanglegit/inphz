<?php
header("content-type:text/html; charset=utf-8");
//data
require_once "data/config.inc.php";

require_once "app/my/alipay/alipay.config.php";
require_once "app/my/alipay/lib/alipay_notify.class.php";

//mysql 连接
$con = @mysql_connect($TS_DB['host'],$TS_DB['user'],$TS_DB['pwd']);
@mysql_select_db($TS_DB['name'], $con);
@mysql_query("set names utf-8");
if(!empty($_POST))
{
	$str = json_encode($_POST);
	
	@mysql_query("insert into alipay_log (log) values ('".$str."')");
}
				//交易状态
/*				$out_trade_no = '20151106151527';
				$total_fee = 10;
				mysql_query("update inphz_orders set orders_status=2 where orders_id=".$out_trade_no);
				//查找userid
	
				//查找userid
				$user_query = @mysql_query("select*from inphz_orders where orders_id = ".$out_trade_no);
				$user_result = @mysql_fetch_array($user_query);
				$user_id = $user_result['user_id'];
				//添加积分记录
				$score = $total_fee;
				$t = time();
				@mysql_query("insert into inphz_user_score_log (userid,scorename,score,status,addtime) values ('".$user_id."','支付宝在线充值','".$score."',0,'".$t."')");
				//计算总积分
				$score_query = @mysql_query("select count_score from inphz_user_info where userid=".$user_id);
				$score_result = @mysql_fetch_array($score_query);
				$count_score = $score_result['count_score']+$score;
				@mysql_query("update inphz_user_info set count_score='".$count_score."' where userid=".$user_id);
				*/

//计算得出通知验证结果
			$alipayNotify = new AlipayNotify($alipay_config);
			$verify_result = $alipayNotify->verifyNotify();

			if($verify_result) {//验证成功
				/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				//请在这里加上商户的业务逻辑程序代

				
				//——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
				
				//获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表
				
				//商户订单号

				$out_trade_no = $_POST['out_trade_no'];

				//支付宝交易号

				$trade_no = $_POST['trade_no'];
				$total_fee= $_POST['total_fee'];

				//交易状态
				$trade_status = $_POST['trade_status'];
				@mysql_query("update inphz_orders set orders_status=2 where orders_id=".$out_trade_no);
				//查找userid
	
				//查找userid
				$user_query = @mysql_query("select*from inphz_orders where orders_id = ".$out_trade_no);
				$user_result = @mysql_fetch_array($user_query);
				$user_id = $user_result['user_id'];
				//添加积分记录
				$score = $total_fee;
				$t = time();
				@mysql_query("insert into inphz_user_score_log (userid,scorename,score,status,addtime) values ('".$user_id."','alipay','".$score."',0,'".$t."')");
				//计算总积分
				$score_query = @mysql_query("select count_score from inphz_user_info where userid=".$user_id);
				$score_result = @mysql_fetch_array($score_query);
				$count_score = $score_result['count_score']+$score;
				@mysql_query("update inphz_user_info set count_score='".$count_score."' where userid=".$user_id);
				@mysql_query("insert into alipay_log (log) values ('success')");
				echo "success";		//请不要修改或删除
				exit();
				if($_POST['trade_status'] == 'TRADE_FINISHED') {
					
					//$o = $new['my'] -> find('orders', array('orders_id'=>$out_trade_no));
					//$userid = $o['user_id'];
					//$new['my'] -> update('orders', array('orders_id'=>$out_trade_no),array('orders_status'=>2));
					//aac('user') -> addScore($userid,'支付宝在线充值',$total_fee);
					
					//判断该笔订单是否在商户网站中已经做过处理
						//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
						//如果有做过处理，不执行商户的业务程序
							
					//注意：
					//退款日期超过可退款期限后（如三个月可退款），支付宝系统发送该交易状态通知

					//调试用，写文本函数记录程序运行情况是否正常
					//logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
				}
				else if ($_POST['trade_status'] == 'TRADE_SUCCESS') {
					//判断该笔订单是否在商户网站中已经做过处理
						//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
						//如果有做过处理，不执行商户的业务程序
							
					//注意：
					//付款完成后，支付宝系统发送该交易状态通知
					//$o = $new['my'] -> find('orders', array('orders_id'=>$out_trade_no));
					//$userid = $o['user_id'];
					//$new['my'] -> update('orders', array('orders_id'=>$out_trade_no),array('orders_status'=>2));
					//aac('user') -> addScore($userid,'支付宝在线充值',$total_fee);
					//$userid = $o['user_id'];
					//$pu_id = $new['paymentunionpay'] -> create('payment_unionpay', $arrData);
					//调试用，写文本函数记录程序运行情况是否正常
					//logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
				}

				//——请根据您的业务逻辑来编写程序（以上代码仅作参考）——

				
				/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			}
			else {
				//验证失败
				@mysql_query("insert into alipay_log (log) values ('fail')");
				echo "fail";

				//调试用，写文本函数记录程序运行情况是否正常
				//logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
			}
?>