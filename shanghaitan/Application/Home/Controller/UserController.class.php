<?php
namespace Home\Controller;
use Home\Logic\UsersLogic;
use Think\Page;
use Think\Verify;

class UserController extends BaseController {

    /*
     * 用户中心首页
     */
    public function index(){
        $logic = new UsersLogic();
        $user = $logic->get_info($this->user_id);
        $user = $user['result'];
        $level = M('user_level')->select();
        $level = convert_arr_key($level,'level_id');
        $this->assign('level',$level);
        $this->assign('user',$user);
        $this->display();
    }
    /**
     * 会员介绍
     */
    public function vipIntroduce(){
        $this->display();
    }
    /**
     * 充值记录
     */
    public function recharge(){
        $count = M('recharge')->where(array('user_id'=>$this->user_id,'pay_status'=>1))->count();
        $Page = new Page($count,5);
        $show = $Page->show();
        $recharge_list = M('recharge')->where(array('user_id'=>$this->user_id,'pay_status'=>1))->limit($Page->firstRow.','.$Page->listRows)->select();
        $r_arr = M('recharge')->where(array('user_id'=>$this->user_id,'pay_status'=>1))->select();
        $total='';
        foreach($r_arr as $k=>$v){
            $total += $v['account'];
        }
        $date = date("Y-m-d");
        $this->assign('date',$date);
        $user = M('Users')->where(array('user_id'=>$this->user_id))->find();
        $this->assign('user',$user);
        $this->assign('count',$count);
        $this->assign('total',$total);
        $this->assign('page',$show);
        $this->assign('recharge_list',$recharge_list);//充值记录
        $this->display();
    }
    /**
     *
     */
    public function ajaxRecharge(){
        $begin = I('begin');
        $end = I('end');
        // 搜索条件
        $condition = array();
        if($begin && $end){
            $condition['add_time'] = array('between',"$begin,$end");
        }
        $condition['user_id'] = array('user_id'=>$this->user_id);
        $condition['pay_status'] = array('pay_status'=>1);
        $count = M('recharge')->where($condition)->count();
        $Page  = new Page($count,20);
        //  搜索条件下 分页赋值
        foreach($condition as $key=>$val) {
            $Page->parameter[$key]   =  urlencode($val);
        }
        $show = $Page->show();
        //获取充值记录
        $rechargeList = M('recharge')->where($condition)->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('rechargeList',$rechargeList);
        $this->assign('count',$count);
        $this->assign('page',$show);// 赋值分页输出
        $this->display();
    }
    /*
   * 用户充值
   */
    public function vipPay(){
        if(IS_POST){
            $user = session('user');
            $data['user_id'] = $this->user_id;
            $data['nickname'] = $user['nickname'];
            $data['account'] = I('account');
            $data['order_sn'] = 'recharge'.get_rand_str(6,1);
            $data['ctime'] = time();
            $order_id = M('recharge')->add($data);
            if($order_id){
                $url = U('Payment/getPay',array('pay_radio'=>$_REQUEST['pay_radio'],'order_id'=>$order_id));
                redirect($url);
            }else{
                $this->error('提交失败,参数有误!');
            }
        }
        M('recharge')->where(array('order_id'=>$_GET['order_id']))->save(array('pay_status'=>2));
        $paymentList = M('Plugin')->where("`type`='payment' and status = 1 and  scenario in(0,2)")->select();
        $paymentList = convert_arr_key($paymentList, 'code');
        //var_dump($paymentList);exit;
        foreach($paymentList as $key => $val)
        {
            $val['config_value'] = unserialize($val['config_value']);
            if($val['config_value']['is_bank'] == 2)
            {
                $bankCodeList[$val['code']] = unserialize($val['bank_code']);
            }
        }
        $bank_img = include 'Application/Home/Conf/bank.php'; // 银行对应图片
        $this->assign('paymentList',$paymentList);
        $this->assign('bank_img',$bank_img);
        $this->assign('bankCodeList',$bankCodeList);
        $this->display();


     /*   $count2 = M('account_log')->where(array('user_id'=>$this->user_id,'user_money'=>array('gt',0)))->count();
        $Page2 = new Page($count2,10);
        $consume_list = M('account_log')->where(array('user_id'=>$this->user_id,'user_money'=>array('gt',0)))->limit($Page2->firstRow.','.$Page2->listRows)->select();
        $this->assign('consume_list',$consume_list);//消费记录
        $this->assign('page2',$Page2->show());
        $this->display();*/
    }

    public function logout(){
    	setcookie('uname','',time()-3600,'/');
        session_unset();
        session_destroy();
        //$this->success("退出成功",U('Home/Index/index'));
        header("location:".U('Home/Index/index'));
        exit;
    }

    /*
     * 账户资金
     */
    public function account(){
        $user = session('user');
        //获取账户资金记录
        $logic = new UsersLogic();
        $data = $logic->get_account_log($this->user_id,I('get.type'));
        $account_log = $data['result'];

        $this->assign('user',$user);
        $this->assign('account_log',$account_log);
        $this->assign('page',$data['show']);
        $this->assign('active','account');
        $this->display();
    }
    /*
     * 优惠券列表
     */
    public function coupon(){
        $logic = new UsersLogic();
        $data = $logic->get_coupon($this->user_id,$_REQUEST['type']);
        $coupon_list = $data['result'];
        $this->assign('coupon_list',$coupon_list);
        $this->assign('page',$data['show']);
        $this->assign('active','coupon');
        $this->display();
    }
    /**
     *  登录
     */
    public function login(){
        if($this->user_id > 0){
        	header("Location: ".U('Home/User/Index'));
        }           
        $referurl = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : U("Home/User/index");
        $this->assign('referurl',$referurl);
        $this->display();
    }

    public function pop_login(){
    	if($this->user_id > 0){
    		header("Location: ".U('Home/User/Index'));
    	}
        $referurl = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : U("Home/User/index");
        $this->assign('referurl',$referurl);
    	$this->display();
    }
    
    public function do_login(){
    	$username = I('post.username');
    	$password = I('post.password');
    	$logic = new UsersLogic();
    	$res = $logic->login($username,$password);
    	if($res['status'] == 1){
    		$res['url'] =  urldecode(I('post.referurl'));
    		session('user',$res['result']);
    		session('user_id',$res['result']['user_id']);
    		$nickname = empty($res['result']['nickname']) ? $username : $res['result']['nickname'];
            setcookie('uname',$nickname,null,'/');
    		$cartLogic = new \Home\Logic\CartLogic();
    		$cartLogic->login_cart_handle($this->session_id,$res['result']['user_id']);  //用户登录后 需要对购物车 一些操作
    	}
    	exit(json_encode($res));
    }
    /**
     *  注册
     */
    public function reg(){
    	if($this->user_id > 0) header("Location: ".U('Home/User/Index'));
    	
        if(IS_POST){
            $logic = new UsersLogic();
            $username = I('post.username','');
            $password = I('post.password','');
            $password2 = I('post.password2','');
            $mobile = I('post.mobile','');
            //是否开启注册验证码机制
            if(check_mobile($mobile)){
                $code = I('post.code','');
                if(!$code)
                    $this->error('请输入短信验证码');
                $check_code = $logic->sms_code_verify($mobile,$code,$this->session_id);
                if($check_code['status'] != 1)
                    $this->error($check_code['msg']);
            }
            $data = $logic->reg($username,$password,$password2,$mobile);
            if($data['status'] != 1)
                $this->error($data['msg']);
            session('user',$data['result']);
            session('user_id',$data['result']['user_id']);
            $nickname = empty($data['result']['nickname']) ? $username : $data['result']['nickname'];
            setcookie('uname',$nickname,null,'/');
            $this->success($data['msg'],U('Home/User/index'));
            exit;
        }
        $sms_time_out = 120;
        $this->assign('sms_time_out', $sms_time_out); // 手机短信超时时间
        $this->display();
    }

    /*
     * 订单列表
     */
    public function myOrder(){
        $where = ' user_id='.$this->user_id;
        //条件搜索
        if(I('get.type')){
            $where .= C(strtoupper(I('get.type')));
        }
        // 搜索订单 根据商品名称 或者 订单编号
        //$search_key = trim(I('search_key'));
        $search_key = trim($_POST['search_key']);
        if($search_key)
        {
            $where .= " and (order_sn like '%$search_key%' or order_id in (select order_id from `".C('DB_PREFIX')."order_goods` where goods_name like '%$search_key%') ) ";
        }
       //var_dump($search_key);exit;
        $count = M('order')->where($where)->count();
        $Page = new Page($count,5);

        $show = $Page->show();
        $order_str = "order_id DESC";
        $order_list = M('order')->order($order_str)->where($where)->limit($Page->firstRow.','.$Page->listRows)->select();

        //获取订单商品
        $model = new UsersLogic();
        foreach($order_list as $k=>$v)
        {
            $order_list[$k] = set_btn_order_status($v);  // 添加属性  包括按钮显示属性 和 订单状态显示属性
            //$order_list[$k]['total_fee'] = $v['goods_amount'] + $v['shipping_fee'] - $v['integral_money'] -$v['bonus'] - $v['discount']; //订单总额
            $data = $model->get_order_goods($v['order_id']);
            $order_list[$k]['goods_list'] = $data['result'];
        }
        $this->assign('order_status',C('ORDER_STATUS'));
        $this->assign('shipping_status',C('SHIPPING_STATUS'));
        $this->assign('pay_status',C('PAY_STATUS'));
        $this->assign('page',$show);
        $this->assign('lists',$order_list);
        $this->assign('active','order_list');
        $this->assign('active_status',I('get.type'));
        $this->display();
    }

    /*
     * 订单详情
     */
    public function order_detail(){
        $id = I('get.id');

        $map['order_id'] = $id;
        $map['user_id'] = $this->user_id;
        $order_info = M('order')->where($map)->find();
        $order_info = set_btn_order_status($order_info);  // 添加属性  包括按钮显示属性 和 订单状态显示属性
        
        if(!$order_info){
            $this->error('没有获取到订单信息');
            exit;
        }
        //获取订单商品
        $model = new UsersLogic();
        $data = $model->get_order_goods($order_info['order_id']);
        $order_info['goods_list'] = $data['result'];
        $order_info['total_fee'] = $order_info['goods_price'] + $order_info['shipping_price'] - $order_info['integral_money'] -$order_info['coupon_price'] - $order_info['discount'];
        //获取订单进度条
        $sql = "SELECT action_id,log_time,status_desc,order_status FROM ((SELECT * FROM __PREFIX__order_action WHERE order_id = 101 AND status_desc <>'' ORDER BY action_id) AS a) GROUP BY status_desc ORDER BY action_id";
        $items = M()->query($sql);
        $items_count = count($items);
        $region_list = get_region_list();
        //获取订单操作记录
        $order_action = M('order_action')->where(array('order_id'=>$id))->select();
        $this->assign('order_status',C('ORDER_STATUS'));
        $this->assign('shipping_status',C('SHIPPING_STATUS'));
        $this->assign('pay_status',C('PAY_STATUS'));
        $this->assign('region_list',$region_list);
        $this->assign('order_info',$order_info);
        $this->assign('order_action',$order_action);
        $this->assign('active','order_list');
        $this->display();
    }

    /*
     * 取消订单
     */
    public function cancel_order(){
        $id = I('get.id');
        //检查是否有积分，余额支付
        $logic = new UsersLogic();
        $data = $logic->cancel_order($this->user_id,$id);
        if($data['status'] < 0)
            $this->error($data['msg']);
        $this->success($data['msg']);
    }
    /**
     * 我的兑换
     */
    public function myExchange(){
        $where = ' user_id='.$this->user_id;
        $count = M('exchange')->where($where)->count();
        $Page = new Page($count,5);

        $show = $Page->show();
        $order_str = "order_id DESC";
        $order_list = M('exchange')->order($order_str)->where($where)->limit($Page->firstRow.','.$Page->listRows)->select();

        //获取订单商品
        $model = new UsersLogic();
        foreach($order_list as $k=>$v)
        {
            $order_list[$k] = set_btn_order_status($v);  // 添加属性  包括按钮显示属性 和 订单状态显示属性
            $data = $model->get_exorder_goods($v['order_id']);
            $order_list[$k]['goods_list'] = $data['result'];
        }
        //var_dump($order_list);exit;
        $this->assign('order_status',C('ORDER_STATUS'));
        $this->assign('shipping_status',C('SHIPPING_STATUS'));
        $this->assign('pay_status',C('PAY_STATUS'));
        $this->assign('page',$show);
        $this->assign('lists',$order_list);
        $this->display();
    }
    public function ajaxStatus(){
        $id = $_POST['id'];
        $re = M('exchange')->where(array('order_id'=>$id))->save(array('order_status'=> 2));
        $this->ajaxReturn(json_encode($re));
    }
    /**
     * 我的健康
     */
    public function myHealth(){
        $user_id = $this->user_id;
        $arr = M('health')->where(array('user_id'=>$user_id))->select();
        $arr2 = array();
        foreach($arr as $k=>$v){
            $arr2[$k] = $v['hearth_id'];
        }
        $id = max($arr2);
        //var_dump($arr2);exit;
        $h_arr = M('health')->where(array('hearth_id'=>$id))->find();
        $this->assign('h_arr',$h_arr);
        $this->display();
    }
    public function chart(){
        $user_id = $this->user_id;
        $_POST = array(
            'height'=>$_POST['height'],
            'weight'=>$_POST['weight'],
            'blood'=>$_POST['blood'],
            'bloodfat'=>$_POST['bloodfat'],
            'bloodsugar'=>$_POST['bloodsugar'],
            'user_id'=>$user_id,
            'add_time'=>time()
        );

        $re = M('health')->add($_POST);
        //var_dump($re).exit;
        $this->ajaxReturn(json_encode($re));
    }
    /*
    * 我的预约
    */
    public function myAppointment(){
        $user_id = $this->user_id;
        $count = M('lifeorder')->where(array('user_id'=>$user_id))->count();
        $Page = new Page($count,3);
        $show1 = $Page->show();
        $l_arr = M('lifeorder')->where(array('user_id'=>$user_id))->order("order_id desc")->limit($Page->firstRow.','.$Page->listRows)->select();
        $num = M('travelorder')->where(array('user_id'=>$user_id))->count();
        $Pages = new Page($num,3);
        $show2 = $Pages->show();
        $t_arr = M('travelorder')->where(array('user_id'=>$user_id))->order("order_id desc")->limit($Pages->firstRow.','.$Pages->listRows)->select();
       // var_dump($t_arr);exit;
        $num2 = M('mudiorder')->where(array('user_id'=>$user_id))->count();
        $Page2 = new Page($num2,3);
        $show3 = $Page2->show();
        $w_arr = M('mudiorder')->where(array('user_id'=>$user_id))->order("order_id desc")->limit($Page2->firstRow.','.$Page2->listRows)->select();
        $this->assign('page',$show1);
        $this->assign('pages',$show2);
        $this->assign('page2',$show3);
        $this->assign('l_arr',$l_arr);
        $this->assign('t_arr',$t_arr);
        $this->assign('w_arr',$w_arr);
        $this->display();
    }
    /*
   * 我的留言
   */
    public function myMessage(){
        $user_id = $this->user_id;
        if($user_id == 0){
            //$this->redirect(U('Home/User/login'),'',3,'您还没有登录，请登录');
            $this->error('您还没有登录，请登录', U('Home/User/login'));
        }
        $count = M('message')->where(array('user_id'=>$user_id,'state'=>1))->count();
        $Page = new Page($count,3);
        $show1 = $Page->show();
        $arr = M('message')->where(array('user_id'=>$user_id,'state'=>1))->order("time desc")->limit($Page->firstRow.','.$Page->listRows)->select();

        $this->assign('page',$show1);
        $this->assign('arr',$arr);
        $this->display();
    }
    public function ajaxMessage(){
        $user_info = session('user');
        $_POST = array(
            'user_id'=>$this->user_id,
            'title'=>$_POST['title'],
            'content'=>$_POST['content'],
            'username'=>$user_info['nickname'],
            'time'=>time(),
            'state'=> 1,
            'ip_address' => $_SERVER['REMOTE_ADDR'],
            'type'=>$_POST['type']
        );
        //var_dump($_POST);exit;
        $re = M('message')->add($_POST);
        exit(json_encode($re));
    }
    /**
      * 留言详情
      */
    public function msgDetail(){
        $arr = M('message')->where(array('message_id'=>$_GET['id']))->find();
        $this->assign('arr',$arr);
        $this->display();
    }
    /**
     * 社交留言
     */
    public function message(){
        $count = M('message')->where(array('to_userid'=>$this->user_id))->count();
        $Page = new Page($count,3);
        $show = $Page->show();
        $arr = M('message')->where(array('to_userid'=>$this->user_id))->order('time desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('page',$show);
        $this->assign('arr',$arr);
        $this->display();
    }
    /**
     * 我的活动
     */
    public function myActivity(){
        $count = M('activity')->where(array('user_id'=>$this->user_id))->count();
        $Page = new Page($count,3);
        $show = $Page->show();
        $arr = M('activity')->where(array('user_id'=>$this->user_id))->order('activity_name desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('page',$show);
        $this->assign('arr',$arr);
        $this->display();
    }
    public function ajaxActivity(){
        $_POST['user_id']=$this->user_id;
        $_POST['activity_addtime']=time();
        $re = M('activity')->add($_POST);
        exit(json_encode($re));
    }
    /*
     * 用户地址列表
     */
    public function address_list(){
        $address_lists = get_user_address_list($this->user_id);
        $region_list = get_region_list();
        $this->assign('region_list',$region_list);
        $this->assign('lists',$address_lists);
        $this->assign('active','address_list');

        $this->display();
    }
    /*
     * 添加地址
     */
    public function add_address(){
        header("Content-type:text/html;charset=utf-8");
        if(IS_POST){
            $logic = new UsersLogic();
            $data = $logic->add_address($this->user_id,0,I('post.'));
            if($data['status'] != 1)
                exit('<script>alert("'.$data['msg'].'");history.go(-1);</script>');
            $call_back = $_REQUEST['call_back'];
            echo "<script>parent.{$call_back}('success');</script>";
            exit(); // 成功 回调closeWindow方法 并返回新增的id
        }
        $p = M('region')->where(array('parent_id'=>0,'level'=> 1))->select();
        $this->assign('province',$p);
        $this->display('edit_address');

    }

    /*
     * 地址编辑
     */
    public function edit_address(){
        header("Content-type:text/html;charset=utf-8");
        $id = I('get.id');
        $address = M('user_address')->where(array('address_id'=>$id,'user_id'=> $this->user_id))->find();
        if(IS_POST){
            $logic = new UsersLogic();
            $data = $logic->add_address($this->user_id,$id,I('post.'));
            if($data['status'] != 1)
                exit('<script>alert("'.$data['msg'].'");history.go(-1);</script>');

            $call_back = $_REQUEST['call_back'];
            echo "<script>parent.{$call_back}('success');</script>";
            exit(); // 成功 回调closeWindow方法 并返回新增的id
        }
        //获取省份
        $p = M('region')->where(array('parent_id'=>0,'level'=> 1))->select();
        $c = M('region')->where(array('parent_id'=>$address['province'],'level'=> 2))->select();
        $d = M('region')->where(array('parent_id'=>$address['city'],'level'=> 3))->select();
        if($address['twon']){
        	$e = M('region')->where(array('parent_id'=>$address['district'],'level'=>4))->select();
        	$this->assign('twon',$e);
        }

        $this->assign('province',$p);
        $this->assign('city',$c);
        $this->assign('district',$d);
        $this->assign('address',$address);
        $this->display();
    }

    /*
     * 设置默认收货地址
     */
    public function set_default(){
        $id = I('get.id');
        M('user_address')->where(array('user_id'=>$this->user_id))->save(array('is_default'=>0));
        $row = M('user_address')->where(array('user_id'=>$this->user_id,'address_id'=>$id))->save(array('is_default'=>1));
        if(!$row)
            $this->error('操作失败');
        $this->success("操作成功");
    }

    /*
     * 地址删除
     */
    public function del_address(){
        $id = I('get.id');
        $row = M('user_address')->where(array('user_id'=>$this->user_id,'address_id'=>$id))->delete();
        if(!$row)
            $this->error('操作失败');
        $this->success("操作成功");
    }

    /*
     * 评论晒单
     */
    public function comment(){
        $user_id = $this->user_id;
        $status = I('get.status');
        $logic = new UsersLogic();
        $data = $logic->get_comment($user_id,$status); //获取评论列表
        $this->assign('page',$data['show']);// 赋值分页输出
        $this->assign('comment_list',$data['result']);
        $this->assign('count1',$data['count1']);
        $this->assign('count2',$data['count2']);
        $this->assign('active','comment');
        $this->display();
    }

    /*
     *添加评论
     */
    public function add_comment()
    {          
            $user_info = session('user');
            $comment_img = serialize(I('comment_img')); // 上传的图片文件            
            $add['goods_id'] = I('goods_id');
            $add['email'] = $user_info['email'];
            //$add['nick'] = $user_info['nickname'];
            $add['username'] = $user_info['nickname'];
            $add['order_id'] = I('order_id');
            $add['service_rank'] = I('service_rank');
            $add['deliver_rank'] = I('deliver_rank');
            $add['goods_rank'] = I('goods_rank');
            //$add['content'] = htmlspecialchars(I('post.content'));
            $add['content'] = I('content');
            $add['img'] = $comment_img;
            $add['add_time'] = time();
            $add['ip_address'] = $_SERVER['REMOTE_ADDR'];
            $add['user_id'] = $this->user_id;
            $logic = new UsersLogic();
            //添加评论
            $row = $logic->add_comment($add);            
            exit(json_encode($row));        
    }
    /*
 *添加评论
 */
    public function add_excomment()
    {
        $user_info = session('user');
        $comment_img = serialize(I('comment_img')); // 上传的图片文件
        $add['goods_id'] = I('goods_id');
        $add['email'] = $user_info['email'];
        //$add['nick'] = $user_info['nickname'];
        $add['username'] = $user_info['nickname'];
        $add['order_id'] = I('order_id');
        $add['service_rank'] = I('service_rank');
        $add['deliver_rank'] = I('deliver_rank');
        $add['goods_rank'] = I('goods_rank');
        //$add['content'] = htmlspecialchars(I('post.content'));
        $add['content'] = I('content');
        $add['img'] = $comment_img;
        $add['add_time'] = time();
        $add['ip_address'] = $_SERVER['REMOTE_ADDR'];
        $add['exchange_status'] = 1;
        $add['user_id'] = $this->user_id;
        $logic = new UsersLogic();
        //添加评论
        $row = $logic->add_excomment($add);
        exit(json_encode($row));
    }

    /*
     * 个人信息
     */
    public function info(){
        $userLogic = new UsersLogic();
        $user_info = $userLogic->get_info($this->user_id); // 获取用户信息
        $user_info = $user_info['result'];
        if(IS_POST){
            I('post.mobile') ? $post['mobile'] = I('post.mobile') : false; //昵称
            I('post.nickname') ? $post['nickname'] = I('post.nickname') : false; //昵称
            I('post.qq') ? $post['qq'] = I('post.qq') : false;  //QQ号码
            I('post.head_pic') ? $post['head_pic'] = I('post.head_pic') : false; //头像地址
            I('post.sex') ? $post['sex'] = I('post.sex') : false;  // 性别
            I('post.birthday') ? $post['birthday'] = I('post.birthday') : false;  // 生日
            I('post.enjoy') ? $post['enjoy'] = I('post.enjoy') : false;  //爱好
            I('post.status') ? $post['status'] = I('post.status') : false;  // 显示
            I('post.address') ? $post['address'] = I('post.address') : false;  //地址
            if(!$userLogic->update_info($this->user_id,$post))
                $this->error("保存失败");
            $this->success("操作成功");
            exit;
        }
        $this->assign('user',$user_info);
        $this->display();
    }

    /*
     * 邮箱验证
     */
    public function email_validate(){
        $userLogic = new UsersLogic();
        $user_info = $userLogic->get_info($this->user_id); // 获取用户信息
        $user_info = $user_info['result'];
        $step = I('get.step',1);
        if(IS_POST){
            $email = I('post.email');
            $old_email = I('post.old_email'); //旧邮箱
            $code = I('post.code');
            $info = session('email_code');
            if(!$info)
                $this->error('非法操作');
            //检查原邮箱是否正确
            if($user_info['email_validated'] == 1 && $old_email != $user_info['email'])
                $this->error('原邮箱匹配错误');
            //验证邮箱和验证码
            if($info['email'] == $email && $info['code'] == $code){
                session('email_code',null);
                if(!$userLogic->update_email_mobile($email,$this->user_id))
                    $this->error('邮箱已存在');
                $this->success('绑定成功',U('Home/User/index'));
                exit;
            }
            $this->error('邮箱验证码不匹配');
        }
        $this->assign('step',$step);
        $this->display();
    }


    /*
    * 手机验证
    */
    public function mobile_validate(){
        $userLogic = new UsersLogic();
        $user_info = $userLogic->get_info($this->user_id); //获取用户信息
        $user_info = $user_info['result'];
        $config = F('sms','',TEMP_PATH);
        $sms_time_out = $config['sms_time_out'];
        $step = I('get.step',1);
        //验证是否未绑定过
        if($user_info['mobile_validated'] == 0)
            $step = 2;
        //原手机验证是否通过
        if($user_info['mobile_validated'] == 1 && session('mobile_step1') == 1)
            $step = 2;
        if($user_info['mobile_validated'] == 1 && session('mobile_step1') != 1)
            $step = 1;
        if(IS_POST){
            $mobile = I('post.mobile');
            $old_mobile = I('post.old_mobile');
            $code = I('post.code');
            $info = session('mobile_code');
            if(!$info)
                $this->error('非法操作');
            //检查原手机是否正确
            if($user_info['mobile_validated'] == 1 && $old_mobile != $user_info['mobile'])
                $this->error('原手机号码错误');
            //验证手机和验证码
            if($info['mobile'] == $mobile && $info['code'] == $code){
                session('mobile_code',null);
                //验证有效期
                if($info['time'] < time())
                    $this->error('验证码已失效');
                if(!$userLogic->update_email_mobile($mobile,$this->user_id,2))
                    $this->error('手机已存在');
                $this->success('绑定成功',U('Home/User/index'));
                exit;
            }
            $this->error('手机验证码不匹配');
        }
        $this->assign('time',$sms_time_out);
        $this->assign('step',$step);
        $this->display();
    }

    //发送验证码
    public function send_validate_code(){
        $type = I('get.type');
        $to = I('get.send');
        $code =  rand(1000,9999);
        if($type == 'email'){
            //检查是否邮箱格式
            if(!check_email($to))
                exit('fail');
            //发送电子邮件验证码
            $send = send_email($to,'验证码','您好，你的验证码是：'.$code);
            //在有效期内不再重复发送
            if($send){
                $info['code'] = $code;
                $info['email'] = $to;
                $info['time'] = time()+300;
                session('email_code',$info);
                exit('ok');
            }
        }
        if($type == 'mobile'){
            $config = F('sms','',TEMP_PATH);
            $sms_time_out = $config['sms_time_out'];
            //获取上一次的发送时间
            $send = session('mobile_code');
            if($send && $send['time'] > time() && $send['mobile'] == $to){
                //在有效期范围内 相同号码不再发送
                exit('oks');
            }
            //检查是否手机号码格式
            if(!check_mobile($to))
                exit('fail');
            //发送手机验证码
            $send = sendSMS($to,'您好，你的验证码是：'.$code);
            if(true){
                $info['code'] = $code;
                $info['mobile'] = $to;
                $info['time'] = time()+$sms_time_out;
                session('mobile_code',$info);
                exit('ok');
            }
        }
    }

    /**
     * 发送手机注册验证码
     */
    public function send_sms_reg_code(){
        $mobile = I('mobile');
        $userLogic = new UsersLogic();
        if(!check_mobile($mobile))
            exit(json_encode(array('status'=>-1,'msg'=>'手机号码格式有误')));
        $code =  rand(100000,999999);
        $send = $userLogic->sms_log($mobile,$code,$this->session_id);
        if($send['status'] != 1)
            exit(json_encode(array('status'=>-1,'msg'=>$send['msg'])));
        exit(json_encode(array('status'=>1,'msg'=>'验证码已发送，请注意查收')));
    }
    /*
     *商品收藏
     */
    public function goods_collect(){
        $userLogic = new UsersLogic();
        $data = $userLogic->get_goods_colletc($this->user_id);
        $this->assign('page',$data['show']);// 赋值分页输出
        $this->assign('lists',$data['result']);
        $this->assign('active','goods_collect');
        $this->display();
    }

    /*
     * 删除一个收藏商品
     */
    public function del_goods_collect(){
        $id = I('get.id');
        if(!$id)
            $this->error("缺少ID参数");
        $row = M('goods_collect')->where(array('collect_id'=>$id,'user_id'=>$this->user_id))->delete();
        if(!$row)
            $this->error("删除失败");
        $this->success('删除成功');
    }

    /*
     * 密码修改
     */
    public function password(){
        //检查是否第三方登录用户
        $logic = new UsersLogic();
        $data = $logic->get_info($this->user_id);
        $user = $data['result'];
        if($user['mobile'] == ''&& $user['email'] == '')
            $this->error('请先绑定手机或邮箱',U('Home/User/info'));
        if(IS_POST){
            $userLogic = new UsersLogic();
            $data = $userLogic->password($this->user_id,I('post.old_password'),I('post.new_password'),I('post.confirm_password')); // 获取用户信息
            if($data['status'] == -1)
                $this->error($data['msg']);
            $this->success($data['msg']);
            exit;
        }
        $this->display();
    }

    public function forget_pwd(){
        $this->display();
    }
    
    public function set_pwd(){
    	if(IS_POST){
    		$password = I('post.password');
    		$password2 = I('post.repassword');
    		if($password2 != $password){
    			$this->error('两次密码不一致',U('Home/User/forget_pwd'));
    		}
            $user = session('user');
            $arr = M('users')->where(array('user_id'=>$user['user_id']))->find();
            if( encrypt($password) == $arr['password']){
                $re= 0;
                $this->ajaxReturn(json_encode($re));
            }else{
                $re = M('users')->where(array('user_id' => $user['user_id']))->save(array('password' => encrypt($password)));
                //var_dump($re);exit;
                $this->ajaxReturn(json_encode($re));
            }

    	}
    	$username = session('username');
    	$this->assign('username',$username);
    	$this->display();
    }
    
    public function finished(){
        $username = session('username');
        $this->assign('username',$username);
    	$this->display();
    }
    
    public function check_forget_code(){   	
    	$code = I('post.code');
    	$check = session('mobile_code');
    	if(empty($check))
    	{
    		$res = array('status'=>0,'msg'=>'请先获取验证码');
    	}elseif($check['time']<time())
    	{
    		$res = array('status'=>0,'msg'=>'验证码超时失效');
    	}elseif($code!=$check['code'])
    	{
    		$res = array('status'=>0,'msg'=>'验证码有误');
    	}else
    	{
    		$check['is_sure'] = 1;
    		session('mobile_code',$check);
    		$res = array('status'=>1);
    	}
    	exit(json_encode($res));
    }
    
    public function check_captcha(){
    	$verify = new Verify();
    	$type = I('post.type','user_login');
    	if (!$verify->check(I('post.verify_code'), $type)) {
    		exit(json_encode(0));
    	}else{
    		exit(json_encode(1));
    	}
    }

    /**
     * 核对账号信息
     */
    public function check_username(){
    	$username = I('post.username');
    	if(!empty($username)){
    		$count = M('users')->where("email='$username' or mobile='$username'")->count();
            //session('mobile',$username);
    		exit(json_encode(intval($count)));
    	}else{
    		exit(json_encode(0));
    	}  	
    }
    /**
     *
     */
    public function repwd(){
        $username = I('get.zhanghao');
        if(!empty($username)){
            $user = M('users')->where("email='$username' or mobile='$username'")->find();
            session('username',$username);
            session('user',$user);
        }
        $this->assign('username',$username);
        $this->assign('mobile',$user['mobile']);
        $this->display();
    }
    public function identity(){
    	if($this->user_id > 0){
    		header("Location: ".U('Home/User/Index'));
    	}
    	$username = I('post.username');
    	$captcha = I('post.captcha');
    	$userinfo = array();
    	if($username){
    		$userinfo = M('users')->where("email='$username' or mobile='$username'")->find();
    		$userinfo['username'] = $username;
    		$this->assign('sendToken',encrypt($username.C('AUTH_CODE')));
    	}else{
    		$this->error('参数有误！！！');
    	} 	
    	if(empty($userinfo)){
    		$this->error('非法请求！！！');
    	}
    	unset($userinfo['password']);
    	$this->assign('userinfo',$userinfo);
    	$this->display();
    }
    
    
    public function send_forget_code(){
    	$username = I('post.username');
        if($username == ''){
            exit(json_encode(array('status'=>-1,'msg'=>'非法请求')));
        }
    	$userinfo = M('users')->where("email='$username' or mobile='$username'")->find();
    	if($userinfo){
    			$sms_time_out = 120;
    			//获取上一次的发送时间
    			$send = session('mobile_code');
    			if($send && $send['time'] > time() && $send['mobile'] == $userinfo['mobile']){
    				//在有效期范围内 相同号码不再发送
    				exit(json_encode(array('status'=>-2,'msg'=>'手机验证码已发送，请检查')));
    			}
    			//发送手机验证码
                $code =  rand(100000,999999);
    			$send2 = sendSMS($userinfo['mobile'],$code,'139095');
                //var_dump($send2);exit;
    			if($send2){
    				$info['code'] = $code;
    				$info['mobile'] = $userinfo['mobile'];
    				$info['time'] = time()+$sms_time_out;
    				session('mobile_code',$info);
    				exit(json_encode(array('status'=>1,'msg'=>'验证码已发送，请注意查收')));
    			}
    	}else{
    		exit(json_encode(array('status'=>-1,'msg'=>'非法请求')));
    	}
    }

    /**
     * 验证码验证
     * $id 验证码标示
     */
    private function verifyHandle($id)
    {
        $verify = new Verify();
        if (!$verify->check(I('post.verify_code'), $id ? $id : 'user_login')) {
            $this->error("验证码错误");
        }
    }

    /**
     * 验证码获取
     */
    public function verify()
    {
        //验证码类型
        $type = I('get.type') ? I('get.type') : 'user_login';
        $config = array(
            'fontSize' => 40,
            'length' => 4,
            'useCurve' => true,
            'useNoise' => false,
        );
        $Verify = new Verify($config);
        $Verify->entry($type);
    }

    public function order_confirm(){
        $id = I('get.id',0);
       $logic = new UsersLogic();
        $data = $logic->confirm_order($this->user_id,$id);
        if(!$data['status'])
            $this->error($data['msg']);
        $this->success($data['msg']);
    }
    /**
     * 申请退货
     */
    public function return_goods()
    {
        $order_id = I('order_id',0);
        $order_sn = I('order_sn',0);
        $goods_id = I('goods_id',0);        
        
        $return_goods = M('return_goods')->where("order_id = $order_id and goods_id = $goods_id and status in(0,1)")->find();            
        if(!empty($return_goods))
        {
            $this->success('已经提交过退货申请!',U('Home/User/return_goods_info',array('id'=>$return_goods['id'])));
            exit;
        }       
        if(IS_POST)
        {
            $data['order_id'] = $order_id; 
            $data['order_sn'] = $order_sn; 
            $data['goods_id'] = $goods_id; 
            $data['addtime'] = time(); 
            $data['user_id'] = $this->user_id;            
            $data['type'] = I('type'); // 服务类型  退货 或者 换货
            $data['reason'] = I('reason'); // 问题描述
            $data['imgs'] = I('imgs'); // 用户拍照的相片
            M('return_goods')->add($data);            
            $this->success('申请成功,客服第一时间会帮你处理',U('Home/User/order_list'));
            exit;
        }
               
        $goods = M('goods')->where("goods_id = $goods_id")->find();        
        $this->assign('goods',$goods);
        $this->assign('order_id',$order_id);
        $this->assign('order_sn',$order_sn);
        $this->assign('goods_id',$goods_id);
        $this->display();
    }
    
    /**
     * 退换货列表
     */
    public function return_goods_list()
    {        
        $count = M('return_goods')->where("user_id = {$this->user_id}")->count();
        $page = new Page($count,10);
        $list = M('return_goods')->where("user_id = {$this->user_id}")->order("id desc")->limit("{$page->firstRow},{$page->listRows}")->select();
        $goods_id_arr = get_arr_column($list, 'goods_id');
        if(!empty($goods_id_arr))
            $goodsList = M('goods')->where("goods_id in (".  implode(',',$goods_id_arr).")")->getField('goods_id,goods_name');        
        $this->assign('goodsList', $goodsList);
        $this->assign('list', $list);
        $this->assign('page', $page->show());// 赋值分页输出
        $this->display();
    }
    
    /**
     *  退货详情
     */
    public function return_goods_info()
    {
        $id = I('id',0);
        $return_goods = M('return_goods')->where("id = $id")->find();
        if($return_goods['imgs'])
            $return_goods['imgs'] = explode(',', $return_goods['imgs']);        
        $goods = M('goods')->where("goods_id = {$return_goods['goods_id']} ")->find();                
        $this->assign('goods',$goods);
        $this->assign('return_goods',$return_goods);
        $this->display();
    }
    
    /**
     * 安全设置
     */
    public function safety_settings()
    {
        $userLogic = new UsersLogic();
        $user_info = $userLogic->get_info($this->user_id); // 获取用户信息
        $user_info = $user_info['result'];        
        $this->assign('user',$user_info);
        $this->display();      
    }
}