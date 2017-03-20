<?php
namespace Admin\Controller;

class CouponController extends BaseController {
    /**----------------------------------------------*/
     /*                优惠券控制器                  */
    /**----------------------------------------------*/

    /*
     * 优惠券类型列表
     */
    public function index(){
        //获取优惠券列表
        $lists = M('coupon')->order('add_time desc')->select();
        $this->assign('lists',$lists);
        $this->assign('coupons',C('COUPON_TYPE'));
        $this->display();
    }

    /*
     * 添加一个优惠券类型
     */
    public function add_coupon(){
        if(IS_POST){
            $add['name'] = I('post.name');
            $add['money'] = I('post.money');
            $add['condition'] = I('post.condition');
            $add['type'] = I('post.type');
            $add['add_time'] = time();
            $add['min_order'] = I('post.min_order');
            $add['send_start_time'] = strtotime(I('post.send_start_time'));
            $add['send_end_time'] = strtotime(I('post.send_end_time'));
            $add['use_end_time'] = strtotime(I('post.use_end_time'));
            if($add['send_start_time'] > $add['send_end_time']){
                $this->error('发放日期填写有误');
            }
            $row = M('coupon')->add($add);
            if(!$row)
                $this->error('添加失败');
            $this->success('添加成功',U('Admin/Coupon/index'));
            exit;
        }
        $def['send_start_time'] = strtotime("+1 day");
        $def['send_end_time'] = strtotime("+1 month");
        $def['use_end_time'] = strtotime("+2 month");
        $this->assign('coupon',$def);
        $this->display();
    }

    public function edit_coupon(){
        $cid = I('get.id');
        $coupon = M('coupon')->where(array('id'=>$cid))->find();
        if(!$coupon)
            $this->error('没有获取到优惠券信息');
        if(IS_POST){
            $add['name'] = I('post.name');
            $add['money'] = I('post.money');
            $add['condition'] = I('post.condition');
            $add['type'] = I('post.type');
            $add['add_time'] = time();
            $add['min_order'] = I('post.min_order');
            $add['send_start_time'] = strtotime(I('post.send_start_time'));
            $add['send_end_time'] = strtotime(I('post.send_end_time'));
            $add['use_end_time'] = strtotime(I('post.use_end_time'));

            if($add['send_start_time'] > $add['send_end_time']){
                $this->error('发放日期填写有误');
            }

            $row =  M('coupon')->where(array('id'=>$cid))->save($add);
            if(!$row)
                $this->error('编辑失败');
            	$this->success('编辑成功',U('Admin/Coupon/index'));
            exit;
        }
        $this->assign('coupon',$coupon);
        $this->display('add_coupon');
    }

    /*
    * 优惠券发放
    */
    public function send_coupon(){
        //获取优惠券ID
        $cid = I('get.id');
        $type = I('get.type');

        //查询是否存在优惠券
        $data = M('coupon')->where(array('id'=>$cid))->find();
        if(!$data)
            $this->error("优惠券类型不存在");
        if($type != 4)
            $this->error("该优惠券类型不支持发放");

        if(IS_POST){
            $num  = I('post.num');
            if(!$num > 0)
                $this->error("发放数量不能小于0");
            $add['cid'] = $cid;
            $add['type'] = $type;
            $add['send_time'] = time();
            for($i=0;$i<$num; $i++){
                do{
                    //获取随机6位字符串
                    $code = get_rand_str(6,0,1);
                    $check_exist = M('coupon_list')->where(array('code'=>$code))->find();
                }while($check_exist);
                $add['code'] = $code;
                M('coupon_list')->add($add);
            }
            $this->success("发放成功",U('Admin/Coupon/index'));
            exit;
        }
        $this->assign('coupon',$data);
        $this->display();
    }

    /*
     * 删除优惠券类型
     */
    public function del_coupon(){
        //获取优惠券ID
        $cid = I('get.id');
        //查询是否存在优惠券
        $row = M('coupon')->where(array('id'=>$cid))->delete();
        if($row){
            //删除此类型下的优惠券
            M('coupon_list')->where(array('cid'=>$cid))->delete();
            $this->success("删除成功");
        }else{
            $this->error("删除失败");
        }
    }


    /*
     * 优惠券详细查看
     */
    public function coupon_list(){
        //获取优惠券ID
        $cid = I('get.id');
        //查询是否存在优惠券
        $check_coupon = M('coupon')->field('id,type')->where(array('id'=>$cid))->find();
        if(!$check_coupon['id'] > 0)
            $this->error('不存在该类型优惠券');
        //查询该优惠券的列表
        $sql = "SELECT l.*,c.name,o.order_sn,u.nickname FROM __PREFIX__coupon_list  l ".
                "LEFT JOIN __PREFIX__coupon c ON c.id = l.cid ". //联合优惠券表查询名称
                "LEFT JOIN __PREFIX__order o ON o.order_id = l.order_id ".     //联合订单表查询订单编号
                "LEFT JOIN __PREFIX__users u ON u.user_id = l.uid WHERE l.cid = ".$cid;    //联合用户表去查询用户名
        $coupon_list = M()->query($sql);
        $this->assign('coupon_type',C('COUPON_TYPE'));
        $this->assign('type',$check_coupon['type']);

        $this->assign('lists',$coupon_list);
        $this->display();
    }
    
    /*
     * 删除一张优惠券
     */
    public function coupon_list_del(){
        //获取优惠券ID
        $cid = I('get.id');
        if(!$cid)
            $this->error("缺少参数值");
        //查询是否存在优惠券
         $row = M('coupon_list')->where(array('id'=>$cid))->delete();
        if(!$row)
            $this->error('删除失败');
        $this->success('删除成功');
    }
}