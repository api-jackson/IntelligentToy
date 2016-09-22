<?php 
//require_once dirname(__FILE__).'/ErrorCode.php';
define('ROOT_PATH', dirname(__FILE__) . '/../');
define('DEFAULT_CHARSET', 'utf-8');
define('COMPONENT_VERSION', '1.0');
define('COMPONENT_NAME', 'wxmp');

//关闭NOTICE错误日志
error_reporting(E_ALL ^ E_NOTICE);

define('wanju', 'gh_5ca50ccb5851');
define('WX_API_HTTPS_URL', "https://api.weixin.qq.com/cgi-bin/");
define('WX_API_HTTP_URL', "http://api.weixin.qq.com/cgi-bin/");
// define('WX_API_APPID', "wx3309b26898b21a67");
// define('WX_API_APPSECRET', "3130556e5ef4925f1474af1538929867");

define('WX_API_APPID', "wxce41caf19d614b81");
define('WX_API_APPSECRET', "8a19708589ff1485fdeebf4bbf964d64");

define("WEIXIN_TOKEN", "WeChat");
define("HINT_NOT_IMPLEMEMT", "未实现");

define('TEXT_TPL', 
		"<xml>
  		<ToUserName><![CDATA[%s]]></ToUserName>
  		<FromUserName><![CDATA[%s]]></FromUserName>
  		<CreateTime>%s</CreateTime>
  		<MsgType><![CDATA[%s]]></MsgType>
  		<Content><![CDATA[%s]]></Content>
  		<FuncFlag>0</FuncFlag>
		</xml>"
		);
		
define("IMAGE_TPL", 
		"<xml>
		<ToUserName><![CDATA[%s]]></ToUserName>
		<FromUserName><![CDATA[%s]]></FromUserName>
		<CreateTime>%s</CreateTime>
		<MsgType><![CDATA[%s]]></MsgType>
		<Image>
		<MediaId><![CDATA[%s]]></MediaId>
		</Image>
		</xml>");

define('VOICE_TPL',
		"<xml>
		<ToUserName><![CDATA[%s]]></ToUserName>
		<FromUserName><![CDATA[%s]]></FromUserName>
		<CreateTime>%s</CreateTime>
		<MsgType><![CDATA[%s]]></MsgType>
		<Voice>
		<MediaId><![CDATA[%s]]></MediaId>
		</Voice>
		</xml>"
);
		
define("VIDEO_TPL",
		"<xml>
		<ToUserName><![CDATA[%s]]></ToUserName>
		<FromUserName><![CDATA[%s]]></FromUserName>
		<CreateTime>%s</CreateTime>
		<MsgType><![CDATA[%s]]></MsgType>
		<Video>
		<MediaId><![CDATA[%s]]></MediaId>
		<Title><![CDATA[%s]]></Title>
		<Description><![CDATA[%s]]></Description>
		</Video>
		</xml>");

$GLOBALS['DB'] = array(
	'DB' => array(
		'HOST' => 'localhost',
		'DBNAME' => 'heyi',
		'USER' => 'root',
		'PASSWD' => 'gdutlab404',
		'PORT' => 3306 
	)
);

define('WJ_HINT_INPUT_HARDWARE_NUM', "请输入硬件编号");
define('WJ_HINT_ADD_HARDWARE_REPEATED', "该硬件已有管理者");
define('WJ_HINT_HAVING_THIS_HARDWARE', "您已绑定该硬件");
define('WJ_HINT_HAVING_OTHER_HARDWARE', "您已绑定硬件：");
define('WJ_HINT_HARDWARE_NOT_EXISTS', "该硬件编号不存在");
define('WJ_HINT_ADD_SUCCESSFULLY', "成功添加硬件");
define('WJ_HINT_HAVE_NO_DEVICE', "你还未添加硬件，请添加硬件");
define('WJ_HINT_YOUR_DEVICE_ID', "你的硬件编号是：");
define('WJ_HINT_PLEASE_WAIT', "请稍候");

?>
