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
                            <div class="collapse navbar-collapse">
                                <form class="navbar-form form-inline" action="<?php echo U('Admin/Love/applyList');?>" method="post">
                                    <div class="form-group">
                                        <input type="text" name="keywords" class="form-control" placeholder="筛选名字或手机号">
                                    </div>
                                    <button type="submit" class="btn btn-default">提交</button>
                                </form>
                            </div>
                        </nav>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <table id="list-table" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
                                    <thead>
                                    <tr role="row">
                                        <th class="sorting" tabindex="0" aria-controls="example1"  aria-label="Rendering engine: activate to sort column descending" >姓名</th>
                                        <th class="sorting" tabindex="0" aria-controls="example1"  aria-label="Platform(s): activate to sort column ascending">电话</th>
                                        <th class="sorting" tabindex="0" aria-controls="example1"  aria-label="Platform(s): activate to sort column ascending">性别</th>
                                        <th class="sorting" tabindex="0" aria-controls="example1"  aria-label="Platform(s): activate to sort column ascending">年龄区间</th>
                                        <th class="sorting" tabindex="0" aria-controls="example1"  aria-label="Engine version: activate to sort column ascending">报名时间</th>
                                        <th class="sorting" tabindex="0" aria-controls="example1"  aria-label="CSS grade: activate to sort column ascending">操作</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php if(is_array($list)): $k = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "没有符合要求的数据" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?><tr role="row" align="center">
                                            <td><?php echo ($vo["name"]); ?></td>
                                            <td><?php echo ($vo["mobile"]); ?></td>
                                            <td><?php if($vo["sex"] == 1): ?>男<?php elseif($vo["sex"] == 2): ?>女<?php else: ?>保密<?php endif; ?></td>
                                            <td><?php echo ($vo["ages"]); ?></td>
                                            <td><?php echo (date("Y-m-d H:i:s",$vo["time"])); ?></td>
                                            <td>
                                                <a class="btn btn-danger" href="javascript:void(0)" data-url="<?php echo U('Love/delApply');?>" data-id="<?php echo ($vo["id"]); ?>" onclick="delfun(this)"><i class="fa fa-trash-o"></i></a>
                                            </td>
                                        </tr><?php endforeach; endif; else: echo "没有符合要求的数据" ;endif; ?>
                                    </tbody>
                                    <tfoot>

                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6 text-left"></div>
                            <div class="col-sm-6 text-right"><?php echo ($page); ?></div>
                        </div>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div>
        </div>
    </section>
</div>
<script>
    function delfun(obj){
        if(confirm('确认删除')){
            $.ajax({
                type : 'post',
                url : $(obj).attr('data-url'),
                data : {act:'del',id:$(obj).attr('data-id')},
                dataType : 'json',
                success : function(data){
                    if(data){
                        $(obj).parent().parent().remove();
                    }else{
                        layer.alert('删除失败', {icon: 2});  //alert('删除失败');
                    }
                }
            })
        }
        return false;
    }
</script>
</body>
</html>