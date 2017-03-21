<?php
namespace Home\Controller;
use Think\AjaxPage;
use Think\Page;
use Think\Verify;
class TravelController extends BaseController {
    public function index(){      
        $this->display();
    }

    /**
     *新闻中心详细页
     *
     */
    public function newDetail(){
        $id = $_GET['id'];
        $arr = M('Article')->where(array('article_id'=>$id))->find();
        $this->assign('arr',$arr);
        $this->assign('id',$arr['article_id']);
        $this->display();
    }

    /**
     *旅游详细页
     *
     */
    public function travelDetail(){
        $id = $_GET['id'];
        $arr = M('Travel')->where(array('travel_id'=>$id))->find();
        $p_arr = M('Travel_images')->where(array('travel_id'=>$id))->select();
        $s_arr = M('schedule')->where(array('travel_id'=>$id))->select();
        $this->assign('p_arr',$p_arr);
        $this->assign('s_arr',$s_arr);
        $this->assign('arr',$arr);
        $this->display();
    }

    /**
     * 同步报名人数
     */
    public function ajaxTravel(){
        $id = $_POST['id'];
        $arr = M('schedule')->where(array('schedule_id'=>$id))->find();
        $re = $arr['num'];
        exit(json_encode($re));
    }
    /**
     * 填写订单
     */
    public function orderDetail(){
        if($_GET['tid']){
            //var_dump($_SESSION);exit;
            $travel_id = $_SESSION['travel_id'];
            $t_arr = M('travel')->where(array('travel_id'=>$travel_id))->find();
            $this->assign('t_arr',$t_arr);
            $this->assign('re',$_SESSION);
            $this->display();
        }else {
            if($_POST['user_id'] == 0) {
                $this->error('你还没有登录，请登录', U("Home/User/login"));
            }else {
                //班期id
                $id = $_POST['id'];
                // var_dump($_POST);exit;
                // 成人人数
                $person = intval($_POST['person']);
                // var_dump($person);exit;
                //儿童人数
                $child = intval($_POST['child']);
                $arr = M('schedule')->where(array('schedule_id' => $id))->find();
                $travel_id = $arr['travel_id'];
                //根据班期得到travel_id 再找到对应的旅游
                $t_arr = M('travel')->where(array('travel_id' => $travel_id))->find();
                $bprice = $t_arr['travel_bprice'];
                $sprice = $t_arr['travel_sprice'];
                $b_total = $bprice * $person;
                $s_total = $sprice * $child;
                $total_price = $b_total + $s_total;
                $re = array(
                    'total_price' => $total_price,
                    'person' => $person,
                    'child' => $child,
                    'bprice' => $bprice,
                    'sprice' => $sprice,
                    'b_total' => $b_total,
                    's_total' => $s_total,
                    'travel_id' => $travel_id,
                    'travel_name'=>$t_arr['travel_name'],
                    'start_time'=>$arr['start_time'],
                    'original_img'=>$t_arr['original_img']
                );
                //var_dump($re);exit;
                //session($re);
                session('total_price', $re['total_price']);
                session('person', $re['person']);
                session('bprice', $re['bprice']);
                session('sprice', $re['sprice']);
                session('b_total', $re['b_total']);
                session('s_total', $re['s_total']);
                session('child', $re['child']);
                session('travel_id', $re['travel_id']);
                session('travel_name', $re['travel_name']);
                session('start_time', $re['start_time']);
                session('original_img', $re['original_img']);
                //var_dump($_SESSION);exit;
                $this->ajaxReturn($re);
            }
        }
    }
    /**
     * 提交订单
     */
    public function subOrder(){
        if($_GET['id']) {
            $order_arr = M('travelorder')->where(array('order_id' => $_GET['id']))->find();
            $travel_id = $_SESSION['travel_id'];
            $t_arr = M('travel')->where(array('travel_id'=>$travel_id))->find();
            $this->assign('t_arr',$t_arr);
            $this->assign('order_arr', $order_arr);
            $this->assign('re', $_SESSION);
            $this->display();
        }else{
            $_POST = array(
                'name'=>$_POST['name'],
                'phone'=>$_POST['phone'],
                'email'=>$_POST['email'],
                'sex' => $_POST['stt_radiosex'],
                'travel_id'=>$_SESSION['travel_id'],
                'travel_name'=>$_SESSION['travel_name'],
                'original_img'=>$_SESSION['original_img'],
                'personnum'=>$_SESSION['person'],
                'childnum'=>$_SESSION['child'],
                'start_time'=>$_SESSION['start_time'],
                'total_price'=>$_SESSION['total_price'],
                'order_sn'=> 'LY'.date('YmdHis').rand(1000,9999)
            );
            $re = M('travelorder')->add($_POST);
            $this->ajaxReturn($re);
        }
    }

    public function sure(){
        $arr = M('travelorder')->where(array('order_id'=>$_GET['id']))->find();
        $_POST = array(
            'mes'=>$_POST['mes'],
            'content'=>$_POST['content'],
            'name'=>$arr['name'],
            'phone'=>$arr['phone'],
            'email'=>$arr['email'],
            'sex' => $arr['sex'],
            'travel_id'=>$arr['travel_id'],
            'personnum'=>$arr['person'],
            'childnum'=>$arr['child'],
            'total_price'=>$arr['total_price'],
            'order_sn'=> $arr['order_sn'],
            'travel_name'=>$arr['travel_name'],
            'original_img'=>$arr['original_img'],
            'start_time'=>$arr['start_time'],
            'order_time'=>time(),
            'user_id'=>$this->user_id
        );
         $re = M('travelorder')->where(array('order_id'=>$_GET['id']))->save($_POST);
        /*$arr['mes'] = $_POST['mes'];
        $arr['content'] = $_POST['content'];*/

        $this->ajaxReturn($re);
    }
    /**
     * 预约成功
     */
    public function success(){
        $this->display();
    }
     /**
     * 旅居服务首页
     */
    public function travelList(){
        $id = $_GET['id'];
        if(!$id){
            $_GET['id'] = 2;
        }
        $cat_arr = M('travel_category')->where(array('parent_id'=>1))->select();
        $travel_arr = M('travel')->where(array('cat_id'=>$_GET['id']))->select();
        $hot_arr = M('travel')->where(array('is_hot'=>1))->order("travel_id desc")->limit(2,6)->select();
        $count = M('Article')->where(array('cat_id'=>44))->count();
        $page = new Page($count,3);
        $arr = M('Article')->where(array('cat_id'=>44))->order('add_time desc')->limit($page->firstRow.','.$page->listRows)->select();
        $show = $page->show();
        $list = M('lunbo')->where(array('name'=>5))->limit(3)->select();
        $this->assign('list',$list);
        $this->assign('page',$show);
        $this->assign('arr',$arr);
        $this->assign('hot_arr',$hot_arr);
        $this->assign('travel_arr',$travel_arr);
        $this->assign('cat_arr',$cat_arr);
        $this->display();
    }
   
    /**
     * 商品搜索列表页
     */
    public function search()
    {
       //C('URL_MODEL',0);
        $filter_param = array(); // 帅选数组                        
        $id = I('get.id',0); // 当前分类id 
        $brand_id = I('brand_id',0);
        $spec = I('spec',0); // 规格 
        $attr = I('attr',''); // 属性        
        $sort = I('sort','goods_id'); // 排序
        $sort_asc = I('sort_asc','asc'); // 排序
        $price = I('price',''); // 价钱
        $start_price = trim(I('start_price','0')); // 输入框价钱
        $end_price = trim(I('end_price','0')); // 输入框价钱
        if($start_price && $end_price) $price = $start_price.'-'.$end_price; // 如果输入框有价钱 则使用输入框的价钱
        $q = urldecode(trim(I('q',''))); // 关键字搜索 
        empty($q) && $this->error('请输入搜索词');
                
        $id && ($filter_param['id'] = $id); //加入帅选条件中                       
        $brand_id  && ($filter_param['brand_id'] = $brand_id); //加入帅选条件中
        $spec  && ($filter_param['spec'] = $spec); //加入帅选条件中
        $attr  && ($filter_param['attr'] = $attr); //加入帅选条件中
        $price  && ($filter_param['price'] = $price); //加入帅选条件中
        $q  && ($_GET['q'] = $filter_param['q'] = $q); //加入帅选条件中
        
        $goodsLogic = new \Home\Logic\GoodsLogic(); // 前台商品操作逻辑类
               
        $where = "goods_name like '%{$q}%' and is_on_sale=1 ";
        if($id)
        {
            $cat_id_arr = getCatGrandson ($id);
            $where .= " and cat_id in(".  implode(',', $cat_id_arr).")";
        }
        
        $search_goods = M('goods')->where($where)->getField('goods_id,cat_id');
        $filter_goods_id = array_keys($search_goods);                
        $filter_cat_id = array_unique($search_goods); // 分类需要去重
        if($filter_cat_id)        
        {
            $cateArr = M('goods_category')->where("id in(".implode(',', $filter_cat_id).")")->select();            
            $tmp = $filter_param;
            foreach($cateArr as $k => $v)            
            {
                $tmp['id'] = $v['id'];
                $cateArr[$k]['href'] = U("/Home/Goods/search",$tmp);                            
            }                
        }                        
        // 过滤帅选的结果集里面找商品        
        if($brand_id || $price)// 品牌或者价格
        {
            $goods_id_1 = $goodsLogic->getGoodsIdByBrandPrice($brand_id,$price); // 根据 品牌 或者 价格范围 查找所有商品id    
            $filter_goods_id = array_intersect($filter_goods_id,$goods_id_1); // 获取多个帅选条件的结果 的交集
        }
        if($spec)// 规格
        {
            $goods_id_2 = $goodsLogic->getGoodsIdBySpec($spec); // 根据 规格 查找当所有商品id
            $filter_goods_id = array_intersect($filter_goods_id,$goods_id_2); // 获取多个帅选条件的结果 的交集
        }
        if($attr)// 属性
        {
            $goods_id_3 = $goodsLogic->getGoodsIdByAttr($attr); // 根据 规格 查找当所有商品id
            $filter_goods_id = array_intersect($filter_goods_id,$goods_id_3); // 获取多个帅选条件的结果 的交集
        }        
             
        $filter_menu  = $goodsLogic->get_filter_menu($filter_param,'search'); // 获取显示的帅选菜单
        $filter_price = $goodsLogic->get_filter_price($filter_goods_id,$filter_param,'search'); // 帅选的价格期间         
        $filter_brand = $goodsLogic->get_filter_brand($filter_goods_id,$filter_param,'search',1); // 获取指定分类下的帅选品牌        
        $filter_spec  = $goodsLogic->get_filter_spec($filter_goods_id,$filter_param,'search',1); // 获取指定分类下的帅选规格        
        $filter_attr  = $goodsLogic->get_filter_attr($filter_goods_id,$filter_param,'search',1); // 获取指定分类下的帅选属性        
                                
        $count = count($filter_goods_id);
        $page = new Page($count,20);
        if($count > 0)
        {
            $goods_list = M('goods')->where("is_on_sale=1 and goods_id in (".  implode(',', $filter_goods_id).")")->order("$sort $sort_asc")->limit($page->firstRow.','.$page->listRows)->select();
            $filter_goods_id2 = get_arr_column($goods_list, 'goods_id');
            if($filter_goods_id2)
            $goods_images = M('goods_images')->where("goods_id in (".  implode(',', $filter_goods_id2).")")->select();       
        }    
                
        $this->assign('goods_list',$goods_list);  
        $this->assign('goods_images',$goods_images);  // 相册图片
        $this->assign('filter_menu',$filter_menu);  // 帅选菜单
        $this->assign('filter_spec',$filter_spec);  // 帅选规格
        $this->assign('filter_attr',$filter_attr);  // 帅选属性
        $this->assign('filter_brand',$filter_brand);  // 列表页帅选属性 - 商品品牌
        $this->assign('filter_price',$filter_price);// 帅选的价格期间
        $this->assign('cateArr',$cateArr);
        $this->assign('filter_param',$filter_param); // 帅选条件
        $this->assign('cat_id',$id);
        $this->assign('page',$page);// 赋值分页输出
        C('TOKEN_ON',false);
        $this->display();
    }

}