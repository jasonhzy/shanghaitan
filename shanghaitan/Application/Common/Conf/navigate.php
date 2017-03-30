<?php
// 面包屑导航配置
    return array(        
           	'home/user'=>array(
                'name' =>'用户中心',
                'action'=>array(
                     'index'=>'我的商城',
                     'order_list'=>'我的订单',           
                     'order_detail'=>'订单详情',
                     'goods_collect'=>'我的收藏',
                     'info'=>'个人信息',
                     'password'=>'修改密码',
                     'account'=>'我的资金',
                     'coupon'=>'优惠券',
                     'address_list'=>'收货地址管理',
                     'comment'=>'订单评价',       
            	    )
               ),             		
              'admin/index'=>array(
                'name' =>'上海滩后台管理',
                'action'=>array(
                     'index'=>'欢迎页面',                     
         	       )
               ),
			'admin/music'=>array(
				'name' =>'背景音乐',
				'action'=>array(
					'musicList'=>'音乐列表',
                    'addEditNav'=>'添加修改音乐',
				)
			),
              'admin/system'=>array(
                'name' =>'系统设置',
                'action'=>array(
                     'index'=>'网站设置',        
                     'navigationList'=>'导航设置',
                     'menu'=>'菜单管理',
                     'module'=>'模块管理',
         	       )
               ),
        
              'admin/goods'=>array(
                'name' =>'家居装修',
                'action'=>array(
                     'companyList'=>'商户列表',
					 'applyList'=>'报名列表',
					 'activity'=>'编辑专题',
                     'helpList'=>'帮我选公司名单',
                     'designList'=>'申请列表',
                     'addEditGoods'=>'添加修改商户',
                     'effectList'=>'效果图',
                     'addEditGoodsType'=>'编辑商品类型',
                     'checkList'=>'商户审核',
                     'minge'=>'设计名额',
                     'goodsList'=>'商品列表',
                     'addEditList'=>'添加修改商品',
                     'brandList'=>'商品品牌',
                     'addEditBrand'=>'添加修改品牌',                    
         	       )
               ), 
				 'admin/love'=>array(
                'name' =>'恋爱婚姻',
                'action'=>array(
                     'applyList'=>'报名列表',
         	       )
               ),
			'admin/module'=>array(
					'name' =>'版块轮播',
					'action'=>array(
							'addEditNav'=>'添加修改图片',
							'moduleList'=>'图片列表',
					)
			),
			    'admin/area'=>array(
                'name' =>'区域管理',
                'action'=>array(
					'addEditArea'=>'添加修改区域',
                     'areaList'=>'区域列表',
                     'cityList'=>'城市列表',
                     'addCity'=>'添加城市',
         	       )
               ),
			   	    'admin/travel'=>array(
                'name' =>'旅居服务',
                'action'=>array(
					'addEditTravel'=>'添加修改景点',
					'addEditCategory'=>'添加修改分类',
                     'travelList'=>'旅游列表',
                     'travelCategory'=>'旅游分类',
					 'scheduleList'=>'班期列表',
         	       )
               ),
			    'admin/talents'=>array(
                'name' =>'人才管理',
                'action'=>array(
					'addEditPosition'=>'添加修改职位',
                     'positionList'=>'职位列表',
					 'talList'=>'人才列表',
         	       )
               ),
              'admin/order'=>array(
                'name' =>'订单管理',
                'action'=>array(
                     'index'=>'订单列表',
                     'edit_order'=>'编辑订单',
                     'delivery_list'=>'发货单列表',
                     'delivery_info'=>'订单发货',
                     'add_order'=>'添加订单',
                     'split_order'=>'拆分订单',
                     'detail'=>'订单详情',
                     'return_list'=>'退货申请列表',
         	      )
               ),        
              'admin/user'=>array(
                'name' =>'会员管理',
                'action'=>array(
                     'index'=>'用户列表',
                     'address'=>'收货地址',
                     'account_log'=>'用户资金',
                     'levelList'=>'等级列表',
                     'level'=>'添加等级',
					'addUser'=>'添加会员'
         	      )
               ),        
              'admin/ad'=>array(
                'name' =>'广告管理',
                'action'=>array(
                     'adList'=>'广告列表',
                     'edit'=>'编辑广告',
                     'ad'=>'新增广告',
                     'adList'=>'广告列表',
                     'positionList'=>'广告位置',
                     'position'=>'编辑广告位',                     
         	      )
               ),        
              'admin/article'=>array(
                'name' =>'文章管理',
                'action'=>array(
                     'categorylist'=>'分类列表',                     
                     'category'=>'编辑分类',
                     'articlelist'=>'文章列表',
                     'article'=>'编辑文章', 
                     'linkList'=>'友情链接列表',
                     'link'=>'编辑友情链接',                                       
         	      )
               ),
        'admin/activity'=>array(
            'name' =>'专题管理',
            'action'=>array(
                'catlist'=>'专题分类',
                'category'=>'编辑分类',
                'activitylist'=>'专题列表',
                'activity'=>'编辑专题',
            )
        ),
        'admin/admin'=>array(
                'name' =>'权限管理',
                'action'=>array(
                     'index'=>'管理员列表',    
                     'admin_info'=>'编辑管理员',                        
                     'log'=>'管理员日志',
                     'role'=>'角色管理',         
                     'role_info'=>'创建编辑角色',                    
         	      )
               ),        
              'admin/comment'=>array(
                'name' =>'评论管理',
                'action'=>array(
                     'index'=>'评论列表',
                     'detail'=>'评论回复',
         	      )
               ),        
              'admin/template'=>array(
                'name' =>'模板管理',
                'action'=>array(
                     'templatelist'=>'模板选择',                     
         	      )
               ),                
              'admin/coupon'=>array(
                'name' =>'优惠券管理',
                'action'=>array(
                     'index'=>'优惠券列表',
                     'add_coupon'=>'添加优惠券',
                     'edit_coupon'=>'编辑优惠券',
         	      )
               ),
              'admin/wechat'=>array(
                'name' =>'微信管理',
                'action'=>array(
                     'index'=>'公众号管理',
                     'setting'=>'微信配置',
                     'menu'=>'微信菜单',
                     'text'=>'文本回复',
                     'add_text'=>'编辑文本回复',
                     'img'=>'图文回复',
                     'add_img'=>'编辑图文回复',                   
                     'news'=>'组合图文回复',   
                     'add_news'=>'编辑图文',
         	      )
               ),                
              'admin/plugin'=>array(
                'name' =>'插件管理',
                'action'=>array(
                     'index'=>'插件列表',
                     'setting'=>'插件配置',
         	      )
               ),
               'admin/topic'=>array(
               		'name' =>'专题管理',
               		'action'=>array(
               			'topicList'=>'专题列表',
               			'topic'=>'添加专题',
               		)
               ),
               'admin/promotion'=>array(
               		'name' =>'团购管理',
               		'action'=>array(
               			'group_buy_list'=>'团购列表',
               			'group_buy'=>'编辑团购',
               		)
               ),
               'admin/tools'=>array(
               		'name' =>'工具管理',
               		'action'=>array(
               			'index'=>'数据备份',
               			'restore'=>'数据还原',
               		)
               ),
               'admin/report'=>array(
               		'name' =>'报表统计',
               		'action'=>array(
               			'index'=>'销售概况',
               			'saleTop'=>'销售排行',
               			'userTop'=>'会员排行',
               			'saleList'=>'销售明细',
               			'user'=>'会员统计',
               			'finance'=>'财务统计',
               		)
               ),

    );
?>
