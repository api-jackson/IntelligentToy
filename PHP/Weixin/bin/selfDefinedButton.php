<?php
require_once dirname(__FILE__).'/../class/tokenStub.php';
require_once dirname(__FILE__).'/../common/Common.php';

// $button = array("button"=>
// 		array(
// 				array(
// 						"name"=>"助手",
// 						"sub_button"=>array(
// 								array("type"=>"click","name"=>"变声留言","key"=>"leave_message"),
// 								array("type"=>"click","name"=>"我讲故事","key"=>"tell_story"),
// 								array("type"=>"click","name"=>"拍照","key"=>"take_photo"),
// 								array("type"=>"view","name"=>"视频录制","url"=>"http://112.74.16.163")
// 						)
// 				),
// 				array(
// 						"name"=>"资源",
// 						"sub_button"=>array(
// 								array("type"=>"click","name"=>"口袋故事","key"=>"collection"),
// 								array("type"=>"click","name"=>"云端故事","key"=>"public_resource"),
// 								array("type"=>"click","name"=>"公仔故事","key"=>"toy_resource"),
// 								array("type"=>"click","name"=>"故事更新","key"=>"update"),
// 								array("type"=>"click","name"=>"复读记录","key"=>"repeater_record")
// 						)
// 				),
// 				array(
// 						"name"=>"设置",
// 						"sub_button"=>array(
// 								array("type"=>"click","name"=>"添加硬件","key"=>"hardware"),
// 								array("type"=>"click","name"=>"网络设置","key"=>"network_setup"),
// 								array("type"=>"view","name"=>"宝宝生日","url"=>"https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx3309b26898b21a67&redirect_uri=http%3A%2F%2F112.74.16.163%2Fljc%2FWeixin%2Fhtml%2Fbirthday.php&response_type=code&scope=snsapi_base&state=1#wechat_redirect"),
// 								array("type"=>"view","name"=>"玩转指南","url"=>"https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx3309b26898b21a67&redirect_uri=http%3A%2F%2F112.74.16.163%2Fljc%2FWeixin%2Fhtml%2Fguide.php&response_type=code&scope=snsapi_base&state=2#wechat_redirect")
// 						)
// 				)
// 		)
		
// );
 
$jsonButton = '{
    "button": [
        {
            "name": "助手", 
            "sub_button": [
		        {
                    "type": "click", 
                    "name": "拍照", 
                    "key": "take_photo"
                }, 
                {
                    "type": "click", 
                    "name": "视频录制", 
                    "key": "video"
                }, 
                {
                    "type": "view", 
                    "name": "遥控公仔", 
                    "url": "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx3309b26898b21a67&redirect_uri=http%3A%2F%2F112.74.16.163%2Fljc%2FWeixin%2Fhtml%2Fremote_control.php&response_type=code&scope=snsapi_base&state=1#wechat_redirect"
                },
				{
                    "type": "view", 
                    "name": "音量控制", 
                    "url": "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx3309b26898b21a67&redirect_uri=http%3A%2F%2F112.74.16.163%2Fljc%2FWeixin%2Fhtml%2Fvolume.php&response_type=code&scope=snsapi_base&state=1#wechat_redirect"
                }
            ]
        }, 
        {
            "name": "资源", 
            "sub_button": [
                {
                    "type": "view", 
                    "name": "本地资源", 
                    "url": "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx3309b26898b21a67&redirect_uri=http%3A%2F%2F112.74.16.163%2Fljc%2FWeixin%2Fhtml%2Fupload.php&response_type=code&scope=snsapi_base&state=1#wechat_redirect"
                }, 
                {
                    "type": "view", 
                    "name": "云端资源", 
                    "url": "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx3309b26898b21a67&redirect_uri=http%3A%2F%2F112.74.16.163%2Fljc%2FWeixin%2Fhtml%2Fserver_resource.php&response_type=code&scope=snsapi_base&state=1#wechat_redirect"
                }, 
                {
                    "type": "view", 
                    "name": "公仔资源", 
                    "url": "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx3309b26898b21a67&redirect_uri=http%3A%2F%2F112.74.16.163%2Fljc%2FWeixin%2Fhtml%2Fpersonal_resource.php&response_type=code&scope=snsapi_base&state=1#wechat_redirect"
                }
            ]
        }, 
        {
            "name": "设置", 
            "sub_button": [
                {
                    "type": "scancode_push", 
                    "name": "添加硬件", 
                    "key": "hardware",
					"sub_button":[]
                }, 
                {
                    "type": "view", 
                    "name": "网络设置", 
                    "url": "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx3309b26898b21a67&redirect_uri=http%3A%2F%2F112.74.16.163%2Fljc%2FWeixin%2Fhtml%2FgenQRCode.php&response_type=code&scope=snsapi_base&state=1#wechat_redirect"
                }, 
                {
                    "type": "view", 
                    "name": "宝宝信息", 
                    "url": "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx3309b26898b21a67&redirect_uri=http%3A%2F%2F112.74.16.163%2Fljc%2FWeixin%2Fhtml%2Fbirthday.php&response_type=code&scope=snsapi_base&state=1#wechat_redirect"
                }, 
                {
                    "type": "view", 
                    "name": "玩转指南", 
                    "url": "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx3309b26898b21a67&redirect_uri=http%3A%2F%2F112.74.16.163%2Fljc%2FWeixin%2Fhtml%2Fwanzhuan.html&response_type=code&scope=snsapi_base&state=2#wechat_redirect"
                },
            ]
        }
    ]
}';


$ACCESS_TOKEN = tokenStub::getToken();
 
$url = WX_API_HTTPS_URL."menu/create?access_token=$ACCESS_TOKEN";
 
$output = doHttpsCurlPostRequest($url, $jsonButton);

echo $output;

?>