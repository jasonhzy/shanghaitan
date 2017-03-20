<?php
namespace Admin\Controller;
use Admin\Logic\ArticleCatLogic;

class ActivityController extends BaseController {
    /*
     * 活动分类
     * */
    public function catList(){
        $ArticleCat = new ArticleCatLogic(); 
        $cat_list = $ArticleCat->activity_cat_list(0, 0, false);
      /*  $count = M("article_cat")->where(array('parent_id'=>0))->count();// 查询满足要求的总记录数
        $pager = new \Think\Page($count,6);// 实例化分页类 传入总记录数和每页显示的记录数
        $page = $pager->show();//分页显示输出
        $this->assign('page',$page);*/
        $this->assign('cat_list',$cat_list);
        $this->display('categoryList');
    }
    
    public function category(){
        $ArticleCat = new ArticleCatLogic();  
 		$act = I('GET.act','add');
        $this->assign('act',$act);
        $cat_id = I('GET.cat_id');
        //dump($cat_id);die;
        $cat_info = array();
        if($cat_id){
            $cat_info = D('activity_cat')->where('cat_id='.$cat_id)->find();
            $this->assign('cat_info',$cat_info);
        }
        $cats = $ArticleCat->activity_cat_list(0,$cat_info['parent_id'],true);
        $this->assign('cat_select',$cats);
        $this->display();
    }
    /*
     *活动列表
     * */
    public function activityList(){
        $activity =  M('activity');
        $res = $list = array();
        $p = empty($_REQUEST['p']) ? 1 : $_REQUEST['p'];
        $size = empty($_REQUEST['size']) ? 10 : $_REQUEST['size'];
        
        $where = " 1 = 1 ";
        $keywords = trim(I('keywords'));
        $keywords && $where.=" and title like '%$keywords%' ";
        $res = $activity->where($where)->order('activity_id desc')->page("$p,$size")->select();
        $count = $activity->where($where)->count();// 查询满足要求的总记录数
        $pager = new \Think\Page($count,$size);// 实例化分页类 传入总记录数和每页显示的记录数
        $page = $pager->show();//分页显示输出
        $ArticleCat = new ArticleCatLogic();
        $cats = $ArticleCat->activity_cat_list(0,0,false);
        if($res){
        	foreach ($res as $val){
        		$val['category'] = $cats[$val['cat_id']]['cat_name'];
        		$val['add_time'] = date('Y-m-d',$val['add_time']);
        		$list[] = $val;
        	}
        }
        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$page);// 赋值分页输出
        $this->display('activityList');
    }
    
    public function activity(){
        $ArticleCat = new ArticleCatLogic();
 		$act = I('GET.act','add');
        $info = array();
        if(I('GET.activity_id')){
           $activity_id = I('GET.activity_id');
           $info = D('activity')->where('activity_id='.$activity_id)->find();
        }
        $cats = $ArticleCat->activity_cat_list(0,$info['cat_id']);
        $time = date("Y-m-d",time());
        $this->assign('cat_select',$cats);
        $this->assign('act',$act);
        $this->assign('time',$time);
        $this->assign('info',$info);
        $this->initEditor();
        $this->display();
    }
    
    
    /**
     * 初始化编辑器链接
     * @param $post_id post_id
     */
    private function initEditor()
    {
        $this->assign("URL_upload", U('Admin/Ueditor/imageUp',array('savepath'=>'article')));
        $this->assign("URL_fileUp", U('Admin/Ueditor/fileUp',array('savepath'=>'article')));
        $this->assign("URL_scrawlUp", U('Admin/Ueditor/scrawlUp',array('savepath'=>'article')));
        $this->assign("URL_getRemoteImage", U('Admin/Ueditor/getRemoteImage',array('savepath'=>'article')));
        $this->assign("URL_imageManager", U('Admin/Ueditor/imageManager',array('savepath'=>'article')));
        $this->assign("URL_imageUp", U('Admin/Ueditor/imageUp',array('savepath'=>'article')));
        $this->assign("URL_getMovie", U('Admin/Ueditor/getMovie',array('savepath'=>'article')));
        $this->assign("URL_Home", "");
    }
    
    
    public function categoryHandle(){
    	$data = I('post.');   
        if($data['act'] == 'add'){           
            $d = D('activity_cat')->add($data);
        }
        
        if($data['act'] == 'edit')
        {
        	if ($data['cat_id'] == $data['parent_id']) 
			{
        		$this->error("所选分类的上级分类不能是当前分类",U('Admin/Activity/category',array('cat_id'=>$data['cat_id'])));
        	}
        	$ArticleCat = new ArticleCatLogic();
        	$children = array_keys($ArticleCat->activity_cat_list($data['cat_id'], 0, false)); // 获得当前分类的所有下级分类
        	if (in_array($data['parent_id'], $children))
        	{
        		$this->error("所选分类的上级分类不能是当前分类的子分类",U('Admin/Activity/category',array('cat_id'=>$data['cat_id'])));
        	}
        	$d = D('activity_cat')->where("cat_id=$data[cat_id]")->save($data);
        }
        
        if($data['act'] == 'del'){      	
        	$res = D('activity_cat')->where('parent_id ='.$data['cat_id'])->select();
        	if ($res)
        	{
        		exit(json_encode('还有子分类，不能删除'));
        	}
        	$res = D('activity')->where('cat_id ='.$data['cat_id'])->select();
        	if ($res)
        	{
        		exit(json_encode('非空的分类不允许删除'));
        	}      	
        	$r = D('activity_cat')->where('cat_id='.$data['cat_id'])->delete();
        	if($r) exit(json_encode(1));
        }
        if($d){
        	$this->success("操作成功",U('Admin/Activity/catList'));
        }else{
        	$this->error("操作失败",U('Admin/Activity/catList'));
        }
    }
    
    public function activityHandle(){
        $data = I('post.');
        $data['add_time'] = strtotime($data['add_time']);
        //$data['content'] = htmlspecialchars(stripslashes($_POST['content']));
        if($data['act'] == 'add'){
        	//$data['add_time'] = time();
            $r = D('Activity')->add($data);
        }
        
        if($data['act'] == 'edit'){
            $r = D('Activity')->where('activity_id='.$data['activity_id'])->save($data);
        }
        
        if($data['act'] == 'del'){
        	$r = D('Activity')->where('activity_id='.$data['activity_id'])->delete();
        	if($r) exit(json_encode(1));       	
        }
        $referurl = U('Admin/Activity/activityList');
        if($r){
            $this->success("操作成功",$referurl);
        }else{
            $this->error("操作失败",$referurl);
        }
    }
    
    
    public function link(){
    	$act = I('GET.act','add');
    	$this->assign('act',$act);
    	$link_id = I('GET.link_id');
    	$link_info = array();
    	if($link_id){
    		$link_info = D('friend_link')->where('link_id='.$link_id)->find();
    		$this->assign('info',$link_info);
    	}
    	$this->display();
    }
    
    public function linkList(){
    	$Ad =  M('friend_link');
    	$res = $Ad->where('1=1')->order('orderby')->page($_GET['p'].',10')->select();
    	if($res){
    		foreach ($res as $val){
    			$val['target'] = $val['target']>0 ? '开启' : '关闭';
    			$list[] = $val;
    		}
    	}
    	$this->assign('list',$list);// 赋值数据集
    	$count = $Ad->where('1=1')->count();// 查询满足要求的总记录数
    	$Page = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数
    	$show = $Page->show();// 分页显示输出
    	$this->assign('page',$show);// 赋值分页输出
    	$this->display();
    }
    
    public function linkHandle(){
        $data = I('post.');
        if($_POST['orderby']==''){
            $data['orderby']=50;
        }
    	if($data['act'] == 'add'){
    		$r = D('friend_link')->add($data);
    	}
    	if($data['act'] == 'edit'){
    		$r = D('friend_link')->where('link_id='.$data['link_id'])->save($data);
    	}
    	
    	if($data['act'] == 'del'){
    		$r = D('friend_link')->where('link_id='.$data['link_id'])->delete();
    		if($r) exit(json_encode(1));
    	}
    	
    	if($r){
    		$this->success("操作成功",U('Admin/Article/linkList'));
    	}else{
    		$this->error("操作失败",U('Admin/Article/linkList'));
    	}
    }
}