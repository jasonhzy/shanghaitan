<?php
namespace Home\Controller;
use Think\Page;
use Think\Verify;
class IndexController extends BaseController {
    public function index(){
       /* $arr = M("music")->select();
        $i_arr = array();
        foreach($arr as $k=>$v){
            $i_arr[$k] = $v['id'];
        }
        $tarr = M("music")->where(array('id'=>max($i_arr)))->find();
        $this->assign('arr',$tarr);*/
        $nav = M('navigation')->where(array('parent_id'=>0))->order('id desc')->select();
        $lunbo = M('lunbo')->where(array('is_show'=>1,'type'=>0))->order('id desc')->select();
        $ad = M('ad')->where(array('enabled'=>1))->select();
        $cat = M('activity')->where(array('parent_id'=>0))->select();
        $cat2 = M('activity_cat')->where(array('parent_id'=>2,'show_in_nav'=>1))->order('cat_id asc')->select();
        $cat3 = M('activity_cat')->where(array('parent_id'=>2,'show_in_nav'=>0))->limit(3)->order('cat_id asc')->select();
        $cat4 = M('activity_cat')->where(array('parent_id'=>3,'show_in_nav'=>1))->order('cat_id asc')->select();
        $cat5 = M('activity_cat')->where(array('parent_id'=>3,'show_in_nav'=>0))->order('cat_id asc')->select();
        $users = M('users')->where(array('is_check'=>1))->select();
        $this->assign('cat',$cat);
        $this->assign('cat2',$cat2);
        $this->assign('cat3',$cat3);
        $this->assign('cat4',$cat4);
        $this->assign('cat5',$cat5);
        $this->assign('cat',$cat);
        $this->assign('ad',$ad);
        $this->assign('users',$users);
        $this->assign('nav',$nav);
        $this->assign('lunbo',$lunbo);
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