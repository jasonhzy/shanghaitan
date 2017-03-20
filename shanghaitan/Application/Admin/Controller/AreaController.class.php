<?php
namespace Admin\Controller;
use Admin\Logic\LifeLogic;
use Think\AjaxPage;
use Think\Page;

class AreaController extends BaseController {
    
    /**
     *  区域列表
     *
     */
    public function areaList(){
        $count = M('area')->count();
        $Page  = new Page($count,5);
        $show = $Page->show();
        $arr = M('area')->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('cat_list',$arr);
        $this->assign('page',$show);
        $this->display();        
    }
    
    /**
     * 添加修改区域
     */
    public function addEditArea(){
           if(IS_GET)
            {
                $area = D('Area')->where('id='.I('GET.id',0))->find();
                //echo "11";exit;
                $this->assign('area',$area);
                $this->display('_area');
                exit;
            }

            $area = D('Area'); //

            $type = $_POST['id'] > 0 ? 2 : 1; // 标识自动验证时的 场景 1 表示插入 2 表示更新                        
            //ajax提交验证
            if($_GET['is_ajax'] == 1)
            {
                C('TOKEN_ON',false);
                

                
                if(!$area->create(NULL,$type))// 根据表单提交的POST数据创建数据对象
                {
                    //  编辑
                    $return_arr = array(
                        'status' => -1,
                        'msg'   => '操作失败!',
                        'data'  => $area->getError(),
                    );
                    $this->ajaxReturn(json_encode($return_arr));
                }else {
                    //  form表单提交
                    C('TOKEN_ON',true);

                    if ($type == 2)
                    {
                        $area->where(array('id'=>$_POST['id']))->save(); // 写入数据到数据库

                    }
                    else
                    {
                         $area->add(); // 写入数据到数据库
                    }
                    $return_arr = array(
                        'status' => 1,
                        'msg'   => '操作成功',
                        'data'  => array('url'=>U('Admin/Area/areaList')),
                    );
                    $this->ajaxReturn(json_encode($return_arr));

                }  
            }

    }
    

    
    /**
     * 删除区域
     */
    public function delArea(){
        $area = M("Area");
        // 判断该区域下是否存在城市
        $life_count = M('city')->where("parent_id = {$_GET['id']}")->count('1');
        $life_count > 0 && $this->error('该区域下有城市，不能删除!',U('Admin/Area/AreaList'));
        // 删除区域
        $area->where("id = {$_GET['id']}")->delete();
        $this->success("操作成功!!!",U('Admin/Area/areaList'));
    }
    /**
     *  城市列表
     */
    public function cityList(){
        $this->display();
    }

    /**
     *  列表
     */
    public function ajaxCityList(){

        $where = ' 1 = 1 '; // 搜索条件

        // 关键词搜索
        $key_word = I('key_word') ? trim(I('key_word')) : '';
        if($key_word)
        {
            $where = "$where and (name like '%$key_word%')" ;
        }
        $model = M('City');
        $count = $model->where($where)->count();
        $Page  = new AjaxPage($count,10);
        $show = $Page->show();
        $order_str = "`{$_POST['orderby1']}` {$_POST['orderby2']}";
        $cityList = $model->where($where)->order($order_str)->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('cityList',$cityList);
        $this->assign('page',$show);// 赋值分页输出
        $this->display();
    }
    /**
     * 添加修改养老院
     */
    public function addCity(){
        $city = D('City'); //
        $type = $_POST['id'] > 0 ? 2 : 1; // 标识自动验证时的 场景 1 表示插入 2 表示更新
        //ajax提交验证
        if(($_GET['is_ajax'] == 1) && IS_POST)
        {
            C('TOKEN_ON',false);
            if($city->create())// 根据表单提交的POST数据创建数据对象
            {
                //  编辑
                if ($type == 2)
                {
                    $resthouse_id = $_POST['resthouse_id'];
                    $city->save(); // 写入数据到数据库
                }
                else
                {
                    $insert_id = $city->add(); // 写入数据到数据库
                }


                $return_arr = array(
                    'status' => 1,
                    'msg'   => '操作成功',
                    'data'  => array('url'=>U('Admin/Area/cityList')),
                );
                $this->ajaxReturn(json_encode($return_arr));
            }
        }

        $area = D('Area')->order('id desc')->select();
        $this->assign('area',$area);  // 服务详情
        $this->initEditor(); // 编辑器
        $this->display('city');
    }

    /**
     * 删除养老院
     */
    public function delCity()
    {


        // 删除养老院
        M("city")->where('id ='.$_GET['id'])->delete();
        $return_arr = array('status' => 1,'msg' => '操作成功','data'  =>'',);   //$return_arr = array('status' => -1,'msg' => '删除失败','data'  =>'',);
        $this->ajaxReturn(json_encode($return_arr));
    }
    /**
     *  生活服务列表
     */
    public function lifeList(){
        $lifeLogic = new LifeLogic();
        $categoryList = $lifeLogic->getSortCategory();
        $this->assign('categoryList',$categoryList);
        $this->display();
    }
    /**
     *  列表
     */
    public function ajaxLifeList(){
        
        $where = ' 1 = 1 '; // 搜索条件                

        $cat_id = I('cat_id');
        // 关键词搜索               
        $key_word = I('key_word') ? trim(I('key_word')) : '';
        if($key_word)
        {
            $where = "$where and (life_name like '%$key_word%')" ;
        }
        
        if($cat_id > 0)
        {
            $grandson_ids = getCatGrandson($cat_id); 
            $where .= " and cat_id in(".  implode(',', $grandson_ids).") "; // 初始化搜索条件
        }
        
        
        $model = M('Life');
        $count = $model->where($where)->count();
        $Page  = new AjaxPage($count,10);
        /**  搜索条件下 分页赋值
        foreach($condition as $key=>$val) {
            $Page->parameter[$key]   =   urlencode($val);
        }
        */
        $show = $Page->show();
        $order_str = "`{$_POST['orderby1']}` {$_POST['orderby2']}";
        $lifeList = $model->where($where)->order($order_str)->limit($Page->firstRow.','.$Page->listRows)->select();

        $catList = D('life_category')->select();
        $catList = convert_arr_key($catList, 'id');
        $this->assign('catList',$catList);
        $this->assign('lifeList',$lifeList);
        $this->assign('page',$show);// 赋值分页输出
        $this->display();         
    }    
    
    
    /**
     * 添加修改服务
     */
    public function addEditLife(){
        
            $lifeLogic = new LifeLogic();
            $life = D('Life'); //
            $type = $_POST['life_id'] > 0 ? 2 : 1; // 标识自动验证时的 场景 1 表示插入 2 表示更新
            //ajax提交验证
            if(($_GET['is_ajax'] == 1) && IS_POST)
            {                
                C('TOKEN_ON',false);
                if(!$life->create(NULL,$type))// 根据表单提交的POST数据创建数据对象
                {
                    //  编辑
                    $return_arr = array(
                        'status' => -1,
                        'msg'   => '操作失败',
                        'data'  => $life->getError(),
                    );
                    $this->ajaxReturn(json_encode($return_arr));
                }else {
                    //  form表单提交
                   // C('TOKEN_ON',true);
                    $life->cat_id = $_POST['cat_id_1'];
                    $_POST['cat_id_2'] && ($life->cat_id = $_POST['cat_id_2']);
                    $_POST['cat_id_3'] && ($life->cat_id = $_POST['cat_id_3']);
                    
                    if ($type == 2)
                    {
                        $life_id = $_POST['life_id'];
                        $life->save(); // 写入数据到数据库
                    }
                    else
                    {                           
                        $life_id = $insert_id = $life->add(); // 写入数据到数据库
                    }                                        

                    
                    $return_arr = array(
                        'status' => 1,
                        'msg'   => '操作成功',                        
                        'data'  => array('url'=>U('Admin/Life/lifeList')),
                    );
                    $this->ajaxReturn(json_encode($return_arr));
                }  
            }
            
            $lifeInfo = D('Life')->where('life_id='.I('GET.id',0))->find();
            //$cat_list = $GoodsLogic->goods_cat_list(); // 已经改成联动菜单            
            $level_cat = $lifeLogic->find_parent_cat($lifeInfo['cat_id']); // 获取分类默认选中的下拉框
            $cat_list = M('life_category')->where("parent_id = 0")->select(); // 已经改成联动菜单

            $this->assign('level_cat',$level_cat);
            $this->assign('cat_list',$cat_list);
            $this->assign('lifeInfo',$lifeInfo);  // 服务详情
            $this->initEditor(); // 编辑器
            $this->display('life');
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
     * 删除服务
     */
    public function delLife()
    {
        
        // 判断此服务是否有预约

        // 删除此服务
        M("Life")->where('life_id ='.$_GET['id'])->delete();
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


        $model = M('lifeorder');
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
        M("lifeorder")->where('order_id ='.$_GET['id'])->delete();
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