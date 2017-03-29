<?php

namespace Admin\Controller;
use Think\AjaxPage;
use Think\Page;

class LoveController extends BaseController {
    /**
     * 报名列表
     *
     */
    public function applyList(){
        $tal =  M('Apply');
        $check = trim($_POST['keywords']);
        if($check){
            if(substr($check,0,1)==1){
                $where ="sid = 1 and mobile=".$check;
            }else{
                $where= "sid = 1 and name like '%$check%'";
            }
            $count = $tal->where($where)->count();// 查询满足要求的总记录数
            $page = new Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数
            $list = $tal->where($where)->order('time desc')->limit($page->firstRow.','.$page->listRows)->select();
            $show = $page->show();//分页显示输出
        }else{
            $count = $tal->where('sid=1')->count();// 查询满足要求的总记录数
            $page = new Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数
            $list = $tal->where('sid=1')->order('time desc')->limit($page->firstRow.','.$page->listRows)->select();
            $show = $page->show();//分页显示输出
        }
        $this->assign('list',$list);
        $this->assign('page',$show);
        $this->display();
    }
    /**
     * 删除报名
     */
    public function delApply()
    {
        // 删除此报名用户
        $re = M("Apply")->where('id ='.$_POST['id'])->delete();
        $this->ajaxReturn(json_encode($re));
    }
    /**
     * 预约列表
     *
     */
    public function appointList()
    {

        $this->display();
    }
    /**
     *  列表
     */
    public function ajaxAppointList(){

        $where = ' 1 = 1 '; // 搜索条件

        // 关键词搜索
        $key_word = I('key_word') ? trim(I('key_word')) : '';
        if($key_word)
        {
            $where = "$where and (name like '%$key_word%' )" ;
        }


        $model = M('mudiorder');
        $count = $model->where($where)->count();
        $Page  = new AjaxPage($count,10);
        /**  搜索条件下 分页赋值
        foreach($condition as $key=>$val) {
        $Page->parameter[$key]   =   urlencode($val);
        }
         */
        $show = $Page->show();
        $order_str = "`{$_POST['orderby1']}` {$_POST['orderby2']}";
        $mudiList = $model->where($where)->order($order_str)->limit($Page->firstRow.','.$Page->listRows)->select();

        $this->assign('mudiList',$mudiList);
        $this->assign('page',$show);// 赋值分页输出
        $this->display();
    }
    /**
     * 删除预约
     */
    public function delAppoint()
    {

        // 删除此商品
        M("Mudiorder")->where('order_id ='.$_GET['id'])->delete();
        $return_arr = array('status' => 1,'msg' => '操作成功','data'  =>'',);   //$return_arr = array('status' => -1,'msg' => '删除失败','data'  =>'',);
        $this->ajaxReturn(json_encode($return_arr));
    }
    private function initEditor()
    {
        $this->assign("URL_upload", U('Admin/Ueditor/imageUp',array('savepath'=>'goods'))); // 图片上传目录
        $this->assign("URL_imageUp", U('Admin/Ueditor/imageUp',array('savepath'=>'article'))); //  不知道啥图片
        $this->assign("URL_fileUp", U('Admin/Ueditor/fileUp',array('savepath'=>'article'))); // 文件上传s
        $this->assign("URL_scrawlUp", U('Admin/Ueditor/scrawlUp',array('savepath'=>'article')));  //  图片流
        $this->assign("URL_getRemoteImage", U('Admin/Ueditor/getRemoteImage',array('savepath'=>'article'))); // 远程图片管理
        $this->assign("URL_imageManager", U('Admin/Ueditor/imageManager',array('savepath'=>'article'))); // 图片管理
        $this->assign("URL_getMovie", U('Admin/Ueditor/getMovie',array('savepath'=>'article'))); // 视频上传
        $this->assign("URL_Home", "");
    }
}