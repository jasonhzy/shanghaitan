<?php
namespace Home\Controller;
use Think\Controller;
class BaseController extends Controller {
    public $user_id = 0;
    public $user = array();
    public $session_id;
    public $cateTrre = array();
    /*
     * 初始化操作
     */
    public function _initialize() {
        $this->session_id = session_id(); // 当前的 session_id
        if(session('?user'))
        {
            $this->user = $user = session('user');
            $this->user_id = $user['user_id'];
            $user = get_user_info($this->user_id);
            $this->assign('user', $user); //存储用户信息
        }else
        {
            $this->user[user_id] = 0;
        }
        $this->assign('user_id',$this->user_id); 
                
        // 判断当前用户是否手机                
        if(isMobile())
            cookie('is_mobile','1',3600); 
        else 
            cookie('is_mobile','0',3600);
                  
        $this->cartLogic = new \Home\Logic\CartLogic();        
        $cart_result = $this->cartLogic->cartList($this->user, $this->session_id,0,1);
        if(empty($cart_result['total_price']))
            $cart_result['total_price'] = Array( 'total_fee' =>0, 'cut_fee' =>0, 'num' => 0, 'atotal_fee' =>0, 'acut_fee' =>0, 'anum' => 0);
        $cat = M('activity_cat')->where(array('parent_id'=>2,'show_in_nav'=>0))->order('cat_id asc')->select();
        $this->assign('cat',$cat);
        $this->assign('cartList', $cart_result['cartList']); // 购物车的商品
        $this->assign('cart_total_price', $cart_result['total_price']); // 总计        
        $this->public_assign(); 
    }
    /**
     * 保存公告变量到 smarty中 比如 导航 
     */
    public function public_assign()
    {
        
       $tpshop_config = array();
       $tp_config = M('config')->cache(true,TPSHOP_CACHE_TIME)->select();       
       foreach($tp_config as $k => $v)
       {
       	  if($v['name'] == 'hot_keywords'){
       	  	 $tpshop_config['hot_keywords'] = explode('|', $v['value']);
       	  }       	  
          $tpshop_config[$v['inc_type'].'_'.$v['name']] = $v['value'];
       }                        
       
       $goods_category_tree = get_goods_category_tree();    
       $this->cateTrre = $goods_category_tree;
       $this->assign('goods_category_tree', $goods_category_tree);                     
       $brand_list = M('brand')->cache(true,TPSHOP_CACHE_TIME)->field('id,logo,is_hot')->select();
       $this->assign('brand_list', $brand_list);
       $this->assign('tpshop_config', $tpshop_config);          
    }  

    public function tp404($msg='',$url=''){
    	$msg = empty($msg) ? '您可能输入了错误的网址，或者该页面已经不存在了哦。' : $msg;
    	$this->assign('error',$msg);
    	$this->display('Public/tp404');
    	exit;
    }
}