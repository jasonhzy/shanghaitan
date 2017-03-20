<?php
namespace Mobile\Controller;
use Think\Page;
use Think\Verify;
class IndexController extends MobileBaseController {
    

    
    public function index(){

         /*  //获取微信配置
           $wechat_list = M('wx_user')->select();
           $wechat_config = $wechat_list[0];
           $this->weixin_config = $wechat_config;
           // 微信Jssdk 操作类 用分享朋友圈 JS
           $jssdk = new \Mobile\Logic\Jssdk($this->weixin_config['appid'], $this->weixin_config['appsecret']);
           $signPackage = $jssdk->GetSignPackage();*/

        clear_cart(); // 清空购物车垃圾数据       
        
     /*   $hot_goods = $hot_cate = $cateList = array();
        $sql = "select a.goods_name,a.goods_id,a.shop_price,a.market_price,a.cat_id,b.parent_id_path,b.name from __PREFIX__goods as a left join ";
        $sql .= " __PREFIX__goods_category as b on a.cat_id=b.id where a.is_hot=1 and a.is_on_sale=1 order by a.sort";//二级分类下热卖商品       
        $index_hot_goods = M()->query($sql);//首页热卖商品
		if($index_hot_goods){
			foreach($index_hot_goods as $val){
				$cat_path = explode('_', $val['parent_id_path']);
				$hot_goods[$cat_path[1]][] = $val;
			}
		}

        $hot_category = M('goods_category')->where("is_hot=1 and level=3 and is_show=1")->select();//热门三级分类
        foreach ($hot_category as $v){
        	$cat_path = explode('_', $v['parent_id_path']);
        	$hot_cate[$cat_path[1]][] = $v;
        }
        
        foreach ($this->cateTrre as $k=>$v){
            if($v['is_hot']==1){
        		$v['hot_goods'] = empty($hot_goods[$k]) ? '' : $hot_goods[$k];
        		$v['hot_cate'] = empty($hot_cate[$k]) ? '' : $hot_cate[$k];
        		$cateList[] = $v;
        	}
        }*/
        $arr = M("music")->select();
        $i_arr = array();
        foreach($arr as $k=>$v){
            $i_arr[$k] = $v['id'];
        }
        $tarr = M("music")->where(array('id'=>max($i_arr)))->find();
        $this->assign('arr',$tarr);
        $this->display();
    }
 
    /**
     *  公告详情页
     */
    public function gonggao(){
        $this->display();
    }
    
    // 二维码
    public function erweima(){        
        // 导入Vendor类库包 Library/Vendor/Zend/Server.class.php
        //http://www.tp-shop.cn/Home/Index/erweima/data/www.99soubao.com
         require_once 'ThinkPHP/Library/Vendor/phpqrcode/phpqrcode.php';
          //import('Vendor.phpqrcode.phpqrcode');
            error_reporting(E_ERROR);            
            $url = urldecode($_GET["data"]);
            \QRcode::png($url);          
    }
    
    // 验证码
    public function verify()
    {
        //验证码类型
        $type = I('get.type') ? I('get.type') : '';
        $fontSize = I('get.fontSize') ? I('get.fontSize') : '40';
        $length = I('get.length') ? I('get.length') : '4';
        
        $config = array(
            'fontSize' => $fontSize,
            'length' => $length,
            'useCurve' => true,
            'useNoise' => false,
        );
        $Verify = new Verify($config);
        $Verify->entry($type);        
    }
    
    // 促销活动页面
    public function promoteList()
    {        
        $goodsList = M('goods')->where("is_promote = 1 and ".time()." > promote_start_time  and  ".time()." < promote_end_time")->select();
        $brandList = M('brand')->getField("id,name,logo");        
        $this->assign('brandList',$brandList);
        $this->assign('goodsList',$goodsList);
        $this->display();
    }
    public function aboutUs()
    {
        $arr = M('Position')->order('p_id desc')->limit(3)->select();
        $this->assign('arr',$arr);
        $this->display();
    }
    public function ajaxTal()
    {
        $_POST['t_time'] = time();
        $re = M('Talents')->add($_POST);
        $this->ajaxReturn(json_encode($re));
    }
}