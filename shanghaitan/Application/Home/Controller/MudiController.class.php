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
 * $Author: IT宇宙人 2015-08-10 $
 */
namespace Home\Controller;
use Think\AjaxPage;
use Think\Page;
use Think\Verify;
class MudiController extends BaseController {

   /**
    * 墓地详情页
    */ 
    public function mudiInfo(){
        $mudi_id = I("get.id");
        $mudi = M('Mudi')->where("mudi_id = $mudi_id")->find();
        if(empty($mudi)){
        	$this->tp404('此墓地不存在');
        }

        $this->assign('mudi_id',$mudi['mudi_id']);
        $this->assign('mudi',$mudi);
        $this->display();
    }
    /**
     * 预约
     */
    public function ajaxMudi(){
        $mudi_id = $_POST['mudi_id'];
        $arr = M('mudi')->where(array('mudi_id'=>$mudi_id))->find();
        $_POST = array(
            'user_id' =>$this->user_id,
            'mudi_id'=>$mudi_id,
            'mudi_name'=>$arr['name'],
            'mudi_price'=>$arr['price'],
            'order_time'=>time(),
            'order_sn'=>'MD'.date('YmdHis').rand(1000,9999)
        );
        $re = M('mudiorder')->add($_POST);
        $this->ajaxReturn(json_encode($re));
    }
    /**
     * 动态详情页
     */
    public function mudiDetail(){
        $news_id = I("get.id");
        $news = M('Mudi_news')->where("news_id = $news_id")->find();
        $this->assign('news',$news);
        $this->assign('news_id',$news['news_id']);
        $this->display();
    }

    /**
     * 墓地列表页
     */
    public function mudiList(){

        $mudiList = M('Mudi')->order("mudi_id desc")->limit(10)->select();
        $news = M('Mudi_news')->order("addtime desc")->limit(6)->select();
        //var_dump($mudilist);exit;
        $list = M('lunbo')->where(array('name'=>6))->limit(3)->select();
        $this->assign('list',$list);
        $this->assign('mudiList',$mudiList);
        $this->assign('news',$news);
        $this->display();         
    }    
   

}