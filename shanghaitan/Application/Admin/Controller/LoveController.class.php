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
            $pager = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数
            $page = $pager->show();//分页显示输出
            $list = $tal->where($where)->order('time desc')->limit($page->firstRow.','.$page->listRows)->select();
        }else{
            $count = $tal->count();// 查询满足要求的总记录数
            $pager = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数
            $page = $pager->show();//分页显示输出
            $list = $tal->where('sid=1')->order('time desc')->limit($page->firstRow.','.$page->listRows)->select();
        }
        $this->assign('list',$list);
        $this->assign('page',$page);
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
     * 添加修改墓地
     */
    public function addEditMudi(){

        $mudi = D('Mudi'); //
        $type = $_POST['mudi_id'] > 0 ? 2 : 1; // 标识自动验证时的 场景 1 表示插入 2 表示更新
        //ajax提交验证
        if(($_GET['is_ajax'] == 1) && IS_POST)
        {
            if($mudi->create())// 根据表单提交的POST数据创建数据对象
            {

                C('TOKEN_ON', false);
                //  form表单提交
                // C('TOKEN_ON',true);
                $mudi->time = time(); // 上架时间
                if ($type == 2) {
                    $id = $_POST['mudi_id'];
                    $mudi->save(); // 写入数据到数据库
                } else {
                    $id = $insert_id = $mudi->add(); // 写入数据到数据库
                }


                $return_arr = array(
                    'status' => 1,
                    'msg' => '操作成功',
                    'data' => array('url' => U('Admin/Mudi/mudiList')),
                );
                $this->ajaxReturn(json_encode($return_arr));
            }
        }

        $mudiInfo = D('Mudi')->where('mudi_id='.I('GET.id',0))->find();
        $this->assign('mudiInfo',$mudiInfo);  // 商品详情
        $this->initEditor(); // 编辑器
        $this->display();
    }


    /**
     * 删除墓地
     */
    public function delMudi()
    {

        // 判断此墓地是否有预约
       /* $mudi_count = M('OrderGoods')->where("goods_id = {$_GET['id']}")->count('1');
        if($mudi_count)
        {
            $return_arr = array('status' => -1,'msg' => '此商品有订单,不得删除!','data'  =>'',);   //$return_arr = array('status' => -1,'msg' => '删除失败','data'  =>'',);
            $this->ajaxReturn(json_encode($return_arr));
        }*/

        // 删除此商品
        M("Mudi")->where('mudi_id ='.$_GET['id'])->delete();
        $return_arr = array('status' => 1,'msg' => '操作成功','data'  =>'',);   //$return_arr = array('status' => -1,'msg' => '删除失败','data'  =>'',);
        $this->ajaxReturn(json_encode($return_arr));
    }
    /**
     *  墓地动态列表
     */
    public function mudiNews(){

        $this->display();
    }

    /**
     *  墓地列表
     */
    public function ajaxMudiNews(){

        $where = ' 1 = 1 '; // 搜索条件

        // 关键词搜索
        $key_word = I('key_word') ? trim(I('key_word')) : '';
        if($key_word)
        {
            $where = "$where and (title like '%$key_word%' )" ;
        }


        $model = M('Mudi_news');
        $count = $model->where($where)->count();
        $Page  = new AjaxPage($count,10);
        /**  搜索条件下 分页赋值
        foreach($condition as $key=>$val) {
        $Page->parameter[$key]   =   urlencode($val);
        }
         */
        $show = $Page->show();
        $order_str = "`{$_POST['orderby1']}` {$_POST['orderby2']}";
        $newsList = $model->where($where)->order($order_str)->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('newsList',$newsList);
        $this->assign('page',$show);// 赋值分页输出
        $this->display();
    }


    /**
     * 添加修改墓地
     */
    public function addEditNews(){

        $news = D('Mudi_news'); //
        $type = $_POST['news_id'] > 0 ? 2 : 1; // 标识自动验证时的 场景 1 表示插入 2 表示更新
        //ajax提交验证
        if(($_GET['is_ajax'] == 1) && IS_POST)
        {
            if($news->create())// 根据表单提交的POST数据创建数据对象
            {

                C('TOKEN_ON', false);
                //  form表单提交
                // C('TOKEN_ON',true);
                $news->addtime = time(); // 添加时间
                if ($type == 2) {
                    $id = $_POST['news_id'];
                    $news->save(); // 写入数据到数据库
                } else {
                    $id = $insert_id = $news->add(); // 写入数据到数据库
                }


                $return_arr = array(
                    'status' => 1,
                    'msg' => '操作成功',
                    'data' => array('url' => U('Admin/Mudi/mudiNews')),
                );
                $this->ajaxReturn(json_encode($return_arr));
            }
        }

        $mudiInfo = D('Mudi_news')->where('news_id='.I('GET.id',0))->find();
        $this->assign('mudiInfo',$mudiInfo);  // 商品详情
        $this->initEditor(); // 编辑器
        $this->display();
    }


    /**
     * 删除墓地
     */
    public function delNews()
    {

        // 删除此商品
        M("Mudi_news")->where('news_id ='.$_GET['id'])->delete();
        $return_arr = array('status' => 1,'msg' => '操作成功','data'  =>'',);   //$return_arr = array('status' => -1,'msg' => '删除失败','data'  =>'',);
        $this->ajaxReturn(json_encode($return_arr));
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