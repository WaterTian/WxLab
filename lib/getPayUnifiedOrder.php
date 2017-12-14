<?php
include_once("./WxApi.php");


/**
 * JS_API支付
 * ====================================================
 * 步骤1：网页授权获取用户openid
 * 步骤2：使用统一支付接口，获取prepay_id
 * 步骤3：使用jsapi调起支付
*/


//=========步骤1：网页授权获取用户openid============
//通过GET传入openid
$openid = $_GET['openid'];
if(empty($openid)){
	$result = array();
	$result['errorCode'] = 2000;
	$result['msg'] = 'openid empty';
	response($result);
}
//金额 单位 分
$fee = $_GET['fee'];
if(empty($fee)){
	$result = array();
	$result['errorCode'] = 2000;
	$result['msg'] = 'fee empty';
	response($result);
}


//=========步骤2：使用统一支付接口，获取prepay_id============
//使用统一支付接口
$unifiedOrder = new UnifiedOrder_pub();

//设置统一支付接口参数
//appid已填,商户无需重复填写
//mch_id已填,商户无需重复填写
//noncestr已填,商户无需重复填写
//spbill_create_ip已填,商户无需重复填写
//sign已填,商户无需重复填写
$unifiedOrder->setParameter("openid","$openid");//商品描述
$unifiedOrder->setParameter("body","贡献一点钱");//商品描述

//自定义订单号
// $timeStamp = time();
$timeStamp = time()+(microtime(true)*10000);
$out_trade_no = WxConfig::APPID."$timeStamp";
$unifiedOrder->setParameter("out_trade_no","$out_trade_no");//商户订单号 

$unifiedOrder->setParameter("total_fee","$fee");//总金额
$unifiedOrder->setParameter("notify_url",WxConfig::NOTIFY_URL);//通知地址 
$unifiedOrder->setParameter("trade_type","JSAPI");//交易类型



$prepay_id = $unifiedOrder->getPrepayId();


//=========步骤3：使用jsapi 获得支付参数============
$jsApi = new JsApi_pub();
$jsApi->setPrepayId($prepay_id);
$jsApiParameters = $jsApi->getParameters();

echo $jsApiParameters;
exit();

?>