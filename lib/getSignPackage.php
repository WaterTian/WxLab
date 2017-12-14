<?php

/**
 * 微信基础签名包
 * ====================================================
 * 获得基础的 微信JS-SDK 功能
 * 例如 分享 录音 地理位置等...
*/
include_once("WxConfig.php");

getSignPackage();

function getSignPackage() {
  // 注意 URL 一定要动态获取，不能 hardcode.
  $url = $_GET['path'];
  if(empty($url)){
  	$result = array();
  	$result['errorCode'] = 2000;
  	$result['msg'] = 'path empty';
  	response($result);
  }
  $jsapiTicket = getJsApiTicket();
  $timestamp = time();
  $nonceStr = createNonceStr();
  // 这里参数的顺序要按照 key 值 ASCII 码升序排序
  $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";
  $signature = sha1($string);
  $signPackage = array(
    "appId"     => WxConfig::APPID,
    "nonceStr"  => $nonceStr,
    "timestamp" => $timestamp,
    "url"       => $url,
    "signature" => $signature,
    "rawString" => $string,
  );
  response($signPackage);
}
function createNonceStr($length = 16) {
	$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	$str = "";
	for ($i = 0; $i < $length; $i++) {
  		$str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
	}
	return $str;
}
function getJsApiTicket() {    
  $ticket = '';
  // jsapi_ticket 应该全局存储与更新，以下代码以写入到文件中做示例
  $tokenFile = "access_token.txt";//缓存文件名
  $data = json_decode(file_get_contents($tokenFile));
  if ($data->expire_time < time() or !$data->expire_time)
  {
    	$accessToken = getAccessToken();
    	// 如果是企业号用以下 URL 获取 ticke
    	$url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$accessToken";
    	// $res = json_decode(file_get_contents($url));
      $res = json_decode(httpsGet($url));
    	$ticket = $res->ticket;
    	if ($ticket) {
      	$data = array();
      	$data['app_id'] = WxConfig::APPID;
      	$data['access_token'] = $accessToken;
      	$data['jsapi_ticket'] = $ticket;
      	$data['expire_time'] = time() + 7000;

      	$fp = fopen($tokenFile, "w");
        fwrite($fp, json_encode($data));
        fclose($fp);
    	}
  }else {
    	$ticket = $data->jsapi_ticket;
  }
  return $ticket;
}

function getAccessToken() {
	$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".WxConfig::APPID."&secret=".WxConfig::APPSECRET;
	// $res = json_decode(file_get_contents($url));
  $res = json_decode(httpsGet($url));
	$access_token = $res->access_token;
	return $access_token;
}


function httpGet($url) {
  $curl = curl_init();
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($curl, CURLOPT_TIMEOUT, 500);
  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
  curl_setopt($curl, CURLOPT_URL, $url);
  $res = curl_exec($curl);
  curl_close($curl);
  return $res;
}


function httpsGet($url) {
  $curl = curl_init(); // 启动一个CURL会话
  curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
  curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1); // 从证书中检查SSL加密算法是否存在
  curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
  curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
  curl_setopt($curl, CURLOPT_TIMEOUT, 50); // 设置超时限制防止死循环
  curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回

  $tmpInfo = curl_exec($curl); // 执行操作
  if (curl_errno($curl)) {
    echo 'Errno'.curl_error($curl);//捕抓异常
  }
  curl_close($curl); // 关闭CURL会话
  return $tmpInfo; // 返回数据
}


function response($data){
	if(array_key_exists('callback',$_GET)){
		$callback = $_GET['callback'];
		echo $callback.'('.json_encode($data).')';exit();
	}
	echo json_encode($data);exit();
}
?>