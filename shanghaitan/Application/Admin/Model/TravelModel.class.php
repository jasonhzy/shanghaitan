<?php
namespace Admin\Model;
use Think\Model;
class GoodsModel extends Model {
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
        array('goods_name','require','商品名称必须填写！',1 ,'',3),         
        //array('cat_id','require','商品分类必须填写！',1 ,'',3),        
        array('cat_id','/[1-9]+$/','商品分类必须填写。',1,'regex',3),
        array('goods_sn','','商品货号重复！',2,'unique',1),        
        array('shop_price','/\d{1,10}(\.\d{1,2})?$/','本店售价格式不对。',2,'regex'),        
        array('member_price','/\d{1,10}(\.\d{1,2})?$/','会员价格式不对。',2,'regex'),        
        array('market_price','/\d{1,10}(\.\d{1,2})?$/','市场价格式不对。',2,'regex'), // currency
        array('weight','/\d{1,10}(\.\d{1,2})?$/','重量格式不对。',2,'regex'),
		array('exchange_integral','checkExchangeIntegral','积分抵扣金额不能超过商品总额',0,'callback'),
	
     );   
    
    

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
