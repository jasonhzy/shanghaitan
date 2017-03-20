<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>上海滩管理后台</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.4 -->
    <link href="/Public/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- FontAwesome 4.3.0 -->
 	<link href="/Public/bootstrap/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <!-- Ionicons 2.0.0 --
    <link href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <link href="/Public/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
    <!-- AdminLTE Skins. Choose a skin from the css/skins 
    	folder instead of downloading all of them to reduce the load. -->
    <link href="/Public/dist/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css" />
    <!-- iCheck -->
    <link href="/Public/plugins/iCheck/flat/blue.css" rel="stylesheet" type="text/css" />   
    <!-- jQuery 2.1.4 -->
    <script src="/Public/plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <script src="/Public/js/global.js"></script>
    <script src="/Public/js/myFormValidate.js"></script>    
    <script src="/Public/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="/Public/js/layer/layer.js"></script><!-- 弹窗js 参考文档 http://layer.layui.com/-->
    <script src="/Public/js/myAjax.js"></script>        
  </head>
  <body style="background-color:#ecf0f5;">
 

<div class="wrapper">
    <div class="breadcrumbs" id="breadcrumbs">
	<ol class="breadcrumb">
	<?php if(is_array($navigate_admin)): foreach($navigate_admin as $k=>$v): if($k == '后台首页'): ?><li><a href="<?php echo ($v); ?>"><i class="fa fa-home"></i>&nbsp;&nbsp;<?php echo ($k); ?></a></li>
	    <?php else: ?>    
	        <li><a href="<?php echo ($v); ?>"><?php echo ($k); ?></a></li><?php endif; endforeach; endif; ?>          
	</ol>
</div>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <nav class="navbar navbar-default">
                            <div class="panel-heading">
                                <h3 class="panel-title"> 会员审核</h3>
                            </div>
                        </nav>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <table id="list-table" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
                                    <thead>
                                    <tr role="row">
                                        <th>公司名称</th>
                                        <th>营业执照</th>
                                        <th>法人代表</th>
                                        <th>电话号码</th>
                                        <th>所在地</th>
                                        <th>审核状态</th>
                                        <!--<th>相册</th>-->
                                        <!-- <th>操作</th> -->
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php if(is_array($goodsInfo)): $k = 0; $__LIST__ = $goodsInfo;if( count($__LIST__)==0 ) : echo "暂无未审核用户" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?><tr role="row">
                                            <td><?php echo ($vo["goods_name"]); ?></td>
                                            <td><?php if($vo["yingye"] == ''): ?>暂无<?php else: ?><img src="<?php echo ($vo["yingye"]); ?>" width="80px"><?php endif; ?></td>
                                            <td><?php echo ($vo["faren"]); ?></td>
                                            <td><?php echo ($vo["mobile"]); ?></td>
                                            <td><?php echo ($vo["city"]); ?></td>
                                            <td data-id="<?php echo ($vo["user_id"]); ?>">
                                                <!-- <img class="static" width="20" height="20" src="/Public/images/<?php if($vo[checked] == 1): ?>cancel.png<?php endif; ?>"/> -->
                                                <?php if($vo[is_check] == 0): ?><a href="javascript:void(0)" class="static">等待审核</a><?php endif; ?>
                                                <?php if($vo[is_check] == 1): ?><span  class="static">已审核</span><?php endif; ?>
                                            </td>
                                            <!--<td><a class="btn btn-info" href="<?php echo U('Admin/Cation/imagepath',array('user_id'=>$vo['user_id']));?>">查看相册</a></td>-->
                                            <!-- <td>
                                             <a class="btn btn-info" href="<?php echo U('Admin/Ad/adList',array('pid'=>$vo['position_id']));?>">查看广告</a>
                                             <a class="btn btn-primary" href="<?php echo U('Admin/Ad/position',array('act'=>'edit','position_id'=>$vo['position_id']));?>"><i class="fa fa-pencil"></i></a>
                                             <a class="btn btn-danger"><i class="fa fa-trash-o"></i></a>
                                           </td> -->
                                        </tr><?php endforeach; endif; else: echo "暂无未审核用户" ;endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6 text-left"></div>
                            <div class="col-sm-6 text-right"><?php echo ($page); ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
</body>
</html>
<script>
    $(".static").click(function(){
        var con = confirm("确定通过审核吗?");
        var  obj = $(this);
        var user_id = obj.parent("td").attr("data-id");
        if(con == true){
            $.get("<?php echo U('Admin/Goods/ajaxCheck');?>",{user_id:user_id},function(data){
                if(data == "1"){
                    location.reload();
                }
            })
        }
    })
</script>