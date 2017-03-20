<?php
namespace Admin\Controller;
use Think\AjaxPage;
class MusicController extends BaseController{
	

       /**
        * 自定义导航
        */
    public function musicList(){
           $model = M("music");
			$count = $model->count();
			$Page  = new AjaxPage($count,10);
			$show = $Page->show();
           $navigationList = $model->order("id desc")->limit($Page->firstRow.','.$Page->listRows)->select();
			$this->assign('pages',$show);
           $this->assign('navigationList',$navigationList);
           $this->display();
     }
    
    /**
     * 添加修改编辑 前台导航
     */
    public  function addEditNav(){                        
            $model = D("music");
            if(IS_POST)
            {
                    $model->create();
                    // $model->url = strstr($model->url, 'http') ? $model->url : "http://".$model->url; // 前面自动加上 http://
                    if($_GET['id'])
                        $model->save();
                    else
                        $model->add();
                    
                    $this->success("操作成功!!!",U('Admin/Music/musicList'));
                    exit;
            }                    
           // 点击过来编辑时                 
           $id = $_GET['id'] ? $_GET['id'] : 0;       
           $navigation = $model->find($id);;
           $this->assign('navigation',$navigation);
           $this->display('_navigation');
    }   
    
    /**
     * 删除前台 自定义 导航
     */
    public function delNav()
    {     
        // 删除导航
        M('music')->where("id = {$_GET['id']}")->delete();
        $this->success("操作成功!!!",U('Admin/Music/musicList'));
    }
	
}