<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="Author" contect="http://www.6665.com">
    <title><?php echo ($tpshop_config['shop_info_store_name']); ?></title>

</head>

<body id="content">
<link rel="shortcut icon" href="/Template/pc/soubao/Static/images/favicon.ico" />
<link type="text/css" href="/Template/pc/soubao/Static/css/reset.css" rel="stylesheet" />
<link type="text/css" href="/Template/pc/soubao/Static/css/iconfont.css" rel="stylesheet" />
<link type="text/css" href="/Template/pc/soubao/Static/css/style.css" rel="stylesheet" />
<!--[if lt IE 9]>
<script>
  (function() {     if (!
                  /*@cc_on!@*/
                  0) return;     var e = "abbr, article, aside, audio, canvas, datalist, details, dialog, eventsource, figure, footer, header, hgroup, mark, menu, meter, nav, output, progress, section, time, video".split(', ');     var i= e.length;     while (i--){
    document.createElement(e[i])
  }
  })()
</script>
<![endif]-->
<div class="header">
  <div class="web-width">
    <div class="city fl">
      <strong class="cityname">上海</strong> <a href="javascript:;" class="tab-city">[切换城市]</a>
      <div class="citylist">
        <div class="citytitle">可选城市列表</div>
        <?php
 $md5_key = md5("SELECT * FROM `__PREFIX__area`"); $sql_result_v = S("sql_".$md5_key); if(empty($sql_result_v)) { $Model = new \Think\Model(); $result_name = $sql_result_v = $Model->query("SELECT * FROM `__PREFIX__area`"); S("sql_".$md5_key,$sql_result_v,TPSHOP_CACHE_TIME); } foreach($sql_result_v as $k=>$v): ?><dl>
          <dt><?php echo ($v['name']); ?></dt>
          <dd>
            <?php
 $md5_key = md5("SELECT * FROM `__PREFIX__city` where parent_id = $v[id]"); $sql_result_v0 = S("sql_".$md5_key); if(empty($sql_result_v0)) { $Model = new \Think\Model(); $result_name = $sql_result_v0 = $Model->query("SELECT * FROM `__PREFIX__city` where parent_id = $v[id]"); S("sql_".$md5_key,$sql_result_v0,TPSHOP_CACHE_TIME); } foreach($sql_result_v0 as $k=>$v0): ?><a class="chengshi" data="<?php echo ($v0["id"]); ?>"><?php echo ($v0['name']); ?></a><?php endforeach; ?>
          </dd>
          <div class="clears"></div>
        </dl><?php endforeach; ?>
      </div>
    </div><!--city/-->
    <div class="head-lab fr">
      <a href="<?php echo U('Home/User/login');?>">登录</a><span>|</span>
      <a href="<?php echo U('Home/User/reg');?>">注册</a><span>|</span>
      <a href="<?php echo U('Home/User/index');?>">vip中心</a>
      <a href="javascript:;">帮助</a>
      <div class="head-xiala xuanzhuan">
        <i class="iconfont icon-liebiao">&#xe610;</i>
        <span>
          <?php
 $md5_key = md5("SELECT * FROM `__PREFIX__article_cat` where parent_id = 0"); $sql_result_v = S("sql_".$md5_key); if(empty($sql_result_v)) { $Model = new \Think\Model(); $result_name = $sql_result_v = $Model->query("SELECT * FROM `__PREFIX__article_cat` where parent_id = 0"); S("sql_".$md5_key,$sql_result_v,TPSHOP_CACHE_TIME); } foreach($sql_result_v as $k=>$v): ?><a href="javascript:;"><?php echo ($v["cat_name"]); ?></a><?php endforeach; ?>
     </span>
      </div>
    </div><!--head-lab/-->
  </div><!--web-width/-->
</div><!--header/-->
<div class="logo-searcg-phone">
  <div class="web-width">
    <h1 class="logo fl"><a href="index.html"><img src="<?php echo ($tpshop_config['shop_info_store_logo']); ?>" alt="上海滩网" /></a></h1>
    <div class="search fl">
      <div class="search-top">
        <div class="search-nav xuanzhuan fl">
          <h2><strong id="seatext">资讯</strong> <i class="iconfont">&#xe61a;</i></h2>
          <ul>
            <li>相亲</li>
            <li>家博会</li>
            <li>旅游</li>
            <li>美食</li>
            <li>亲子</li>
          </ul>
        </div>
        <div class="search-input fl"><input type="text" placeholder="品牌特卖，正品低价" /></div>
        <div class="searac-sub fr"><a href="javascript:;"><i class="iconfont">&#xe605;</i></a></div>
      </div><!--search-top/-->
      <div class="search-down">
        <a href="javascript:;" class="bg1">家博会</a>
        <a href="javascript:;" class="bg2">相亲大会</a>
        <a href="javascript:;" class="bg3">装修报价</a>
        <a href="javascript:;" class="bg4">买建材</a>
        <a href="javascript:;" class="bg5">大健康</a>
      </div><!--search-down/-->
    </div><!--search/-->
    <div class="phones">
      <span>|</span>
      <a href="javascript:;">售后服务</a><span>|</span>
      <div class="phone-tel">
        售后服务:<em>400-960-9991 <i class="iconfont icon-sanjiao">&#xe600;</i></em>
        <span>400-960-9991</span>
      </div>
      <a href="http://www.6665.com/plugin.php?id=appbyme_app:download" target="_blank" class="icon-tel"><i class="iconfont">&#xe737;</i></a>
      <a href="javascript:;" class="icon-wx">
        <i class="iconfont">&#xe603;</i>
        <strong><img src="/Template/pc/soubao/Static/images/weixin.jpg" /><span>打开微信扫一扫</span></strong>
      </a>
    </div><!--phones/-->
  </div><!--web-width/-->
</div><!--logo-searcg-phone/-->
<div class="nav">
  <div class="web-width">
    <ul>
      <li class="navcur"><a href="javascript:;">首页</a></li>
      <?php if(is_array($nav)): foreach($nav as $key=>$v): ?><li><a href="javascript:;"><?php echo ($v["name"]); ?> <i class="iconfont">&#xe61a;</i></a>
        <div class="chilNav">
          <?php
 $md5_key = md5("SELECT * FROM `__PREFIX__navigation` where parent_id = $v[id]"); $sql_result_v0 = S("sql_".$md5_key); if(empty($sql_result_v0)) { $Model = new \Think\Model(); $result_name = $sql_result_v0 = $Model->query("SELECT * FROM `__PREFIX__navigation` where parent_id = $v[id]"); S("sql_".$md5_key,$sql_result_v0,TPSHOP_CACHE_TIME); } foreach($sql_result_v0 as $k=>$v0): ?><a href="javascript:;"><?php echo ($v0["name"]); ?></a><?php endforeach; ?>
        </div>
      </li><?php endforeach; endif; ?>
    </ul>
  </div><!--web-width/-->
</div><!--nav/-->

<div id="ban">
    <ul id="slides">
        <?php if(is_array($lunbo)): foreach($lunbo as $key=>$v): ?><li style='background:url("<?php echo ($v[p_url]); ?>") no-repeat center top;'><a href="javascript:;"></a></li><?php endforeach; endif; ?>
    </ul>
</div>
<!--定位导航-->
<div id="menu">
    <ul>
        <?php
 $md5_key = md5("SELECT * FROM `__PREFIX__activity_cat` where parent_id = 0"); $sql_result_v = S("sql_".$md5_key); if(empty($sql_result_v)) { $Model = new \Think\Model(); $result_name = $sql_result_v = $Model->query("SELECT * FROM `__PREFIX__activity_cat` where parent_id = 0"); S("sql_".$md5_key,$sql_result_v,TPSHOP_CACHE_TIME); } foreach($sql_result_v as $k=>$v): ?><li><a <?php if($v["cat_id"] == 1): ?>href="#item1"<?php elseif($v["cat_id"] == 2): ?>href="#item2"<?php else: ?>href="#item3"<?php endif; ?>><em></em><?php echo ($v["cat_name"]); ?></a></li><?php endforeach; ?>
      <!--  <ul>
            <li><a href="#item1" class="current"><em></em>热门活动</a></li>
            <li><a href="#item2"><em></em>家居装修</a></li>
            <li><a href="#item3"><em></em>恋爱婚姻</a></li>
        </ul>-->
    </ul>
</div>
<section id="item1" class="item">
    <div class="web-width">
        <div class="active-top">
            <?php
 $md5_key = md5("SELECT * FROM `__PREFIX__activity_cat` where parent_id = 0 limit 1"); $sql_result_v = S("sql_".$md5_key); if(empty($sql_result_v)) { $Model = new \Think\Model(); $result_name = $sql_result_v = $Model->query("SELECT * FROM `__PREFIX__activity_cat` where parent_id = 0 limit 1"); S("sql_".$md5_key,$sql_result_v,TPSHOP_CACHE_TIME); } foreach($sql_result_v as $k=>$v): ?><h3><?php echo ($v["cat_name"]); ?></h3><?php endforeach; ?>
            <ul class="tab">
                <?php if(is_array($ad)): foreach($ad as $key=>$v): ?><li><?php echo ($v["ad_name"]); ?> <i class="iconfont posj">&#xe608;</i></li><?php endforeach; endif; ?>
            </ul>
            <a href="<?php echo U('Home/Goods/zhuanti/cid/70');?>" class="mores">更多活动&gt;</a>
        </div><!--active-top/-->
        <div class="active-down">
            <div class="active-down-left-jbh fl">
                <div class="active-down-left-img" style=" background:url(/Template/pc/soubao/Static/images/img1-1.jpg) center top no-repeat;">
                    <div class="adl-text">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;上海滩家博会是一个引领产消、直面终端的家居、家装、建材一站式采购平台。<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;汇聚家庭装修采购所需要的家装、家具、橱柜、全屋定制等品质产品！</div>
                    <div class="activesub"><a href="javascript:;" target="_blank">转盘100%赢好礼 <em><i class="iconfont">&#xe604;</i></em></a></div>
                    <div class="activesub"><a href="javascript:;" target="_blank">抢1400个免单优惠 <em><i class="iconfont">&#xe604;</i></em></a></div>
                    <div class="suopiao"><a href="javascript:;"><img src="/Template/pc/soubao/Static/images/suopiao.png" width="207" height="53" /></a></div>
                </div>
            </div><!--active-down-left-jbh/-->
            <div class="active-down-right-jbh fl">
                <?php if(is_array($ad)): foreach($ad as $key=>$v): ?><div class="tablist">
                    <div class="active-down-right-img"><a href="<?php echo ($v["ad_link"]); ?>" target="_blank"><img src="<?php echo ($v["ad_code"]); ?>" width="943" /></a></div>
                </div><!--tablist/--><?php endforeach; endif; ?>
            </div><!--active-down-left-jbh/-->
            <div class="clears"></div>
        </div><!--active-down/-->
    </div><!--web-width/-->
</section>
<section id="item2" class="item">
    <div class="web-width">
        <div class="active-top bor">
            <?php
 $md5_key = md5("SELECT * FROM `__PREFIX__activity_cat` where parent_id = 0 limit 1,1"); $sql_result_v = S("sql_".$md5_key); if(empty($sql_result_v)) { $Model = new \Think\Model(); $result_name = $sql_result_v = $Model->query("SELECT * FROM `__PREFIX__activity_cat` where parent_id = 0 limit 1,1"); S("sql_".$md5_key,$sql_result_v,TPSHOP_CACHE_TIME); } foreach($sql_result_v as $k=>$v): ?><h3><a href="<?php echo U('Home/Goods/index');?>"><?php echo ($v["cat_name"]); ?></a></h3><?php endforeach; ?>
            <ul class="tab tab2">
                <?php if(is_array($cat2)): foreach($cat2 as $key=>$v): ?><li><?php if($v[cat_name] == '手机专享'): ?><i class="iconfont tab-phone">&#xe6ce;</i><?php endif; echo ($v["cat_name"]); ?><img src="/Template/pc/soubao/Static/images/sanjiao.png" class="posj2"/></li><?php endforeach; endif; ?>
            </ul>
            <a href="javascript:;" class="mores">更多详情&gt;</a>
        </div><!--active-top/-->
        <div class="active-down">
            <div class="active-down-left fl" style="height:665px;">
                <div class="active-list">
                    <?php if(is_array($cat3)): foreach($cat3 as $key=>$v): ?><h3><a href="<?php echo ($v["url"]); ?>"><span><?php echo ($v["cat_name"]); ?></span></a></h3>
                          <?php if($v[cat_name] == '装修攻略'): $md5_key = md5("SELECT * FROM `__PREFIX__activity_cat` where parent_id = $v[cat_id]"); $sql_result_v0 = S("sql_".$md5_key); if(empty($sql_result_v0)) { $Model = new \Think\Model(); $result_name = $sql_result_v0 = $Model->query("SELECT * FROM `__PREFIX__activity_cat` where parent_id = $v[cat_id]"); S("sql_".$md5_key,$sql_result_v0,TPSHOP_CACHE_TIME); } foreach($sql_result_v0 as $k=>$v0): ?><div class="active-lab1"><span><?php echo ($v0["cat_name"]); ?></span></div>
                            <ul>
                                <?php
 $md5_key = md5("SELECT * FROM `__PREFIX__activity_cat` where parent_id = $v0[cat_id]"); $sql_result_v1 = S("sql_".$md5_key); if(empty($sql_result_v1)) { $Model = new \Think\Model(); $result_name = $sql_result_v1 = $Model->query("SELECT * FROM `__PREFIX__activity_cat` where parent_id = $v0[cat_id]"); S("sql_".$md5_key,$sql_result_v1,TPSHOP_CACHE_TIME); } foreach($sql_result_v1 as $k=>$v1): ?><li <?php if($k%2 == 0): ?>style="display:inline;"<?php endif; ?>>  <?php if($k%2 == 0): ?><a href="<?php echo ($v1["url"]); ?>" class="fl"><?php echo ($v1["cat_name"]); ?></a><?php else: ?><a href="javascript:;" class="fr"><?php echo ($v1["cat_name"]); ?></a><?php endif; ?></li><?php endforeach; ?>
                            </ul><?php endforeach; ?>
                          <?php else: ?>
                              <ul>
                                  <?php
 $md5_key = md5("SELECT * FROM `__PREFIX__activity_cat` where parent_id = $v[cat_id]"); $sql_result_v1 = S("sql_".$md5_key); if(empty($sql_result_v1)) { $Model = new \Think\Model(); $result_name = $sql_result_v1 = $Model->query("SELECT * FROM `__PREFIX__activity_cat` where parent_id = $v[cat_id]"); S("sql_".$md5_key,$sql_result_v1,TPSHOP_CACHE_TIME); } foreach($sql_result_v1 as $k=>$v1): ?><li <?php if($k%2 == 0): ?>style="display:inline;"<?php endif; ?>>  <?php if($k%2 == 0): ?><a href="<?php echo ($v1["url"]); ?>" class="fl"><?php echo ($v1["cat_name"]); ?></a><?php else: ?><a href="javascript:;" class="fr"><?php echo ($v1["cat_name"]); ?></a><?php endif; ?></li><?php endforeach; ?>
                              </ul><?php endif; endforeach; endif; ?>
                </div>
            </div>
            <div class="active-down-right fl" style="height:650px;">
                <?php if(is_array($cat2)): foreach($cat2 as $key=>$v): ?><div class="tablist">
                    <div class="tablist-left fl">
                        <?php
 $md5_key = md5("SELECT * FROM `__PREFIX__activity` where cat_id = $v[cat_id] and is_open = 1 limit 1"); $sql_result_v0 = S("sql_".$md5_key); if(empty($sql_result_v0)) { $Model = new \Think\Model(); $result_name = $sql_result_v0 = $Model->query("SELECT * FROM `__PREFIX__activity` where cat_id = $v[cat_id] and is_open = 1 limit 1"); S("sql_".$md5_key,$sql_result_v0,TPSHOP_CACHE_TIME); } foreach($sql_result_v0 as $k=>$v0): ?><dl class="box1">
                            <dt><a href="javascript:;"><img src="<?php echo ($v0["thumb"]); ?>" alt="图片" /></a></dt>
                            <dd class="boxtitle1"><span><?php echo ($v0["title"]); ?></span><em>|</em><a href="javascript:;"><?php echo ($v0["description"]); ?></a> </dd>
                        </dl><?php endforeach; ?>
                        <?php
 $md5_key = md5("SELECT * FROM `__PREFIX__activity` where cat_id = $v[cat_id] and is_open = 1 limit 1,1"); $sql_result_v0 = S("sql_".$md5_key); if(empty($sql_result_v0)) { $Model = new \Think\Model(); $result_name = $sql_result_v0 = $Model->query("SELECT * FROM `__PREFIX__activity` where cat_id = $v[cat_id] and is_open = 1 limit 1,1"); S("sql_".$md5_key,$sql_result_v0,TPSHOP_CACHE_TIME); } foreach($sql_result_v0 as $k=>$v0): ?><dl class="box2">
                            <dt><a href="javascript:;"><img src="<?php echo ($v0["thumb"]); ?>" alt="图片" /></a></dt>
                            <dd class="boxtitle1"><span><?php echo ($v0["title"]); ?> </span><em>|</em><a href="javascript:;"><?php echo ($v0["description"]); ?></a></dd>
                        </dl><?php endforeach; ?>
                        <?php
 $md5_key = md5("SELECT * FROM `__PREFIX__activity` where cat_id = $v[cat_id] and is_open = 1 limit 2,3"); $sql_result_v0 = S("sql_".$md5_key); if(empty($sql_result_v0)) { $Model = new \Think\Model(); $result_name = $sql_result_v0 = $Model->query("SELECT * FROM `__PREFIX__activity` where cat_id = $v[cat_id] and is_open = 1 limit 2,3"); S("sql_".$md5_key,$sql_result_v0,TPSHOP_CACHE_TIME); } foreach($sql_result_v0 as $k=>$v0): ?><dl class="box2">
                            <dt><a href="javascript:;"><img src="<?php echo ($v0["thumb"]); ?>" alt="图片" /></a></dt>
                            <dd class="boxtitle2"><a href="javascript:;"><?php echo ($v0["title"]); ?></a></dd>
                            <div class="boxtext"><?php echo ($v0["description"]); ?></div>
                            <div class="boxmore"><a href="javascript:;">了解更多&gt;&gt;</a></div>
                        </dl><?php endforeach; ?>
                        <div class="clears"></div>
                    </div><!--tablist-left/-->
                    <div class="tablist-right fr">
                        <?php
 $md5_key = md5("SELECT * FROM `__PREFIX__activity` where cat_id = $v[cat_id] and is_open = 1 limit 5,1"); $sql_result_v0 = S("sql_".$md5_key); if(empty($sql_result_v0)) { $Model = new \Think\Model(); $result_name = $sql_result_v0 = $Model->query("SELECT * FROM `__PREFIX__activity` where cat_id = $v[cat_id] and is_open = 1 limit 5,1"); S("sql_".$md5_key,$sql_result_v0,TPSHOP_CACHE_TIME); } foreach($sql_result_v0 as $k=>$v0): ?><div class="tablist-rightimg"><a href="javascript:;"><img src="<?php echo ($v0["thumb"]); ?>" alt="图片" /></a></div><?php endforeach; ?>
                    </div><!--tablist-right-->
                    <div class="clears"></div>
                </div><!--tablist/--><?php endforeach; endif; ?>
            </div><!--active-down-left/-->
            <div class="clears"></div>
        </div><!--active-down/-->
    </div><!--web-width/-->
</section>

<section id="item3" class="item">
    <div class="web-width">
        <div class="active-top bor">
            <?php
 $md5_key = md5("SELECT * FROM `__PREFIX__activity_cat` where parent_id = 0 limit 2,1"); $sql_result_v = S("sql_".$md5_key); if(empty($sql_result_v)) { $Model = new \Think\Model(); $result_name = $sql_result_v = $Model->query("SELECT * FROM `__PREFIX__activity_cat` where parent_id = 0 limit 2,1"); S("sql_".$md5_key,$sql_result_v,TPSHOP_CACHE_TIME); } foreach($sql_result_v as $k=>$v): ?><h3><a href=""><?php echo ($v["cat_name"]); ?></a></h3><?php endforeach; ?>
            <ul class="tab tab2 tab3">
                <?php if(is_array($cat4)): foreach($cat4 as $key=>$v): ?><li><?php if($v[cat_name] == '手机专享'): ?><i class="iconfont tab-phone">&#xe6ce;</i><?php endif; echo ($v["cat_name"]); ?><img src="/Template/pc/soubao/Static/images/sanjiao.png" class="posj2"/></li><?php endforeach; endif; ?>
            </ul>
            <a href="javascript:;" class="mores">更多详情&gt;</a>
        </div><!--active-top/-->
        <div class="active-down">
            <div class="active-down-left fl" style="height:455px">
                <div class="active-list">
                    <?php if(is_array($cat5)): foreach($cat5 as $key=>$v): ?><h3><span><?php echo ($v["cat_name"]); ?></span></h3>
                    <ul>
                        <?php
 $md5_key = md5("SELECT * FROM `__PREFIX__activity_cat` where parent_id = $v[cat_id]"); $sql_result_v2 = S("sql_".$md5_key); if(empty($sql_result_v2)) { $Model = new \Think\Model(); $result_name = $sql_result_v2 = $Model->query("SELECT * FROM `__PREFIX__activity_cat` where parent_id = $v[cat_id]"); S("sql_".$md5_key,$sql_result_v2,TPSHOP_CACHE_TIME); } foreach($sql_result_v2 as $k=>$v2): ?><li <?php if(($k%2 == 0) ): ?>style="display:inline;"<?php endif; ?> ><?php if($k%2 == 0): ?><a href="javascript:;" class="fl"><?php echo ($v2["cat_name"]); ?></a><?php else: ?><a href="<?php echo ($v2["url"]); ?>" class="fr"><?php echo ($v2["cat_name"]); ?></a><?php endif; ?></li><?php endforeach; ?>
                    </ul><?php endforeach; endif; ?>
                </div><!--active-list/-->
            </div><!--active-down-left/-->
            <div class="active-down-right-qingyuan fl" style="height:455px">
                <div class="tablist">
                    <div class="qingyuanlist">
                    <?php if(is_array($users)): foreach($users as $key=>$v): ?><dl>
                            <dt><a href="javascript:;"><img src="<?php echo ($v["head_pic"]); ?>" /></a></dt>
                            <dd>
                                <h3><?php echo ($v["name"]); ?></h3>
                                <div class="qingtext"><?php echo ($v["age"]); ?>岁，<?php echo ($v["city"]); ?>, <?php echo ($v["height"]); ?>CM</div>
                                <div class="dubai"><strong>内心独白：</strong><?php echo ($v["dubai"]); ?></div>
                                <a href="javascript:;" class="qinglink"><i class="iconfont">&#xe62c;</i></a>
                            </dd>
                        </dl><?php endforeach; endif; ?>
                        <div class="clears"></div>
                    </div>
                </div><!--tablist/-->
                <div class="tablist">
                    <div class="qingyuanlist">
                        <?php if(is_array($users)): foreach($users as $key=>$v): ?><dl>
                                <dt><a href="javascript:;"><img src="<?php echo ($v["head_pic"]); ?>" /></a></dt>
                                <dd>
                                    <h3><?php echo ($v["name"]); ?></h3>
                                    <div class="qingtext"><?php echo ($v["age"]); ?>岁，<?php echo ($v["city"]); ?>, <?php echo ($v["height"]); ?>CM</div>
                                    <div class="dubai"><strong>内心独白：</strong><?php echo ($v["dubai"]); ?></div>
                                    <a href="javascript:;" class="qinglink"><i class="iconfont">&#xe62c;</i></a>
                                </dd>
                            </dl><?php endforeach; endif; ?>
                        <div class="clears"></div>
                    </div>
                </div><!--tablist/-->
            </div><!--active-down-right/-->
            <div class="clears"></div>
        </div><!--active-down/-->
    </div><!--web-width/-->
</section>
<div class="footer">
    <div class="web-width">
        <?php
 $md5_key = md5("select * from `__PREFIX__article_cat` where parent_id = 0 and show_in_nav=1"); $sql_result_v = S("sql_".$md5_key); if(empty($sql_result_v)) { $Model = new \Think\Model(); $result_name = $sql_result_v = $Model->query("select * from `__PREFIX__article_cat` where parent_id = 0 and show_in_nav=1"); S("sql_".$md5_key,$sql_result_v,TPSHOP_CACHE_TIME); } foreach($sql_result_v as $k=>$v): ?><ul>
            <li class="ftNav-title"><?php echo ($v["cat_name"]); ?></li>
            <?php
 $md5_key = md5("select * from `__PREFIX__article` where cat_id = $v[cat_id]"); $sql_result_v0 = S("sql_".$md5_key); if(empty($sql_result_v0)) { $Model = new \Think\Model(); $result_name = $sql_result_v0 = $Model->query("select * from `__PREFIX__article` where cat_id = $v[cat_id]"); S("sql_".$md5_key,$sql_result_v0,TPSHOP_CACHE_TIME); } foreach($sql_result_v0 as $k=>$v0): ?><li><a href="javascript:;"><?php echo ($v0["title"]); ?></a></li><?php endforeach; ?>
        </ul><?php endforeach; ?>
        <div class="ftcontent">
            <h3>全国服务热线</h3>
            <div class="ft-tel"><?php echo ($tpshop_config['shop_info_phone']); ?></div>
            <div class="ft-kf"><a href="tencent://message/?uin=10624700&Site"><i class="iconfont">&#xe637;</i> <span>在线客服</span></a></div>
        </div><!--ftcontent/-->
        <div class="clears"></div>
    </div><!--web-width/-->
</div><!--footer/-->
<div class="copy">
    <p>CopyRight &copy; <?php echo ($tpshop_config['shop_info_record_no']); ?> </p>
    <p>本网站常年法律顾问： 北京盈科（上海）律师事务所 张景三 律师</p>
    <p>企联网提供带宽支持</p>
</div>
<div class="safe">
    <?php
 $md5_key = md5("select * from `__PREFIX__friend_link` where link_logo != ''"); $sql_result_v = S("sql_".$md5_key); if(empty($sql_result_v)) { $Model = new \Think\Model(); $result_name = $sql_result_v = $Model->query("select * from `__PREFIX__friend_link` where link_logo != ''"); S("sql_".$md5_key,$sql_result_v,TPSHOP_CACHE_TIME); } foreach($sql_result_v as $k=>$v): ?><a href="javascript:;"><img class="lazy" data-original="<?php echo ($v["link_logo"]); ?>" src="" alt="<?php echo ($v["link_name"]); ?>" /></a><?php endforeach; ?>
</div>
<div class="links">
    <div class="web-width">
        <?php
 $md5_key = md5("select * from `__PREFIX__friend_link` where link_logo = ''"); $sql_result_v = S("sql_".$md5_key); if(empty($sql_result_v)) { $Model = new \Think\Model(); $result_name = $sql_result_v = $Model->query("select * from `__PREFIX__friend_link` where link_logo = ''"); S("sql_".$md5_key,$sql_result_v,TPSHOP_CACHE_TIME); } foreach($sql_result_v as $k=>$v): ?>友情链接：<a href="javascript:;"><?php echo ($v["link_name"]); ?></a><?php endforeach; ?>
    </div><!--web-width/-->
</div><!--links/-->
<script type="text/javascript" src="/Template/pc/soubao/Static/js/jquery.js"></script>
<script type="text/javascript" src="/Template/pc/soubao/Static/js/style.js"></script>
<script type="text/javascript" src="/Template/pc/soubao/Static/js/jquery.jslides.js"></script><!--焦点轮换-->
<!-- 定位导航 -->
<script type="text/javascript" src="/Template/pc/soubao/Static/js/stickUp.js"></script>
<!--图片预加载-->
<script src="/Template/pc/soubao/Static/js/jquery.lazyload.js"></script>
<script type="text/javascript" charset="utf-8">
    $(function() {
        $("img.lazy").lazyload({effect: "fadeIn"});
        $(".tab li").eq(0).addClass('labcur');
        $(".tab2 li").eq(0).addClass('labcur');
        $(".tab3 li").eq(0).addClass('labcur');
    });
</script>
<script>
    $(function(){
        $('.chengshi').click(function(){
            var cityid = $(this).attr('data');
            alert(cityid);
        })
    });
</script>
</body>
</html>