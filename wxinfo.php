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


<!DOCTYPE html>
<meta charset="utf-8" /> 

<div id="openid"></div>
<img id="myImage" src="" />
<div id="uname"></div>
<div id="sex"></div>
<div id="city"></div>

<script>
    var user_info=
    {
        "openid": "<?php echo $wx_info["openid"];?>",
        "nickname": "<?php echo $wx_info["nickname"];?>",
        "headimgurl":"<?php echo $wx_info["headimgurl"];?>",
        "sex":"<?php echo $wx_info["sex"];?>",
        "city":"<?php echo $wx_info["city"];?>"
    }
    document.getElementById('openid').innerHTML = '<span>'+user_info["openid"]+'</span>';
    document.getElementById("myImage").src=user_info["headimgurl"];
    document.getElementById('uname').innerHTML = '<span>'+user_info["nickname"]+'</span>';
    document.getElementById('sex').innerHTML = '<span>'+user_info["sex"]+'</span>';
    document.getElementById('city').innerHTML = '<span>'+user_info["city"]+'</span>';
</script>



<!-- <script src="https://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script> -->
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script>
    wx.config({
      debug: false,
      appId: '<?php echo $signPackage["appId"];?>',
      timestamp: <?php echo $signPackage["timestamp"];?>,
      nonceStr: '<?php echo $signPackage["nonceStr"];?>',
      signature: '<?php echo $signPackage["signature"];?>',
      jsApiList: [
          'checkJsApi',
          'onMenuShareTimeline',
          'onMenuShareAppMessage',
          'onMenuShareQQ',
          'onMenuShareWeibo'
      ]
    });
    //分享地址
    var sharePath="http://cdn.180china.com/WxLab/wxinfo.php";
    //分享图像
    var shareImg=user_info["headimgurl"];
    wx.ready(function(){
        // 分享到朋友圈
        wx.onMenuShareTimeline({
            title: '分享标题', // 分享标题
            link: sharePath, // 分享链接
            imgUrl: shareImg, // 分享图标
            success: function () {
                // 用户确认分享后执行的回调函数
            },
            cancel: function () {
                // 用户取消分享后执行的回调函数
            }
        });
        //分享给朋友
        wx.onMenuShareAppMessage({
            title: '分享标题', // 分享标题
            desc: '分享描述', // 分享描述
            link: sharePath, // 分享链接
            imgUrl: shareImg, // 分享图标
            type: 'link', // 分享类型,music、video或link，不填默认为link
            dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
            success: function () {
                // 用户确认分享后执行的回调函数
            },
            cancel: function () {
                // 用户取消分享后执行的回调函数
            }
        });
    });
</script>


