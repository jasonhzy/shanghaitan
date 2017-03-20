<?php
namespace Admin\Controller;
use Think\AjaxPage;
use Think\Page;
class ModuleController extends BaseController{
       /**
        *图片列表
        */
    public function moduleList(){
           $model = M("lunbo");
           $count = $model->count();
           $Page  = new Page($count,10);
           $show = $Page->show();
           $navigationList = $model->order("id desc")->limit($Page->firstRow.','.$Page->listRows)->select();
           $this->assign('navigationList',$navigationList);
           $this->assign('page',$show);
           $this->display();
     }
    
    /**
     * 添加修改编辑
     */
    public  function addEditNav(){                        
            $model = D("lunbo");
            $quyu = M('area')->select();
            if(IS_POST)
            {
                    $model->create();
                    // $model->url = strstr($model->url, 'http') ? $model->url : "http://".$model->url; // 前面自动加上 http://
                    if($_GET['id'])
                        $model->save();
                    else
                        $model->add();
                    
                    $this->success("操作成功!!!",U('Admin/Module/moduleList'));
                    exit;
            }                    
           // 点击过来编辑时                 
           $id = $_GET['id'] ? $_GET['id'] : 0;       
           $navigation = $model->find($id);
           $city = M('city')->where(array('id'=>$navigation['cityid']))->find();
            $this->assign('city',$city['name']);
           //$system_nav = array('请选择版块','首页');
           $this->assign('quyu',$quyu);
           $this->assign('navigation',$navigation);
           $this->display('_navigation');
    }   
    
    /**
     * 删除图片
     */
    public function delNav()
    {     
        // 删除导航
        M('lunbo')->where("id = {$_GET['id']}")->delete();
        $this->success("操作成功!!!",U('Admin/Module/moduleList'));
    }
	
}