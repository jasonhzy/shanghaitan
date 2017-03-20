<?php
namespace Mobile\Controller;
use Think\Controller;
class WeixinController extends MobileBaseController {
    public $client;

    public function _initialize(){
        parent::_initialize();
        //获取微信配置信息
        $wechat_list = M('wx_user')->select();
        $wechat_config = $wechat_list[0];
        $options = array(
 			'token'=>$wechat_config['w_token'], //填写你设定的key
 			'encodingaeskey'=>$wechat_config['aeskey'], //填写加密用的EncodingAESKey
 			'appid'=>$wechat_config['appid'], //填写高级调用功能的app id
 			'appsecret'=>$wechat_config['appsecret'], //填写高级调用功能的密钥
        		);

    }

    public function oauth(){

    }
}