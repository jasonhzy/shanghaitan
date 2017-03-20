<?php
namespace Admin\Model;
use Think\Model;
class AreaModel extends Model {
    protected $patchValidate = true; // 系统支持数据的批量验证功能，
    /**
     *     
        self::EXISTS_VALIDATE 或者0 存在字段就验证（默认）
        self::MUST_VALIDATE 或者1 必须验证
        self::VALUE_VALIDATE或者2 值不为空的时候验证
     * 
     * 
        self::MODEL_INSERT或者1新增数据时候验证
        self::MODEL_UPDATE或者2编辑数据时候验证
        self::MODEL_BOTH或者3全部情况下验证（默认）       
     */
    protected $_validate = array(
        array('name','require','区域必须填写！',1 ,'',3),
        //array('cat_id','require','商品分类必须填写！',1 ,'',3),        
        array('cat_id','/[1-9]+$/','生活分类必须填写。',1,'regex',3),
        array('life_price','/\d{1,10}(\.\d{1,2})?$/','服务价格格式不对。',2,'regex'),
        //array('member_price','/\d{1,10}(\.\d{1,2})?$/','会员价格式不对。',2,'regex'),
        //array('market_price','/\d{1,10}(\.\d{1,2})?$/','市场价格式不对。',2,'regex'), // currency
		//array('exchange_integral','checkExchangeIntegral','积分抵扣金额不能超过商品总额',0,'callback'),
	
     );   
    
    
    
    /**
     * 后置操作方法
     * 自定义的一个函数 用于数据保存后做的相应处理操作, 使用时手动调用
     * @param int $life_id 商品id
     */
    public function afterSave($life_id)
    {            

         $this->where("life_id = $life_id ")->save(); // 根据条件更新记录

         // 商品图片相册  图册
         if(count($_POST['life_images']) > 1)
         {             
             array_unshift($_POST['life_images'],$_POST['original_img']); // 商品原始图 默认为 相册第一张图片             
             array_pop($_POST['life_images']); // 弹出最后一个             
             $lifeImagesArr = M('lifeImages')->where("life_id = $life_id")->getField('img_id,image_url'); // 查出所有已经存在的图片
             
             // 删除图片
             foreach($lifeImagesArr as $key => $val)
             {
                 if(!in_array($val, $_POST['life_images']))
                     M('lifeImages')->where("img_id = {$key}")->delete(); // 删除所有状态为0的用户数据
             }
             // 添加图片
             foreach($_POST['life_images'] as $key => $val)
             {
                 if($val == null)  continue;                                  
                 if(!in_array($val, $lifeImagesArr))
                 {                 
                        $data = array(
                            'life_id' => $life_id,
                            'image_url' => $val,
                        );
                        M("lifeImages")->data($data)->add();; // 实例化User对象                     
                 }
             }
         }

         refresh_stock($life_id); // 刷新商品库存
    }
	  protected function checkExchangeIntegral()
    {
        $exchange_integral = I('exchange_integral', 0);
        $shop_price = I('shop_price', 0);
        $point_rate_value = tpCache('shopping.point_rate');
        $point_rate_value = empty($point_rate_value) ? 0 : $point_rate_value;
        if ($exchange_integral > ($shop_price * $point_rate_value)) {
            return false;
        } else {
            return true;
        }
    }
}
