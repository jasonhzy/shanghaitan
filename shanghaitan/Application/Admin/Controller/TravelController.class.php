<?php
namespace Admin\Controller;
use Admin\Logic\TravelLogic;
use Think\AjaxPage;
use Think\Page;

class TravelController extends BaseController {
    
    /**
     *  旅游分类列表
     *
     */
    public function travelCategory(){
                
        $travelLogic = new TravelLogic();
        $cat_list = $travelLogic->travel_cat_list();
        //print_r($cat_list);
        $this->assign('cat_list',$cat_list);        
        $this->display();        
    }
    
    /**
     * 添加修改编辑分类
     * 手动拷贝分类正则 ([\u4e00-\u9fa5/\w]+)  ('393','$1'), 
     * select * from tp_goods_category where id = 393
        select * from tp_goods_category where parent_id = 393
        update tp_goods_category  set parent_id_path = concat_ws('_','0_76_393',id),`level` = 3 where parent_id = 393
        insert into `tp_goods_category` (`parent_id`,`name`) values 
        ('393','时尚饰品'),
     */
    public function addEditCategory(){
        
            $travelLogic = new TravelLogic();
            if(IS_GET)
            {

                $travel_category_info = D('Travel_category')->where('id='.I('GET.id',0))->find();
                //echo "11";exit;
                $level_cat = $travelLogic->find_parent_cat($travel_category_info['id']); // 获取分类默认选中的下拉框
                
                $cat_list = M('travel_category')->where("parent_id = 0")->select(); // 已经改成联动菜单
                $this->assign('level_cat',$level_cat);                
                $this->assign('cat_list',$cat_list);
                $this->assign('travel_category_info',$travel_category_info);
                $this->display('_category');     
                exit;
            }

            $travelCategory = D('Travel_category'); //

            $type = $_POST['id'] > 0 ? 2 : 1; // 标识自动验证时的 场景 1 表示插入 2 表示更新                        
            //ajax提交验证
            if($_GET['is_ajax'] == 1)
            {
                C('TOKEN_ON',false);
                

                
                if(!$travelCategory->create(NULL,$type))// 根据表单提交的POST数据创建数据对象
                {
                    //  编辑
                    $return_arr = array(
                        'status' => -1,
                        'msg'   => '操作失败!',
                        'data'  => $travelCategory->getError(),
                    );
                    $this->ajaxReturn(json_encode($return_arr));
                }else {
                    //  form表单提交
                    C('TOKEN_ON',true);

                    
                    $travelCategory->parent_id = $_POST['parent_id_1'];
                    $_POST['parent_id_2'] && ($travelCategory->parent_id = $_POST['parent_id_2']);
                    
                    if($travelCategory->id > 0 && $travelCategory->parent_id == $travelCategory->id)
                    {
                        //  编辑
                        $return_arr = array(
                            'status' => -1,
                            'msg'   => '上级分类不能为自己',
                            'data'  => '',
                        );
                        $this->ajaxReturn(json_encode($return_arr));                        
                    }
                    if ($type == 2)
                    {
                        $travelCategory->save(); // 写入数据到数据库
                        $travelLogic->refresh_cat($_POST['id']);
                    }
                    else
                    {
                        $insert_id = $travelCategory->add(); // 写入数据到数据库
                        $travelLogic->refresh_cat($insert_id);
                    }
                    $return_arr = array(
                        'status' => 1,
                        'msg'   => '操作成功',
                        'data'  => array('url'=>U('Admin/Travel/travelCategory')),
                    );
                    $this->ajaxReturn(json_encode($return_arr));

                }  
            }

    }
    

    
    /**
     * 删除分类
     */
    public function delTravelCategory(){
        // 判断子分类
        $travelCategory = M("travelCategory");
        $count = $travelCategory->where("parent_id = {$_GET['id']}")->count("id");
        $count > 0 && $this->error('该分类下还有分类不得删除!',U('Admin/Travel/travelCategoryList'));
        // 判断是否存在商品
        $travel_count = M('Life')->where("cat_id = {$_GET['id']}")->count('1');
        $travel_count > 0 && $this->error('该分类下有商品不得删除!',U('Admin/Travel/travelCategoryList'));
        // 删除分类
        $travelCategory->where("id = {$_GET['id']}")->delete();
        $this->success("操作成功!!!",U('Admin/Travel/travelCategory'));
    }


    /**
     *  旅游景点列表
     */
    public function travelList(){
        $travelLogic = new TravelLogic();
        $categoryList = $travelLogic->getSortCategory();
        $this->assign('categoryList',$categoryList);
        $this->display();
    }
    /**
     *  列表
     */
    public function ajaxTravelList(){
        
        $where = ' 1 = 1 '; // 搜索条件                

        $cat_id = I('cat_id');
        // 关键词搜索               
        $key_word = I('key_word') ? trim(I('key_word')) : '';
        if($key_word)
        {
            $where = "$where and (travel_name like '%$key_word%')" ;
        }
        
        if($cat_id > 0)
        {
            $grandson_ids = getCatGrandson($cat_id); 
            $where .= " and cat_id in(".  implode(',', $grandson_ids).") "; // 初始化搜索条件
        }
        
        
        $model = M('Travel');
        $count = $model->where($where)->count();
        $Page  = new AjaxPage($count,10);
        /**  搜索条件下 分页赋值
        foreach($condition as $key=>$val) {
            $Page->parameter[$key]   =   urlencode($val);
        }
        */
        $show = $Page->show();
        $order_str = "`{$_POST['orderby1']}` {$_POST['orderby2']}";
        $travelList = $model->where($where)->order($order_str)->limit($Page->firstRow.','.$Page->listRows)->select();

        $catList = D('Travel_category')->select();
        $catList = convert_arr_key($catList, 'id');
        $this->assign('catList',$catList);
        $this->assign('travelList',$travelList);
        $this->assign('page',$show);// 赋值分页输出
        $this->display();         
    }    
    
    
    /**
     * 添加修改服务
     */
    public function addEditTravel(){
        
            $travelLogic = new TravelLogic();
            $travel = D('Travel'); //
            $type = $_POST['travel_id'] > 0 ? 2 : 1; // 标识自动验证时的 场景 1 表示插入 2 表示更新
            $_POST['number'] = "LY-".date('YmdHis').rand(1000,9999);
            //ajax提交验证
            if(($_GET['is_ajax'] == 1) && IS_POST)
            {                
                C('TOKEN_ON',false);
                if(!$travel->create(NULL,$type))// 根据表单提交的POST数据创建数据对象
                {
                    //  编辑
                    $return_arr = array(
                        'status' => -1,
                        'msg'   => '操作失败',
                        'data'  => $travel->getError(),
                    );
                    $this->ajaxReturn(json_encode($return_arr));
                }else {
                    //  form表单提交
                   // C('TOKEN_ON',true);
                    $travel->cat_id = $_POST['cat_id_1'];
                    $_POST['cat_id_2'] && ($travel->cat_id = $_POST['cat_id_2']);
                    $_POST['cat_id_3'] && ($travel->cat_id = $_POST['cat_id_3']);
                    if ($type == 2)
                    {
                        $travel_id = $_POST['travel_id'];
                        $travel->save(); // 写入数据到数据库
                        $travelLogic->afterSave($travel_id);
                    }
                    else
                    {                           
                        $travel_id = $insert_id = $travel->add(); // 写入数据到数据库
                        $travelLogic->afterSave($travel_id);
                    }                                        

                    
                    $return_arr = array(
                        'status' => 1,
                        'msg'   => '操作成功',                        
                        'data'  => array('url'=>U('Admin/Travel/travelList')),
                    );
                    $this->ajaxReturn(json_encode($return_arr));
                }  
            }
            
            $travelInfo = D('Travel')->where('travel_id='.I('GET.id',0))->find();
            //$cat_list = $GoodsLogic->goods_cat_list(); // 已经改成联动菜单            
            $level_cat = $travelLogic->find_parent_cat($travelInfo['cat_id']); // 获取分类默认选中的下拉框
            $cat_list = M('travel_category')->where("parent_id = 0")->select(); // 已经改成联动菜单
            $travelImages = M("Travel_images")->where('travel_id ='.I('GET.id',0))->select();
            $this->assign('travelImages',$travelImages);  // 商品相册
            $this->assign('level_cat',$level_cat);
            $this->assign('cat_list',$cat_list);
            $this->assign('travelInfo',$travelInfo);  // 服务详情
            $this->initEditor(); // 编辑器
            $this->display('travel');
    } 
          

    /**
     * 更改指定表的指定字段
     */
    public function updateField(){
        $primary = array(
                'goods' => 'goods_id',
                'goods_category' => 'id',
                'brand' => 'id',            
                'goods_attribute' => 'attr_id',
        		'ad' =>'ad_id',            
        );        
        $model = D($_POST['table']);
        $model->$primary[$_POST['table']] = $_POST['id'];
        $model->$_POST['field'] = $_POST['value'];        
        $model->save();   
        $return_arr = array(
            'status' => 1,
            'msg'   => '操作成功',                        
            'data'  => array('url'=>U('Admin/Goods/goodsAttributeList')),
        );
        $this->ajaxReturn(json_encode($return_arr));
    }

    /**
     * 删除景点
     */
    public function delTravel()
    {

        M("Travel")->where('travel_id ='.$_GET['id'])->delete();
        $return_arr = array('status' => 1,'msg' => '操作成功','data'  =>'',);   //$return_arr = array('status' => -1,'msg' => '删除失败','data'  =>'',);        
        $this->ajaxReturn(json_encode($return_arr));
    }


    /**
     *  班期列表
     */
    public function scheduleList(){
        $this->display();
    }
    /**
     *  列表
     */
    public function ajaxScheduleList(){

        $where = ' 1 = 1 '; // 搜索条件

        // 关键词搜索
        $key_word = I('key_word') ? trim(I('key_word')) : '';
        if($key_word)
        {
            $where = "$where and (schedule_name like '%$key_word%')" ;
        }

        $model = M('Schedule');
        $count = $model->where($where)->count();
        $Page  = new AjaxPage($count,10);
        $show = $Page->show();
        $order_str = "`{$_POST['orderby1']}` {$_POST['orderby2']}";
        $scheduleList = $model->where($where)->order($order_str)->limit($Page->firstRow.','.$Page->listRows)->select();
        $catList = D('Travel')->select();
        $catList = convert_arr_key($catList, 'travel_id');
        $this->assign('catList',$catList);
        $this->assign('scheduleList',$scheduleList);
        $this->assign('page',$show);// 赋值分页输出
        $this->display();
    }


    /**
     * 添加修改班期
     */
    public function addEditSchedule(){
        $schedule = D('Schedule'); //
        $type = $_POST['schedule_id'] > 0 ? 2 : 1; // 标识自动验证时的 场景 1 表示插入 2 表示更新
        //ajax提交验证
        if(($_GET['is_ajax'] == 1) && IS_POST)
        {
            C('TOKEN_ON',false);
            if($schedule->create()) {
                if ($type == 2) {
                    $schedule->save(); // 写入数据到数据库
                } else {
                    $schedule->add(); // 写入数据到数据库
                }

                $return_arr = array(
                    'status' => 1,
                    'msg' => '操作成功',
                    'data' => array('url' => U('Admin/Travel/scheduleList')),
                );
                $this->ajaxReturn(json_encode($return_arr));
            }
        }

        $scheduleInfo = D('Schedule')->where('schedule_id='.I('GET.id',0))->find();
        $travel_list = M('travel')->select();
        $this->assign('travel_list',$travel_list);
        $this->assign('scheduleInfo',$scheduleInfo);
        $this->initEditor(); // 编辑器
        $this->display('schedule');
    }
    /**
     * 删除班期
     */
    public function delSchedule()
    {

        M("Schedule")->where('schedule_id ='.$_GET['id'])->delete();
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


        $model = M('travelorder');
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
        M("travelorder")->where('order_id ='.$_GET['id'])->delete();
        $return_arr = array('status' => 1,'msg' => '操作成功','data'  =>'',);   //$return_arr = array('status' => -1,'msg' => '删除失败','data'  =>'',);
        $this->ajaxReturn(json_encode($return_arr));
    }

    /**
     * 初始化编辑器链接     
     * 本编辑器参考 地址 http://fex.baidu.com/ueditor/
     */
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