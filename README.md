# WxLab

180LAB 微信授权

#### 分享权限 ： 
实例 http://cdn.180china.com/WxLab/sign.html

``` javascript
"http://cdn.180china.com/WxLab/lib/getSignPackage.php?path="+encodeURIComponent(window.location.href);

```

#### 用户信息权限 ： 
实例 http://cdn.180china.com/WxLab/wxinfo.php

``` javascript
<?php
    $appId     = 'wxb1e083f1652f5616';
    $getWxInfo = "http://cdn.180china.com/WxLab/lib/getWxInfo.php";

    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $url = rawurlencode("$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
    if(!array_key_exists('code',$_GET) || trim($_GET['code']) == ""){
        $authorize ='https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$appId.'&redirect_uri='.$url.'&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect';
        header('Location:'.$authorize);
    }else{
        $wx_info = json_decode(file_get_contents($getWxInfo."?code=".$_GET['code']),true);
        print_r($wx_info);
    } 
    $signPackage = json_decode(file_get_contents("http://cdn.180china.com/WxLab/lib/getSignPackage.php?path=$url"),true);
?>

<script>
    var user_info=
    {
        "openid": "<?php echo $wx_info["openid"];?>",
        "nickname": "<?php echo $wx_info["nickname"];?>",
        "headimgurl":"<?php echo $wx_info["headimgurl"];?>",
        "sex":"<?php echo $wx_info["sex"];?>",
        "city":"<?php echo $wx_info["city"];?>"
    }
</script>

```
