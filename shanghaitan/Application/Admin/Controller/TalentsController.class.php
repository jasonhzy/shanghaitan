<?php

namespace Admin\Controller;
use Think\AjaxPage;
use Think\Page;

class TalentsController extends BaseController {
	/**
	 *  职位列表
	 */
	public function positionList(){

		$this->display();
	}

	/**
	 *  列表
	 */
	public function ajaxPositionList(){

		$where = ' 1 = 1 '; // 搜索条件

		// 关键词搜索
		$key_word = I('key_word') ? trim(I('key_word')) : '';
		if($key_word)
		{
			$where = "$where and (name like '%$key_word%' )" ;
		}


		$model = M('Position');
		$count = $model->where($where)->count();
		$Page  = new AjaxPage($count,10);
		$show = $Page->show();
		$order_str = "`{$_POST['orderby1']}` {$_POST['orderby2']}";
		$positionList = $model->where($where)->order($order_str)->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign('positionList',$positionList);
		$this->assign('page',$show);// 赋值分页输出
		$this->display();
	}


	/**
	 * 添加职位
	 */
	public function addEditPosition(){

		$position = D('Position'); //
		$type = $_POST['p_id'] > 0 ? 2 : 1; // 标识自动验证时的 场景 1 表示插入 2 表示更新
		//ajax提交验证
		if(($_GET['is_ajax'] == 1) && IS_POST)
		{
			if($position->create())// 根据表单提交的POST数据创建数据对象
			{

				C('TOKEN_ON', false);
				//  form表单提交
				// C('TOKEN_ON',true);
				$position->addtime = time();
				$position->end = strtotime($_POST['end']);
				if ($type == 2) {
					$id = $_POST['p_id'];
					$position->save(); // 写入数据到数据库
				} else {
					$id = $insert_id = $position->add(); // 写入数据到数据库
				}


				$return_arr = array(
						'status' => 1,
						'msg' => '操作成功',
						'data' => array('url' => U('Admin/Talents/positionList')),
				);
				$this->ajaxReturn(json_encode($return_arr));
			}
		}

		$positionInfo = D('Position')->where('p_id='.I('GET.id',0))->find();
		$this->assign('positionInfo',$positionInfo);  // 商品详情
		$this->initEditor(); // 编辑器
		$this->display();
	}


	/**
	 * 删除职位
	 */
	public function delPosition()
	{
		// 删除此职位
		M("Position")->where('p_id ='.$_GET['id'])->delete();
		$return_arr = array('status' => 1,'msg' => '操作成功','data'  =>'',);   //$return_arr = array('status' => -1,'msg' => '删除失败','data'  =>'',);
		$this->ajaxReturn(json_encode($return_arr));
	}
	/**
	 *  人才列表
	 */
	public function talList(){
		$tal =  M('Talents');
		$count = $tal->count();// 查询满足要求的总记录数
		$pager = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数
		$page = $pager->show();//分页显示输出
		$list = M('Talents')->order('t_id desc')->limit($page->firstRow.','.$page->listRows)->select();
		$this->assign('list',$list);
		$this->assign('page',$page);
		$this->display();
	}


	/**
	 * 删除
	 */
	public function delTalents()
	{
		// 删除此商品
		$re = M("Talents")->where('t_id ='.$_GET['id'])->delete();
		$this->ajaxReturn(json_encode($re));
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