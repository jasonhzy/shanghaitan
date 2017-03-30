<?php
namespace Admin\Controller;
use Admin\Logic\GoodsLogic;
use Think\AjaxPage;
use Think\Page;

class GoodsController extends BaseController {

    /**
     * 报名列表 免费设计
     *
     */
    public function applyList(){
        $tal =  M('Apply');
        $check = trim($_POST['keywords']);
        $where = "sid = 0 ";
        if($_POST){
            $ckd = $_POST['checked'];
            $where .= "and checked = ".$ckd ;
        }

        if($check){
            if(substr($check,0,1)==1){
                $where .=" and mobile=".$check ;
            }else{
                $where .= " and area like '%$check%'or name like '%$check%' or c_name like '%$check%'";
            }
        }
        $count = $tal->where($where)->count();// 查询满足要求的总记录数
        $page = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数
        $list = $tal->where($where)->order('time desc')->limit($page->firstRow.','.$page->listRows)->select();
        $page = $page->show();//分页显示输出
        $minge = M('minge')->where('id=1')->find();
        $this->assign('minge',$minge);
        $this->assign('list',$list);
        $this->assign('page',$page);
        $this->display();
    }
    /**
     * 审核通过
     */
    public function ajaxLink(){
        $goods_id = I('get.goods_id');
        if($goods_id == ''){
            exit($this->error('该用户不存在'));
        }
        $data['checked'] = 1;
        $r = D('apply')->where('id='.$goods_id)->save($data);
        if($r) exit(json_encode(1));
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
    public function companyList(){

        $this->display();
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

        $model = M('shanghu');
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
            $Goods = D('shanghu'); //
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
            
            $goodsInfo = D('shanghu')->where('goods_id='.I('GET.id',0))->find();
            $this->assign('goodsInfo',$goodsInfo);
            /*$goodsImages = M("GoodsImages")->where('goods_id ='.I('GET.id',0))->select();
            $this->assign('goodsImages',$goodsImages);  // 商品相册*/
            $this->initEditor(); // 编辑器
            $this->display('_shanghu');
    }
    /**
     * 删除商户
     */
    public function delShanghu()
    {

        M("shanghu")->where('goods_id ='.$_GET['id'])->delete();
        $return_arr = array('status' => 1,'msg' => '操作成功','data'  =>'',);   //$return_arr = array('status' => -1,'msg' => '删除失败','data'  =>'',);
        $this->ajaxReturn(json_encode($return_arr));
    }
    /*
    * 商户审核
    * */
    public function checkList(){
        $count = M('shanghu')->where('is_check=0')->count();// 查询满足要求的总记录数
        $Page = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数
        $goodsInfo = M('shanghu')->where(array('is_check'=>0))->order('reg_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();
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
        $r = D('shanghu')->where('goods_id='.$goods_id)->save($data);
        if($r) exit(json_encode(1));
    }
    /*
     * 免费报价列表
     * */
    public function designList(){
        $tal =  M('Apply');
        $check = trim($_POST['keywords']);
        $where = "sid = 2 ";
        if($_POST){
            $ckd = $_POST['checked'];
            $where .= "and checked = ".$ckd ;
        }

        if($check){
            if(substr($check,0,1)==1){
                $where .=" and mobile=".$check ;
            }else{
                $where .= " and area like '%$check%'or name like '%$check%'";
            }
        }
        $count = $tal->where($where)->count();// 查询满足要求的总记录数
        $page = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数
        $list = $tal->where($where)->order('time desc')->limit($page->firstRow.','.$page->listRows)->select();
        $page = $page->show();//分页显示输出
        $minge = M('minge')->where('id=1')->find();
        $this->assign('minge',$minge);
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
     * 名额
     */
    public function minge(){
        if($_POST){
            $arr = M('minge')->where('id=1')->find();
            $data['num'] = $_POST['num'];
            $data['sum'] = ($_POST['num']-$arr['num'])+$arr['sum'];
            M('minge')->where('id=1')->save($data);
            $this->redirect('Admin/Goods/applyList');
        }
        $minge = M('minge')->where('id=1')->find();
        $this->assign('minge',$minge);
        $this->display();
    }
    /*
    * 选公司列表
    * */
    public function helpList(){
        $tal =  M('Apply');
        $check = trim($_POST['keywords']);
        $where = "sid = 3 ";
        if($_POST){
            $ckd = $_POST['checked'];
            $where .= "and checked = ".$ckd ;
        }

        if($check){
            if(substr($check,0,1)==1){
                $where .=" and mobile=".$check ;
            }else{
                $where .= " and area like '%$check%'or name like '%$check%' ";
            }
        }
        $count = $tal->where($where)->count();// 查询满足要求的总记录数
        $page = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数
        $list = $tal->where($where)->order('time desc')->limit($page->firstRow.','.$page->listRows)->select();
        $page = $page->show();//分页显示输出
        $minge = M('minge')->where('id=1')->find();
        $this->assign('minge',$minge);
        $this->assign('list',$list);
        $this->assign('page',$page);
        $this->display();
    }
    /**
     * 删除申请
     */
    public function delHelp()
    {
        // 删除此报名用户
        $re = M("Apply")->where('id ='.$_POST['id'])->delete();
        $this->ajaxReturn(json_encode($re));
    }
    /**
     *  商品列表
     */
    public function goodsList(){

        $this->display();
    }

    /**
     *  商品列表
     */
    public function ajaxList(){

        $where = ' 1 = 1 '; // 搜索条件
        I('intro')    && $where = "$where and ".I('intro')." = 1" ;
        (I('is_on_sale') !== '') && $where = "$where and is_on_sale = ".I('is_on_sale') ;

        // 关键词搜索
        $key_word = I('key_word') ? trim(I('key_word')) : '';
        if($key_word)
        {
            $where = "$where and (goods_name like '%$key_word%' or goods_sn like '%$key_word%')" ;
        }

        $model = M('Goods');
        $count = $model->where($where)->count();
        $Page  = new AjaxPage($count,10);
        /**  搜索条件下 分页赋值
        foreach($condition as $key=>$val) {
        $Page->parameter[$key]   =   urlencode($val);
        }
         */
        $show = $Page->show();
        $order_str = "`{$_POST['orderby1']}` {$_POST['orderby2']}";
        $goodsList = $model->where($where)->order($order_str)->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('goodsList',$goodsList);
        $this->assign('page',$show);// 赋值分页输出
        $this->display();
    }

    /**
     * 添加修改商品
     */
    public function addEditList(){
        $GoodsLogic = new GoodsLogic();
        $Goods = D('Goods'); //
        $type = $_POST['goods_id'] > 0 ? 2 : 1; // 标识自动验证时的 场景 1 表示插入 2 表示更新
        //ajax提交验证
        if (($_GET['is_ajax'] == 1) && IS_POST) {
            C('TOKEN_ON', false);
            if (!$Goods->create(NULL, $type))// 根据表单提交的POST数据创建数据对象
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
                $Goods->on_time = time(); // 上架时间
                if ($type == 2) {
                    $goods_id = $_POST['goods_id'];
                    $Goods->where(array('goods_id'=>$goods_id))->save(); // 写入数据到数据库
                } else {
                    $Goods->add(); // 写入数据到数据库
                }
                $return_arr = array(
                    'status' => 1,
                    'msg' => '操作成功',
                    'data' => array('url' => U('Admin/Goods/goodsList')),
                );
                $this->ajaxReturn(json_encode($return_arr));
            }
        }

        $goodsInfo = M('Goods')->where('goods_id=' . I('GET.id', 0))->find();
        $shanghu =  M('shanghu')->where('is_check = 1')->select();
        //$cat_list = $GoodsLogic->goods_cat_list(); // 已经改成联动菜单
       /* $level_cat = $GoodsLogic->find_parent_cat($goodsInfo['cat_id']); // 获取分类默认选中的下拉框
        $cat_list = M('goods_category')->where("parent_id = 0")->select(); // 已经改成联动菜单*/
        $brandList = $GoodsLogic->getSortBrands();
        /*$this->assign('level_cat', $level_cat);
        $this->assign('cat_list', $cat_list);*/
        $this->assign('shanghu', $shanghu);
        $this->assign('brandList', $brandList);
        $this->assign('goodsInfo', $goodsInfo);  // 商品详情
        $goodsImages = M("GoodsImages")->where('goods_id =' . I('GET.id', 0))->select();
        $this->assign('goodsImages', $goodsImages);  // 商品相册
        $this->initEditor(); // 编辑器
        $this->display('_goods');
    }
    /**
     * 删除商品
     */
    public function delGoods()
    {

        M("goods")->where('goods_id ='.$_GET['id'])->delete();
        $return_arr = array('status' => 1,'msg' => '操作成功','data'  =>'',);   //$return_arr = array('status' => -1,'msg' => '删除失败','data'  =>'',);
        $this->ajaxReturn(json_encode($return_arr));
    }
    /**
     *  效果图
     */
    public function effectList(){
        $this->display();
    }

    /**
     *  效果图列表
     */
    public function ajaxEffect(){

        $where = ' 1 = 1 '; // 搜索条件
        // 关键词搜索
        $key_word = I('key_word') ? trim(I('key_word')) : '';
        if($key_word)
        {
            $where = "$where and (x_title like '%$key_word%' or x_keywords like '%$key_word%')" ;
        }
        $model = M('xiaoguo');
        $count = $model->where($where)->count();
        $Page  = new AjaxPage($count,10);
        $show = $Page->show();
        $order_str = "`{$_POST['orderby1']}` {$_POST['orderby2']}";
        $effectList = $model->where($where)->order($order_str)->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('effectList',$effectList);
        $this->assign('page',$show);// 赋值分页输出
        $this->display();
    }

    /**
     * 添加修改效果图
     */
    public function addEditEffect(){
        $effect = D('xiaoguo'); //
        $type = $_POST['x_id'] > 0 ? 2 : 1; // 标识自动验证时的 场景 1 表示插入 2 表示更新
        //ajax提交验证
        if (($_GET['is_ajax'] == 1) && IS_POST) {
            C('TOKEN_ON', false);
            if (!$effect->create(NULL, $type))// 根据表单提交的POST数据创建数据对象
            {
                //  编辑
                $return_arr = array(
                    'status' => -1,
                    'msg'   => '操作失败',
                    'data'  => $effect->getError(),
                );
                $this->ajaxReturn(json_encode($return_arr));
            }else {
                //  form表单提交
                $effect->x_time = time(); // 上架时间
                if ($type == 2) {
                    $x_id = $_POST['x_id'];
                    $effect->where(array('x_id'=>$x_id))->save(); // 写入数据到数据库
                } else {
                    $effect->add(); // 写入数据到数据库
                }
                $return_arr = array(
                    'status' => 1,
                    'msg' => '操作成功',
                    'data' => array('url' => U('Admin/Goods/effectList')),
                );
                $this->ajaxReturn(json_encode($return_arr));
            }
        }
        $effectInfo = M('xiaoguo')->where('x_id=' . I('GET.id', 0))->find();
        $xiaoguo =  M('xiaoguo')->where('x_parent_id = 0')->select();
        $this->assign('xiaoguo', $xiaoguo);
        $this->assign('effectInfo', $effectInfo);
        $this->display('_effect');
    }
    /**
     * 删除商品
     */
    public function delEffect()
    {

        M("xiaoguo")->where('x_id ='.$_GET['x_id'])->delete();
        $return_arr = array('status' => 1,'msg' => '操作成功','data'  =>'',);
        $this->ajaxReturn(json_encode($return_arr));
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
}