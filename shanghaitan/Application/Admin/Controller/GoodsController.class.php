<?php
namespace Admin\Controller;
use Admin\Logic\GoodsLogic;
use Think\AjaxPage;
use Think\Page;

class GoodsController extends BaseController {
    
    /**
     *  商户列表
     */
    public function companyList(){

        $this->display();        
    }
    
    /**
     * 报名列表
     *
     */
    public function applyList(){
        $tal =  M('Apply');
        $check = trim($_POST['keywords']);
        if($check){
            if(substr($check,0,1)==1){
                $where ="sid = 0 and mobile=".$check;
            }else{
                $where= "sid = 0 and area like '%$check%'or name like '%$check%'";
            }
            $count = $tal->where($where)->count();// 查询满足要求的总记录数
            $pager = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数
            $page = $pager->show();//分页显示输出
            $list = $tal->where($where)->order('time desc')->limit($page->firstRow.','.$page->listRows)->select();
        }else{
            $count = $tal->count();// 查询满足要求的总记录数
            $pager = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数
            $page = $pager->show();//分页显示输出
            $list = $tal->where('sid=0')->order('time desc')->limit($page->firstRow.','.$page->listRows)->select();
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
     *  商户列表
     */
    public function ajaxGoodsList(){            

        // 关键词搜索               
        $key_word = I('key_word') ? trim(I('key_word')) : '';
        if($key_word)
        {
            $where = "is_check = 1 and city like '%$key_word%' or goods_name like '%$key_word%' ";
        }

        $model = M('Goods');
        $count = $model->where($where)->count();
        $Page  = new AjaxPage($count,10);
        $show = $Page->show();
        $order_str = "`{$_POST['orderby1']}` {$_POST['orderby2']}";
        $goodsList = $model->where($where)->order($order_str)->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('goodsList',$goodsList);
        $this->assign('page',$show);// 赋值分页输出
        $this->display();         
    }    

    /**
     * 添加修改商户
     */
    public function addEditGoods(){
            $Goods = D('Goods'); //
            $type = $_POST['goods_id'] > 0 ? 2 : 1; // 标识自动验证时的 场景 1 表示插入 2 表示更新
            //ajax提交验证
            if(($_GET['is_ajax'] == 1) && IS_POST)
            {
                C('TOKEN_ON',false);
               if(!$Goods->create(NULL,$type))// 根据表单提交的POST数据创建数据对象
                {
                    //  编辑
                    $return_arr = array(
                        'status' => -1,
                        'msg'   => '操作失败',
                        'data'  => $Goods->getError(),
                    );
                    $this->ajaxReturn(json_encode($return_arr));
                }else {
                   //  form表单提交
                   $Goods->reg_time = time(); // 入驻时间
                   if ($type == 2) {
                       $goods_id = $_POST['goods_id'];
                       $Goods->where(array('goods_id'=>$goods_id))->save(); // 写入数据到数据库
                   } else {
                      $Goods->add(); // 写入数据到数据库
                   }
                   $return_arr = array(
                       'status' => 1,
                       'msg' => '操作成功',
                       'data' => array('url' => U('Admin/Goods/companyList')),
                   );
                   $this->ajaxReturn(json_encode($return_arr));
               }
            }
            
            $goodsInfo = D('Goods')->where('goods_id='.I('GET.id',0))->find();
            $this->assign('goodsInfo',$goodsInfo);  // 商品详情            
            $goodsImages = M("GoodsImages")->where('goods_id ='.I('GET.id',0))->select();
            $this->assign('goodsImages',$goodsImages);  // 商品相册
            $this->initEditor(); // 编辑器
            $this->display('_goods');                                     
    }
    /**
     * 删除商户
     */
    public function delGoods()
    {

        M("Goods")->where('goods_id ='.$_GET['id'])->delete();
        $return_arr = array('status' => 1,'msg' => '操作成功','data'  =>'',);   //$return_arr = array('status' => -1,'msg' => '删除失败','data'  =>'',);
        $this->ajaxReturn(json_encode($return_arr));
    }
    /*
    * 商户审核
    * */
    public function checkList(){
        $count = M('goods')->where('is_check=0')->count();// 查询满足要求的总记录数
        $Page = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数
        $goodsInfo = M('goods')->where(array('is_check'=>0))->order('reg_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $show = $Page->show();//分页显示输出
        $this->assign('goodsInfo',$goodsInfo);
        $this->assign('page',$show);
        $this->display();
    }
    /**
     * 审核通过
     */
    public function ajaxCheck(){
        $goods_id = I('get.goods_id');
        if($goods_id == ''){
            exit($this->error('商户不存在'));
        }
        $data['is_check'] = 1;
        $r = D('goods')->where('goods_id='.$goods_id)->save($data);
        if($r) exit(json_encode(1));
    }
    /*
     * 申请设计列表
     * */
    public function designList(){
        $tal =  M('Apply');
        $check = trim($_POST['keywords']);
        if($check){
            if(substr($check,0,1)==1){
                $where ="sid = 2 and mobile=".$check;
            }else{
                $where= "sid = 2 and area like '%$check%'or name like '%$check%'";
            }
            $count = $tal->where($where)->count();// 查询满足要求的总记录数
            $pager = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数
            $page = $pager->show();//分页显示输出
            $list = $tal->where($where)->order('time desc')->limit($page->firstRow.','.$page->listRows)->select();
        }else{
            $count = $tal->count();// 查询满足要求的总记录数
            $pager = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数
            $page = $pager->show();//分页显示输出
            $list = $tal->where('sid=2')->order('time desc')->limit($page->firstRow.','.$page->listRows)->select();
        }
        $this->assign('list',$list);
        $this->assign('page',$page);
        $this->display();
    }
    /**
     * 删除申请
     */
    public function delApp()
    {
        // 删除此报名用户
        $re = M("Apply")->where('id ='.$_POST['id'])->delete();
        $this->ajaxReturn(json_encode($re));
    }
   /**
     * 商品类型  用于设置商品的属性
     */
    public function goodsTypeList(){
        $model = M("GoodsType");                
        $count = $model->count();        
        $Page  = new Page($count,100);
        $show  = $Page->show();
        $goodsTypeList = $model->order("id desc")->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('show',$show);
        $this->assign('goodsTypeList',$goodsTypeList);
        $this->display('goodsTypeList');
    }
    
    
    /**
     * 添加修改编辑  商品属性类型
     */
    public  function addEditGoodsType(){        
            $_GET['id'] = $_GET['id'] ? $_GET['id'] : 0;            
            $model = M("GoodsType");           
            if(IS_POST)
            {
                    $model->create();
                    if($_GET['id'])
                        $model->save();
                    else
                        $model->add();
                    
                    $this->success("操作成功!!!",U('Admin/Goods/goodsTypeList'));               
                    exit;
            }           
           $goodsType = $model->find($_GET['id']);
           $this->assign('goodsType',$goodsType);
           $this->display('_goodsType');           
    }
    
    /**
     * 商品属性列表
     */
    public function goodsAttributeList(){       
        $goodsTypeList = M("GoodsType")->select();
        $this->assign('goodsTypeList',$goodsTypeList);
        $this->display();
    }   
    
    /**
     *  商品属性列表
     */
    public function ajaxGoodsAttributeList(){            
        //ob_start('ob_gzhandler'); // 页面压缩输出
        $where = ' 1 = 1 '; // 搜索条件                        
        I('type_id')   && $where = "$where and type_id = ".I('type_id') ;                
        // 关键词搜索               
        $model = M('GoodsAttribute');
        $count = $model->where($where)->count();
        $Page       = new AjaxPage($count,13);
        $show = $Page->show();
        $goodsAttributeList = $model->where($where)->order('`order` desc,attr_id DESC')->limit($Page->firstRow.','.$Page->listRows)->select();
        $goodsTypeList = M("GoodsType")->getField('id,name');
        $attr_input_type = array(0=>'手工录入',1=>' 从列表中选择',2=>' 多行文本框');
        $this->assign('attr_input_type',$attr_input_type);
        $this->assign('goodsTypeList',$goodsTypeList);        
        $this->assign('goodsAttributeList',$goodsAttributeList);
        $this->assign('page',$show);// 赋值分页输出
        $this->display();         
    }   
    
    /**
     * 添加修改编辑  商品属性
     */
    public  function addEditGoodsAttribute(){
                        
            $model = D("GoodsAttribute");                      
            $type = $_POST['attr_id'] > 0 ? 2 : 1; // 标识自动验证时的 场景 1 表示插入 2 表示更新         
            $_POST['attr_values'] = str_replace('_', '', $_POST['attr_values']); // 替换特殊字符
            $_POST['attr_values'] = str_replace('@', '', $_POST['attr_values']); // 替换特殊字符            
            $_POST['attr_values'] = trim($_POST['attr_values']);

            if(($_GET['is_ajax'] == 1) && IS_POST)//ajax提交验证
            {                
                C('TOKEN_ON',false);
                if(!$model->create(NULL,$type))// 根据表单提交的POST数据创建数据对象                 
                {
                    //  编辑
                    $return_arr = array(
                        'status' => -1,
                        'msg'   => '',
                        'data'  => $model->getError(),
                    );
                    $this->ajaxReturn(json_encode($return_arr));
                }else {                   
                   // C('TOKEN_ON',true); //  form表单提交
                    if ($type == 2)
                    {
                        $model->save(); // 写入数据到数据库                        
                    }
                    else
                    {
                        $insert_id = $model->add(); // 写入数据到数据库                        
                    }
                    $return_arr = array(
                        'status' => 1,
                        'msg'   => '操作成功',                        
                        'data'  => array('url'=>U('Admin/Goods/goodsAttributeList')),
                    );
                    $this->ajaxReturn(json_encode($return_arr));
                }  
            }                
           // 点击过来编辑时                 
           $_GET['attr_id'] = $_GET['attr_id'] ? $_GET['attr_id'] : 0;       
           $goodsTypeList = M("GoodsType")->select();           
           $goodsAttribute = $model->find($_GET['attr_id']);           
           $this->assign('goodsTypeList',$goodsTypeList);                   
           $this->assign('goodsAttribute',$goodsAttribute);
           $this->display('_goodsAttribute');           
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
     * 动态获取商品属性输入框 根据不同的数据返回不同的输入框类型
     */
    public function ajaxGetAttrInput(){
        $GoodsLogic = new GoodsLogic();
        $str = $GoodsLogic->getAttrInput($_REQUEST['goods_id'],$_REQUEST['type_id']);
        exit($str);
    }


    
    /**
     * 删除商品类型 
     */
    public function delGoodsType()
    {
        // 判断 商品规格        
        $count = M("Spec")->where("type_id = {$_GET['id']}")->count("1");   
        $count > 0 && $this->error('该类型下有商品规格不得删除!',U('Admin/Goods/goodsTypeList'));
        // 判断 商品属性        
        $count = M("GoodsAttribute")->where("type_id = {$_GET['id']}")->count("1");   
        $count > 0 && $this->error('该类型下有商品属性不得删除!',U('Admin/Goods/goodsTypeList'));        
        // 删除分类
        M('GoodsType')->where("id = {$_GET['id']}")->delete();   
        $this->success("操作成功!!!",U('Admin/Goods/goodsTypeList'));
    }    

    /**
     * 删除商品属性
     */
    public function delGoodsAttribute()
    {         
        // 判断 有无商品使用该属性
        $count = M("GoodsAttr")->where("attr_id = {$_GET['id']}")->count("1");   
        $count > 0 && $this->error('有商品使用该属性,不得删除!',U('Admin/Goods/goodsAttributeList'));                        
        // 删除 属性
        M('GoodsAttribute')->where("attr_id = {$_GET['id']}")->delete();   
        $this->success("操作成功!!!",U('Admin/Goods/goodsAttributeList'));
    }            
    
    /**
     * 删除商品规格
     */
    public function delGoodsSpec()
    {
        // 判断 商品规格项
        $count = M("SpecItem")->where("spec_id = {$_GET['id']}")->count("1");   
        $count > 0 && $this->error('清空规格项后才可以删除!',U('Admin/Goods/specList'));
        // 删除分类
        M('Spec')->where("id = {$_GET['id']}")->delete();   
        $this->success("操作成功!!!",U('Admin/Goods/specList'));
    } 
    
    /**
     * 品牌列表
     */
    public function brandList(){  
        $model = M("Brand"); 
        $where = "";
        $keyword = I('keyword');
        $where = $keyword ? " name like '%$keyword%' " : "";
        $count = $model->where($where)->count();
        $Page  = new Page($count,10);        
        $brandList = $model->where($where)->order("`sort` asc")->limit($Page->firstRow.','.$Page->listRows)->select();        
        $show  = $Page->show(); 
        $cat_list = M('goods_category')->where("parent_id = 0")->getField('id,name'); // 已经改成联动菜单
        $this->assign('cat_list',$cat_list);       
        $this->assign('show',$show);
        $this->assign('brandList',$brandList);
        $this->display('brandList');
    }
    
    /**
     * 添加修改编辑  商品品牌
     */
    public  function addEditBrand(){        
            $id = I('id');
            $model = M("Brand");           
            if(IS_POST)
            {
                    $model->create();
                    if($id)
                        $model->save();
                    else
                        $id = $model->add();
                    
                    $this->success("操作成功!!!",U('Admin/Goods/brandList',array('p'=>$_GET['p'])));               
                    exit;
            }           
           $cat_list = M('goods_category')->where("parent_id = 0")->select(); // 已经改成联动菜单
           $this->assign('cat_list',$cat_list);           
           $brand = $model->find($id);
           $this->assign('brand',$brand);
           $this->display('_brand');           
    }    
    
    /**
     * 删除品牌
     */
    public function delBrand()
    {        
        // 判断此品牌是否有商品在使用
        $goods_count = M('Goods')->where("brand_id = {$_GET['id']}")->count('1');        
        if($goods_count)
        {
            $return_arr = array('status' => -1,'msg' => '此品牌有商品在用不得删除!','data'  =>'',);   //$return_arr = array('status' => -1,'msg' => '删除失败','data'  =>'',);        
            $this->ajaxReturn(json_encode($return_arr));            
        }
        
        $model = M("Brand"); 
        $model->where('id ='.$_GET['id'])->delete(); 
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
    
    
    
    /**
     * 商品规格列表    
     */
    public function specList(){       
        $goodsTypeList = M("GoodsType")->select();
        $this->assign('goodsTypeList',$goodsTypeList);
        $this->display();
    }
    
    
    /**
     *  商品规格列表
     */
    public function ajaxSpecList(){ 
        //ob_start('ob_gzhandler'); // 页面压缩输出
        $where = ' 1 = 1 '; // 搜索条件                        
        I('type_id')   && $where = "$where and type_id = ".I('type_id') ;        
        // 关键词搜索               
        $model = D('spec');
        $count = $model->where($where)->count();
        $Page       = new AjaxPage($count,13);
        $show = $Page->show();
        $specList = $model->where($where)->order('`type_id` desc')->limit($Page->firstRow.','.$Page->listRows)->select();        
        $GoodsLogic = new GoodsLogic();        
        foreach($specList as $k => $v)
        {       // 获取规格项     
                $arr = $GoodsLogic->getSpecItem($v['id']);
                $specList[$k]['spec_item'] = implode(' , ', $arr);
        }
        
        $this->assign('specList',$specList);
        $this->assign('page',$show);// 赋值分页输出
        $goodsTypeList = M("GoodsType")->select(); // 规格分类
        $goodsTypeList = convert_arr_key($goodsTypeList, 'id');
        $this->assign('goodsTypeList',$goodsTypeList);        
        $this->display();         
    }      
    /**
     * 添加修改编辑  商品规格
     */
    public  function addEditSpec(){
                        
            $model = D("spec");                      
            $type = $_POST['id'] > 0 ? 2 : 1; // 标识自动验证时的 场景 1 表示插入 2 表示更新             
            if(($_GET['is_ajax'] == 1) && IS_POST)//ajax提交验证
            {                
                C('TOKEN_ON',false);
                if(!$model->create(NULL,$type))// 根据表单提交的POST数据创建数据对象                 
                {
                    //  编辑
                    $return_arr = array(
                        'status' => -1,
                        'msg'   => '',
                        'data'  => $model->getError(),
                    );
                    $this->ajaxReturn(json_encode($return_arr));
                }else {                   
                   // C('TOKEN_ON',true); //  form表单提交
                    if ($type == 2)
                    {
                        $model->save(); // 写入数据到数据库                        
                        $model->afterSave($_POST['id']);
                    }
                    else
                    {
                        $insert_id = $model->add(); // 写入数据到数据库        
                        $model->afterSave($insert_id);
                    }                    
                    $return_arr = array(
                        'status' => 1,
                        'msg'   => '操作成功',                        
                        'data'  => array('url'=>U('Admin/Goods/specList')),
                    );
                    $this->ajaxReturn(json_encode($return_arr));
                }  
            }                
           // 点击过来编辑时                 
           $id = $_GET['id'] ? $_GET['id'] : 0;       
           $spec = $model->find($id);
           $GoodsLogic = new GoodsLogic();  
           $items = $GoodsLogic->getSpecItem($id);
           $spec[items] = implode(PHP_EOL, $items); 
           $this->assign('spec',$spec);
           
           $goodsTypeList = M("GoodsType")->select();           
           $this->assign('goodsTypeList',$goodsTypeList);           
           $this->display('_spec');           
    }  
    
    
    /**
     * 动态获取商品规格选择框 根据不同的数据返回不同的选择框
     */
    public function ajaxGetSpecSelect(){
        $goods_id = $_GET['goods_id'] ? $_GET['goods_id'] : 0;        
        $GoodsLogic = new GoodsLogic();
        //$_GET['spec_type'] =  13;
        $specList = D('Spec')->where("type_id = ".$_GET['spec_type'])->order('`order` desc')->select();
        foreach($specList as $k => $v)        
            $specList[$k]['spec_item'] = D('SpecItem')->where("spec_id = ".$v['id'])->getField('id,item'); // 获取规格项                
        
        $items_id = M('SpecGoodsPrice')->where('goods_id = '.$goods_id)->getField("GROUP_CONCAT(`key` SEPARATOR '_') AS items_id");
        $items_ids = explode('_', $items_id);       
        
        // 获取商品规格图片                
        if($goods_id)
        {
           $specImageList = M('SpecImage')->where("goods_id = $goods_id")->getField('spec_image_id,src');                 
        }        
        $this->assign('specImageList',$specImageList);
        
        $this->assign('items_ids',$items_ids);
        $this->assign('specList',$specList);
        $this->display('ajax_spec_select');        
    }    
    
    /**
     * 动态获取商品规格输入框 根据不同的数据返回不同的输入框
     */    
    public function ajaxGetSpecInput(){     
         $GoodsLogic = new GoodsLogic();
         $goods_id = $_REQUEST['goods_id'] ? $_REQUEST['goods_id'] : 0;
         $str = $GoodsLogic->getSpecInput($goods_id ,$_POST['spec_arr']);
         exit($str);   
    }

}