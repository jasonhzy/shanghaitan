<?php
namespace Mobile\Controller;

class CartController extends MobileBaseController {
    
    public $cartLogic; // 购物车逻辑操作类
    
    /**
     * 初始化函数
     */
    public function _initialize() {       
        parent::_initialize();
        
        $user = session('user');
        // 给用户计算会员价 登录前后不一样
        if($user)
            M('Cart')->execute("update `__PREFIX__cart` set member_goods_price = goods_price * {$user[discount]} where (user_id ={$user[user_id]} or session_id = '{$this->session_id}') and activity_type = 0");        
    }


    public function cart(){
        $this->display();
    }
    public function excart(){
        $this->display();
    }
    
    public function index(){
    	$this->display('cart');
    }

    /**
     * ajax 将商品加入购物车
     */
    public function ajaxAddCart(){
        $goods_id = I("goods_id"); // 商品id
        $goods_num = I("goods_num");// 商品数量
        $goods_spec = I("color"); // 商品规格
       $result = $this->cartLogic->addCart($goods_id, $goods_num, $goods_spec,$this->session_id,$this->user_id); // 将商品加入购物车
        exit(json_encode($result));
    }
    /**
     * ajax 将商品加入购物车
     */
    public function ajaxExchange(){
        $goods_id = I("goods_id"); // 商品id
        $goods_num = I("goods_num");// 商品数量
        $result = $this->cartLogic->addexCart($goods_id, $goods_num,$this->session_id,$this->user_id); // 将商品加入购物车
        exit(json_encode($result));
    }
    /**
     * ajax 删除购物车的商品
     */
    public function ajaxDelCart()
    {       
        $ids = I("ids"); // 商品 ids        
        $result = M("Cart")->where(" id in ($ids)")->delete(); // 删除id为5的用户数据
        $return_arr = array('status'=>1,'msg'=>'删除成功','result'=>$result); // 返回结果状态
        exit(json_encode($return_arr));
    }
    public function ajaxDelExcart()
    {
        $ids = I("ids"); // 商品 ids
        $result = M("excart")->where(" id in ($ids)")->delete(); // 删除id为5的用户数据
        $return_arr = array('status'=>1,'msg'=>'删除成功','result'=>$result); // 返回结果状态
        exit(json_encode($return_arr));
    }
    
    /*
     * ajax 请求获取购物车列表
     */
    public function ajaxCartList()
    {    
        $post_goods_num = I("goods_num"); // goods_num 购物车商品数量
        $post_cart_select = I("cart_select"); // 购物车选中状态                               
        $where = " session_id = '$this->session_id' "; // 默认按照 session_id 查询
        $this->user_id && $where = " user_id = ".$this->user_id; // 如果这个用户已经等了则按照用户id查询
        
        $cartList = M('Cart')->where($where)->getField("id,goods_num,selected"); 
        
        if($post_goods_num)
        {
            // 修改购物车数量 和勾选状态
            foreach($post_goods_num as $key => $val)
            {   
                $data['goods_num'] = $val;
                $data['selected'] = $post_cart_select[$key] ? 1 : 0 ;                               
                if(($cartList[$key]['goods_num'] != $data['goods_num']) || ($cartList[$key]['selected'] != $data['selected'])) 
                    M('Cart')->where("id = $key")->save($data);
            }
            $this->assign('select_all', $_POST['select_all']); // 全选框
        }
        
        
        $result = $this->cartLogic->cartList($this->user, $this->session_id,1);
        $result_select = $this->cartLogic->cartList($this->user, $this->session_id,1); // 选中的商品
        if(empty($result_select['total_price']))
            $result_select['total_price'] = Array( 'total_fee' =>0, 'cut_fee' =>0, 'num' => 0, 'atotal_fee' =>0, 'acut_fee' =>0, 'anum' => 0);
        $this->assign('cartList', $result['cartList']); // 购物车的商品                
        $this->assign('total_price', $result_select['total_price']); // 总计       
        $this->display('ajax_cart_list');
    }
    /*
    * ajax 请求获取购物车列表
    */
    public function ajaxExcartList()
    {
        $post_goods_num = I("goods_num"); // goods_num 购物车商品数量
        $where = " session_id = '$this->session_id' "; // 默认按照 session_id 查询
        $this->user_id && $where = " user_id = ".$this->user_id; // 如果这个用户已经等了则按照用户id查询

        $cartList = M('excart')->where($where)->getField("id,goods_num");
        //var_dump($cartList);exit;
        if($post_goods_num)
        {
            // 修改购物车数量
            foreach($post_goods_num as $key => $val)
            {
                $data['goods_num'] = $val;
                if(($cartList[$key]['goods_num'] != $data['goods_num']))
                    M('excart')->where("id = $key")->save($data);
            }
            $this->assign('select_all', $_POST['select_all']); // 全选框
        }


        $result = $this->cartLogic->carList($this->user, $this->session_id,1);
       //var_dump($result);exit;
        $this->assign('cartList', $result['cartList']); // 购物车的商品
        $this->display('ajax_excart_list');
    }
    /**
     * 购物车第二步确定页面
     */
    public function cart2()
    {        
        
        if($this->user_id == 0)
            $this->error('请先登陆',U('Mobile/User/login'));
        
        /*if($this->cartLogic->cart_count($this->user_id,1) == 0 )
            $this->error ('你的购物车没有选中商品','Cart/cart');*/
        
        $result = $this->cartLogic->cartList($this->user, $this->session_id,1); // 获取购物车商品
        //$shippingList = M('Plugin')->where("`type` = 'shipping' and status = 1")->select();// 物流公司
        
        //$Model = new \Think\Model(); // 找出这个用户的优惠券 没过期的  并且 订单金额达到 condition 优惠券指定标准的
        //$sql = "select c1.name,c1.money,c1.condition, c2.* from __PREFIX__coupon as c1 inner join __PREFIX__coupon_list as c2  on c2.cid = c1.id and c1.type in(1,2,3) where c2.uid = {$this->user_id} and ".time()." > c1.use_start_time  and ".time()." < c1.use_end_time and c1.condition <= {$result['total_price']['total_fee']}";
        //$couponList = $Model->query($sql);
               
        //$this->assign('couponList', $couponList); // 优惠券列表
       // $this->assign('shippingList', $shippingList); // 物流公司
        $total = $result['total_price']['total_fee'];
        $points = round($total/10);
        $this->assign('points', $points);
        $this->assign('cartList', $result['cartList']); // 购物车的商品                
        $this->assign('total_price', $result['total_price']); // 总计                               
        $this->display();
    }
    public function excart2()
    {

        if($this->user_id == 0)
            $this->error('请先登陆',U('Mobile/User/login'));

        $result = $this->cartLogic->carList($this->user, $this->session_id,1); // 获取购物车商品
        $this->assign('cartList', $result['cartList']); // 购物车的商品
        $this->assign('total_price', $result['total_price']); // 总计
        $this->display();
    }
    /*
     * ajax 获取用户收货地址 用于购物车确认订单页面
     */
    public function ajaxAddress(){                               
        $address_list = M('UserAddress')->where("user_id = {$this->user_id}")->select();
        //var_dump($address_list);exit;
        if($address_list){
        	$area_id = array();
        	foreach ($address_list as $val){
        		$area_id[] = $val['province'];
                        $area_id[] = $val['city'];
                        $area_id[] = $val['district'];
                        $area_id[] = $val['twon'];                        
        	}    
                $area_id = array_filter($area_id);
        	$area_id = implode(',', $area_id);
        	$regionList = M('region')->where("id in ($area_id)")->getField('id,name');
        	$this->assign('regionList', $regionList);
        }
        $c = M('UserAddress')->where("user_id = {$this->user_id} and is_default = 1")->count(); // 看看有没默认收货地址        
        if((count($address_list) > 0) && ($c == 0)) // 如果没有设置默认收货地址, 则第一条设置为默认收货地址
            $address_list[0]['is_default'] = 1;
                     
        $this->assign('address_list', $address_list);
        $this->display('ajax_address');
    }
    
	
    /**
     * ajax 获取订单商品价格 或者提交 订单
     */
    public function cart3(){
        if($this->user_id == 0)
            exit(json_encode(array('status'=>-100,'msg'=>"登录超时请重新登录!",'result'=>null))); // 返回结果状态
        $address_id = I("address_id"); //  收货地址id
        //var_dump($address_id);exit;
       $shipping_code =  I("shipping_code"); //  物流编号
        $invoice_title = I('invoice_title'); // 发票
        $couponTypeSelect =  I("couponTypeSelect"); //  优惠券类型  1 下拉框选择优惠券 2 输入框输入优惠券代码
        $coupon_id =  I("coupon_id"); //  优惠券id
        $couponCode =  I("couponCode"); //  优惠券代码
        $pay_points =  I("pay_points",0); //  使用积分
        $user_money =  I("user_money",0); //  使用余额
        $user_money = $user_money ? $user_money : 0;

        //if($this->cartLogic->cart_count($this->user_id,1) == 0 ) exit(json_encode(array('status'=>-2,'msg'=>'你的购物车没有选中商品','result'=>null))); // 返回结果状态
        if(!$address_id) exit(json_encode(array('status'=>-3,'msg'=>'请先填写收货人信息','result'=>null))); // 返回结果状态
        //if(!$shipping_code) exit(json_encode(array('status'=>-4,'msg'=>'请选择物流信息','result'=>null))); // 返回结果状态
		
		$address = M('UserAddress')->where("address_id = $address_id")->find();
		$order_goods = M('cart')->where("user_id = {$this->user_id}")->select();
        $result = calculate_price($this->user_id,$order_goods,0,$shipping_price=0,$address[province],$address[city],$address[district],$pay_points,$user_money,$coupon_id,$couponCode);
                
		if($result['status'] < 0)	
			json_encode($result);
        $car_price = array(
           'postFee'      => $result['result']['shipping_price'], // 物流费
             'couponFee'    => $result['result']['coupon_price'], // 优惠券
            'balance'      => $result['result']['user_money'], // 使用用户余额
            'pointsFee'    => $result['result']['integral_money'], // 积分支付
            'payables'     => $result['result']['order_amount'], // 应付金额
            'goodsFee'     => $result['result']['goods_price'],// 商品价格
        );
       
        // 提交订单        
        if($_REQUEST['act'] == 'submit_order')
        {            
            $result = $this->cartLogic->addOrder($this->user_id,$address_id,$shipping_code,$invoice_title,$coupon_id,$car_price); // 添加订单
            exit(json_encode($result));            
        }
            $return_arr = array('status'=>1,'msg'=>'计算成功','result'=>$car_price); // 返回结果状态
            exit(json_encode($return_arr));

    }
    public function excart3(){
        if($this->user_id == 0)
            exit(json_encode(array('status'=>-100,'msg'=>"登录超时请重新登录!",'result'=>null))); // 返回结果状态
        $address_id = I("address_id"); //  收货地址id
        if(!$address_id) exit(json_encode(array('status'=>-3,'msg'=>'请先填写收货人信息','result'=>null))); // 返回结果状态
        //$address = M('UserAddress')->where("address_id = $address_id")->find();
        $order_goods = M('excart')->where("user_id = {$this->user_id}")->select();
        $result = call_price($this->user_id,$order_goods);
        if($result['status'] < 0)
            json_encode($result);
        $car_price = array(
            'payables'     => $result['result']['order_amount'], // 应付金额
            'goodsFee'     => $result['result']['goods_price'],// 商品价格
            'pay_points'   =>$result['result']['pay_points'],
        );
        if($car_price['goodsFee'] >$car_price['pay_points'])
        {
            $result = array('status'=>-3,'msg'=>'积分不足','result'=>''); // 返回结果状态
            exit(json_encode($result));
        }
        // 提交订单
        if($_REQUEST['act'] == 'submit_order')
        {
            $result = $this->cartLogic->addexOrder($this->user_id,$address_id,$car_price); // 添加订单
            //var_dump($result);exit;
            exit(json_encode($result));
        }
        $return_arr = array('status'=>1,'msg'=>'计算成功','result'=>$car_price); // 返回结果状态
        exit(json_encode($return_arr));

    }
    /**
     * ajax 获取订单商品价格 或者提交 订单
	 * 已经用心方法 这个方法 cart9  准备作废
     */
   
    /*
     * 订单支付页面
     */
    public function cart4(){

        $order_id = I('order_id');        
        $order = M('Order')->where("order_id = $order_id")->find();
        $s_points = round($order['order_amount']/10);
        // 如果已经支付过的订单直接到订单详情页面. 不再进入支付页面
        if($order['pay_status'] == 1){
            $arr = M('users')->where(array('user_id'=>$this->user_id))->find();
            $points = $arr['pay_points']+$s_points;
            $total = $arr['total_amount'] + $order['order_amount'];
            M('users')->where(array('user_id'=>$this->user_id))->save(array('pay_points'=>$points,'total_amount'=>$total));
            $order_detail_url = U("Mobile/User/order_detail",array('id'=>$order_id));
            header("Location: $order_detail_url");
        }      
        
        $paymentList = M('Plugin')->where("`type`='payment' and status = 1 and  scenario=2")->select();
        $paymentList = convert_arr_key($paymentList, 'code');
        foreach($paymentList as $key => $val)
        {
            $val['config_value'] = unserialize($val['config_value']);            
            if($val['config_value']['is_bank'] == 2)
            {
                $bankCodeList[$val['code']] = unserialize($val['bank_code']);        
            }                
        }                
        
        $bank_img = include 'Application/Home/Conf/bank.php'; // 银行对应图片        
        $payment = M('Plugin')->where("`type`='payment' and status = 1")->select();        
        $this->assign('paymentList',$paymentList);        
        $this->assign('bank_img',$bank_img);
        $this->assign('order',$order);
        $this->assign('bankCodeList',$bankCodeList);        
        $this->assign('pay_date',date('Y-m-d', strtotime("+1 day")));
        $this->display();                   
    }
    public function excart4(){
        $order_id = I('order_id');
        $order = M('exchange')->where("order_id = $order_id")->find();
        $this->assign('order',$order);
        $this->display();
    }
    //ajax 请求购物车列表
    public function header_cart_list()
    {
        $cart_result = $this->cartLogic->cartList($this->user, $this->session_id,1);
        if(empty($cart_result['total_price']))
            $cart_result['total_price'] = Array( 'total_fee' =>0, 'cut_fee' =>0, 'num' => 0);

        $re = $cart_result['total_price']['anum'];
        $this->ajaxReturn(json_encode($re));

    }
}
