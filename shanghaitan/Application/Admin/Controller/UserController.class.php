<?php

namespace Admin\Controller;


use Think\AjaxPage;
use Think\Page;

class UserController extends BaseController {

    public function index(){
        $this->display();
    }

    /**
     * 会员列表
     */
    public function ajaxindex(){
        // 搜索条件
        $condition = array();
        I('mobile') ? $condition['mobile'] = I('mobile') : false;
        I('email') ? $condition['email'] = I('email') : false;
        $sort_order = I('order_by').' '.I('sort');
        $condition['is_check'] = 1;
        $model = M('users');
        $count = $model->where($condition)->count();
        $Page  = new AjaxPage($count,10);
        //  搜索条件下 分页赋值
        foreach($condition as $key=>$val) {
            $Page->parameter[$key]   =   urlencode($val);
        }
        $show = $Page->show();
        $orderList = $model->where($condition)->order($sort_order)->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('userList',$orderList);
        $this->assign('page',$show);// 赋值分页输出
        $this->display();
    }

    /**
     * 会员详细信息查看
     */
    public function detail(){
        $uid = I('get.id');
        $user = D('users')->where(array('user_id'=>$uid))->find();
        if(!$user)
            exit($this->error('会员不存在'));
        if(IS_POST){
            //  会员信息编辑
            $password = I('post.password');
            $password2 = I('post.password2');
            if($password != '' && $password != $password2){
                exit($this->error('两次输入密码不同'));
            }
            if($password == '' && $password2 == ''){
                unset($_POST['password']);
            }else{
                $_POST['password'] = encrypt($_POST['password']);
            }

            $row = M('users')->where(array('user_id'=>$uid))->save($_POST);
            if($row)
                exit($this->success('修改成功'));
            exit($this->error('未作内容修改或修改失败'));
        }
        $this->assign('user',$user);
        $this->display();
    }

    /**
     * 用户收货地址查看
     */
    public function address(){
        $uid = I('get.id');
        $lists = D('user_address')->where(array('user_id'=>$uid))->select();
        $regionList = M('Region')->getField('id,name');
        $this->assign('regionList',$regionList);
        $this->assign('lists',$lists);
        $this->display();
    }

    /**
     * 删除会员
     */
    public function delete(){
        $uid = I('get.id');
        $row = M('users')->where(array('user_id'=>$uid))->delete();
        if($row){
            $this->success('成功删除会员');
        }else{
            $this->error('操作失败');
        }
    }
    /**
     * 添加会员
     */
    public function addUser(){
        $now = date("Y-m-d",time());
        $this->assign('now',$now);
        $this->display();
    }
    /*
     * 执行添加
     */
    public function saveUser(){
        $birthday = $_POST['birthday'];
        $age = '';
        if(!empty($birthday)){
            list($y1,$m1,$d1) = explode("-",$birthday);
            list($y2,$m2,$d2) = explode("-",date("Y-m-d"), time());
            $age = $y2 - $y1;
            if((int)($m2.$d2) < (int)($m1.$d1)){
                $age -= 1;
            }
        }
        //dump($age);die;
        $_POST['age'] = $age;
        $_POST['reg_time'] = time();
        //dump($_POST);exit;
        $re = M("users")->add($_POST);
        if ($re) {
            $this->success('成功添加会员');
        } else {
            $this->error('操作失败');
        }
    }
    /*
     * 会员审核
     * */
    public function userList(){
        $count = M('users')->where('is_check=0')->count();// 查询满足要求的总记录数
        $Page = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数
        $userInfo = M('users')->where(array('is_check'=>0))->order('reg_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $show = $Page->show();//分页显示输出
        $this->assign('userInfo',$userInfo);
        $this->assign('page',$show);
        $this->display();
    }
    /**
     * 资料审核通过
     */
    public function ajaxCheck(){
        $user_id = I('get.user_id');
        if($user_id == ''){
            exit($this->error('会员不存在'));
        }
        $data['is_check'] = 1;
        $r = D('users')->where('user_id='.$user_id)->save($data);
        if($r) exit(json_encode(1));
    }
    /**
     * 账户资金记录
     */
    public function account_log(){
        $user_id = I('get.id');
        //获取类型
        $type = I('get.type');
        //获取记录总数
        $count = M('account_log')->where(array('user_id'=>$user_id))->count();
        $page = new Page($count);
        $lists  = M('account_log')->where(array('user_id'=>$user_id))->order('change_time desc')->limit($page->firstRow.','.$page->listRows)->select();

        $this->assign('user_id',$user_id);
        $this->assign('page',$page->show());
        $this->assign('lists',$lists);
        $this->display();
    }

    /**
     * 账户资金调节
     */
    public function account_edit(){
        $user_id = I('get.id');
        if(!$user_id > 0)
            $this->error("参数有误");
        if(IS_POST){
            //获取操作类型
            $m_op_type = I('post.money_act_type');
            $user_money = I('post.user_money');
            $user_money =  $m_op_type ? $user_money : 0-$user_money;

            $p_op_type = I('post.point_act_type');
            $pay_points = I('post.pay_points');
            $pay_points =  $p_op_type ? $pay_points : 0-$pay_points;

            $f_op_type = I('post.frozen_act_type');
            $frozen_money = I('post.frozen_money');
            $frozen_money =  $f_op_type ? $frozen_money : 0-$frozen_money;

            $desc = I('post.desc');
            if(!$desc)
                $this->error("请填写操作说明");
            if(accountLog($user_id,$user_money,$pay_points,$desc)){
                $this->success("操作成功",U("Admin/User/account_log",array('id'=>$user_id)));
            }else{
                $this->error("操作失败");
            }
            exit;
        }
        $this->assign('user_id',$user_id);
        $this->display();
    }
    
    public function level(){
    	$act = I('GET.act','add');
    	$this->assign('act',$act);
    	$level_id = I('GET.level_id');
    	$level_info = array();
    	if($level_id){
    		$level_info = D('user_level')->where('level_id='.$level_id)->find();
    		$this->assign('info',$level_info);
    	}
    	$this->display();
    }
    
    public function levelList(){
    	$Ad =  M('user_level');
    	$res = $Ad->where('1=1')->order('level_id')->page($_GET['p'].',10')->select();
    	if($res){
    		foreach ($res as $val){
    			$list[] = $val;
    		}
    	}
    	$this->assign('list',$list);
    	$count = $Ad->where('1=1')->count();
    	$Page = new \Think\Page($count,10);
    	$show = $Page->show();
    	$this->assign('page',$show);
    	$this->display();
    }
    
    public function levelHandle(){
    	$data = I('post.');
    	if($data['act'] == 'add'){
    		$r = D('user_level')->add($data);
    	}
    	if($data['act'] == 'edit'){
    		$r = D('user_level')->where('level_id='.$data['level_id'])->save($data);
    	}
    	 
    	if($data['act'] == 'del'){
    		$r = D('user_level')->where('level_id='.$data['level_id'])->delete();
    		if($r) exit(json_encode(1));
    	}
    	 
    	if($r){
    		$this->success("操作成功",U('Admin/User/levelList'));
    	}else{
    		$this->error("操作失败",U('Admin/User/levelList'));
    	}
    }

    /**
     * 搜索用户名
     */
    public function search_user()
    {
        $search_key = trim(I('search_key'));        
        if(strstr($search_key,'@'))    
        {
            $list = M('users')->where(" email like '%$search_key%' ")->select();        
            foreach($list as $key => $val)
            {
                echo "<option value='{$val['user_id']}'>{$val['email']}</option>";
            }                        
        }
        else
        {
            $list = M('users')->where(" mobile like '%$search_key%' ")->select();        
            foreach($list as $key => $val)
            {
                echo "<option value='{$val['user_id']}'>{$val['mobile']}</option>";
            }            
        } 
        exit;
    }
}