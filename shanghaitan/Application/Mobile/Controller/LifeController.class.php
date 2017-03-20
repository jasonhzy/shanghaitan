<?php
namespace Mobile\Controller;
use Think\AjaxPage;
use Think\Page;
use Think\Verify;
class LifeController extends MobileBaseController {
    public function index(){      
        $this->display();
    }
   /**
    * 商品详情页(积分兑换）
    */ 
    public function lifeInfo(){
        //  form表单提交
        C('TOKEN_ON',true);        
        $goodsLogic = new \Home\Logic\GoodsLogic();
        $goods_id = I("get.id");
        $goods = M('Goods')->where("goods_id = $goods_id")->find();
        if(empty($goods)){
        	$this->tp404('此商品不存在或者已下架');
        }
        if($goods['brand_id']){
            $brnad = M('brand')->where("id =".$goods['brand_id'])->find();
            $goods['brand_name'] = $brnad['name'];
        }
        $goods_images_list = M('GoodsImages')->where("goods_id = $goods_id")->select(); // 商品 图册        
        $goods_attribute = M('GoodsAttribute')->getField('attr_id,attr_name'); // 查询属性
        $goods_attr_list = M('GoodsAttr')->where("goods_id = $goods_id")->select(); // 查询商品属性表                        
		$filter_spec = $goodsLogic->get_spec($goods_id);  
        //商品是否正在促销中
        $goods['promoting'] = 0;
        if($goods['is_promote'] == 1){
        	if(time() > $goods['promote_start_time'] && $goods['promote_end_time']>time()){
        		$goods['promoting'] = 1;
        	}else{
        		$goods['promoting'] = 0;
        	}
        } 
        $spec_goods_price  = M('spec_goods_price')->where("goods_id = $goods_id")->getField("key,price,store_count"); // 规格 对应 价格 库存表
        M('Goods')->where("goods_id=$goods_id")->save(array('click_count'=>$goods['click_count']+1 )); //统计点击数
        $commentStatistics = $goodsLogic->commentStatistics($goods_id);// 获取某个商品的评论统计     
        $this->assign('spec_goods_price', json_encode($spec_goods_price,true)); // 规格 对应 价格 库存表
        $this->assign('navigate_goods',navigate_goods($goods_id,1));// 面包屑导航 
        $this->assign('commentStatistics',$commentStatistics);//评论概览
        $this->assign('goods_attribute',$goods_attribute);//属性值     
        $this->assign('goods_attr_list',$goods_attr_list);//属性列表
        $this->assign('filter_spec',$filter_spec);//规格参数
        $this->assign('goods_images_list',$goods_images_list);//商品缩略图
        $this->assign('siblings_cate',$goodsLogic->get_siblings_cate($goods['cat_id']));//相关分类
        $this->assign('look_see',$goodsLogic->get_look_see($goods));//看了又看      
        $this->assign('goods',$goods);
        $this->display();
    }
    /**
     *养老院详细页
     *
     */
    public function resthouseDetail(){
        $id = $_GET['id'];
        $r_arr = M('Resthouse')->where(array('resthouse_id'=>$id))->find();
        $this->assign('r_arr',$r_arr);
        $this->display();
    }
    /**
     *用户详细页
     *
     */
    public function userDetail(){
        $id = $_GET['id'];
        $user_arr = M('Users')->where(array('user_id'=>$id))->find();
        $this->assign('user_arr',$user_arr);
        $this->assign('sex',$user_arr['sex']);
        $this->display();
    }
    /**
     *提交用户留言内容
     *
     *
     *
     */
    public function addMessage()
    {
        $message = M('Message');
        $_POST['message_time'] =time();
        $_POST['to_userid'] =$_GET['id'];
        $re = $message->add($_POST);
        exit(json_encode($re));
    }
    /**
     * 更新点击数
     */
    public function updateActivity(){
        $id = $_POST['activity_id'];
        $num = $_POST['activity_looknum'];
        $re = M('Activity')->where(array('activity_id'=>$id))->save(array('activity_looknum'=>$num+1));
        exit(json_encode($re));
    }
    /**
     *活动详细页
     *
     */
    public function activityDetail(){
        $id = $_GET['id'];
        $arr = M('Activity')->where(array('activity_id'=>$id))->find();
        $user_arr = M('users')->where(array('user_id'=>$arr['user_id']))->find();
        $num =  M('Comment')->where(array('activity_id'=>$id))->count();
        $p = new Page($num,3);
        $show = $p->show();
        $c_arr = M('Comment')->where(array('activity_id'=>$id))->order('add_time desc')->limit($p->firstRow.','.$p->listRows)->select();
        $arr['activity_commentnum'] = $num;
        M('activity')->save($arr);
        $this->assign('page',$show);
        $this->assign('num',$num);
        $this->assign('c_arr',$c_arr);
        $this->assign('user_arr',$user_arr);
        $t1 = date("Y",$user_arr['birthday']);
        $t2 = date("Y",time());
        $age = $t2 -$t1;
        $this->assign('age',$age);
        $this->assign('arr',$arr);
        $this->display();
    }
    /**
     * 加载评论
     */
    public function addComment(){
        $user_info = session('user');
        $_POST = array(
             'user_id' => $this->user_id,
            'content'=>$_POST['content'],
            'activity_id'=>$_POST['activity_id'],
           // 'username'=>$user_info['nickname'],
            'add_time'=>time()
         );
        $re = M('comment')->add($_POST);
        exit(json_encode($re));
    }
    /**
     * 家政详细页
     */
    public function jzDetail(){
        $id = $_GET['id'];
        $life_arr = M('Life')->where(array('life_id'=>$id))->find();
        $arr = M('Life')->select();
        $this->assign('arr',$arr);
        $this->assign('life_arr',$life_arr);
        $this->display();
    }
    /**
     * 预约
     */
    public function order(){
        $_POST =array(
            'order_sn' => 'JZ'.date('YmdHis').rand(1000,9999),
            'order_time' => time(),
            'user_id' => $this->user_id,
           'order_hour'=>$_POST['order_hour'],
            'order_zone'=>$_POST['order_zone'],
            'order_address'=>$_POST['order_address'],
            'order_message'=>$_POST['order_message'],
            'order_phone'=>$_POST['order_phone'],
            'life_id'=>$_POST['life_id'],
            'life_name'=>$_POST['life_name'],
            'life_price'=>$_POST['life_price'],
        );
        $re = M('lifeorder')->add($_POST);
        exit(json_encode($re));
    }
     /**
     * 生活服务首页
     */
    public function lifeList(){
        $id = $_GET['id'];
        if(!$id){
            $_GET['id'] = 2;
        }
        $cat_arr = M('life_category')->where(array('parent_id'=>1))->select();
        if($_GET['id']==2) {
            $life_arr = M('life')->where(array('cat_id' => $_GET['id']))->select();
        }
        if($_GET['id']==3) {
            $condition = "user_id != $this->user_id";
            $count = M('Users')->where($condition)->count();
            $page = new Page($count,6);
            $life_arr = M('Users')->where($condition)->order('reg_time desc')->limit($page->firstRow.','.$page->listRows)->select();
            $show = $page->show();
            $num = M('activity')->count();
            $p = new Page($num,3);
            $act_arr = M('Activity')->alias('a')->join("tp_users as u on a.user_id = u.user_id ")->order('activity_addtime desc')->limit($p->firstRow.','.$p->listRows)->select();
            $show2 = $p->show();
        }
        if($_GET['id']==4) {
            $count = M('Resthouse')->count();
            $page = new Page($count,3);
            $life_arr = M('Resthouse')->order('resthouse_id desc')->limit($page->firstRow.','.$page->listRows)->select();
            $show = $page->show();
        }
        $list = M('lunbo')->where(array('name'=>4))->limit(3)->select();
        $this->assign('list',$list);
        $this->assign('act_arr',$act_arr);
        $this->assign('pages',$show2);
        $this->assign('page',$show);
        $this->assign('cat_arr',$cat_arr);
        $this->assign('life_arr',$life_arr);
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
                $cateArr[$k]['href'] = U("/Mobile/Goods/search",$tmp);
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
    
    /**
     * 商品咨询ajax分页
     */
    public function ajaxConsult(){        
        $goods_id = I("goods_id",'0');        
        $consult_type = I('consult_type','0'); // 0全部咨询  1 商品咨询 2 支付咨询 3 配送 4 售后
                 
        $where = " parent_id = 0 and goods_id = $goods_id";
        if($consult_type > 0)
            $where .= " and consult_type = $consult_type ";
        
        $count = M('GoodsConsult')->where($where)->count();        
        $page = new AjaxPage($count,5);
        $show = $page->show();        
        $list = M('GoodsConsult')->where($where)->order("id desc")->limit($page->firstRow.','.$page->listRows)->select();
        $replyList = M('GoodsConsult')->where("parent_id > 0")->order("id desc")->select();
        $this->assign('consultCount',$count);// 商品咨询数量
        $this->assign('consultList',$list);// 商品咨询
        $this->assign('replyList',$replyList); // 管理员回复
        $this->assign('page',$show);// 赋值分页输出        
        $this->display();        
    }
    

    /**
     *  商品咨询
     */
    public function goodsConsult(){
        //  form表单提交
        C('TOKEN_ON',true);        
        $goods_id = I("goods_id",'0'); // 商品id
        $consult_type = I("consult_type",'1'); // 商品咨询类型
        $username = I("username",'TPshop用户'); // 网友咨询
        $content = I("content"); // 咨询内容
        
        $verify = new Verify();
        if (!$verify->check(I('post.verify_code'),'consult')) {
            $this->error("验证码错误");
        }
        
        $goodsConsult = M('goodsConsult');        
        if (!$goodsConsult->autoCheckToken($_POST))
        {            
                $this->error('你已经提交过了!', U('/Mobile/Goods/goodsInfo',array('id'=>$goods_id)));
                exit;
        }
        
        $data = array(
            'goods_id'=>$goods_id,
            'consult_type'=>$consult_type,
            'username'=>$username,
            'content'=>$content,
            'add_time'=>time(),
        );        
        $goodsConsult->add($data);        
        $this->success('咨询已提交!', U('/Mobile/Goods/goodsInfo',array('id'=>$goods_id)));
    }

}