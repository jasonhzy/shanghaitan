<?php
/**
 * tpshop
 * ============================================================================
 * * 版权所有 2015-2027 深圳搜豹网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.tp-shop.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用 .
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * 微信交互类
 */ 
namespace Home\Controller;
use Think\Controller;
class WeixinController extends BaseController {
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