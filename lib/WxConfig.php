<?php
/**
* 	配置账号信息
*/

class WxConfig
{
	//=======【基本信息设置】=====================================
	//微信公众号身份的唯一标识。审核通过后，在微信发送的邮件中查看
	const APPID = 'wxb1e083f1652f5616';
	//JSAPI接口中获取openid，审核后在公众平台开启开发模式后可查看
	const APPSECRET = '464b7f75d8516d39f29a1c46cef2a7b9';
	//受理商ID，身份标识
	const MCHID = '1415553002';
	//商户支付密钥Key。审核通过后，在微信发送的邮件中查看
	const KEY = 'j378dqhz73m93nd6e9d0k1ux2ln1g3mn';
 
	//=======【证书路径设置】=====================================
	//证书路径,注意应该填写绝对路径
	const SSLCERT_PATH = 'http://cdn.180china.com/WxLab/lib/cacert/apiclient_cert.pem';
	const SSLKEY_PATH = 'http://cdn.180china.com/WxLab/lib/cacert/apiclient_key.pem';
	
	//=======【异步通知url设置】===================================
	//异步通知url，商户根据实际开发过程设定
	const NOTIFY_URL = 'http://cdn.180china.com/Wxpay/notify_url.php';

	//=======【curl超时设置】===================================
	//本例程通过curl使用HTTP POST方法，此处可修改其超时时间，默认为30秒
	const CURL_TIMEOUT = 30;
}
	
?>