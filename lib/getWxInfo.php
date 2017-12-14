<?php

/**
 * 微信用户信息
 * ====================================================
 * 获得用户信息 openid 昵称 头像 
*/
include_once("WxConfig.php");

getUserInfo();

function getUserInfo(){
    $code = $_GET['code'];
	if($code == ''){
		echo 'code err，empty';exit;
	}

    $getTokenUrl = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".WxConfig::APPID."&secret=".WxConfig::APPSECRET."&code=".$code."&grant_type=authorization_code";
    
    // $r = json_decode(file_get_contents($getTokenUrl));
    $r = json_decode(httpGet($getTokenUrl));
    
    // setcookie("openid",$r->openid, time()+3600*2,"/");
    // setcookie("access_token",$r->access_token,time()+3600*2,"/");

    $info = getInfo($r->access_token, $r->openid);
    response($info);
}

function getInfo($access_token, $openid){
	$appInfoUrl = "https://api.weixin.qq.com/sns/userinfo?access_token=$access_token&openid=$openid&lang=zh_CN";
	// return json_decode(file_get_contents($appInfoUrl));
	return json_decode(httpGet($appInfoUrl));
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
