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
 

<!--公共js 代码 --><script src="/Public/js/common.js" charset="utf-8" type="text/javascript"></script><!--公共js end代码 -->
<!--以下是在线编辑器 代码 -->
<script type="text/javascript">
    window.UEDITOR_Admin_URL = "/Public/plugins/Ueditor/";
    var URL_upload = "<?php echo ($URL_upload); ?>";
    var URL_fileUp = "<?php echo ($URL_fileUp); ?>";
    var URL_scrawlUp = "<?php echo ($URL_scrawlUp); ?>";
    var URL_getRemoteImage = "<?php echo ($URL_getRemoteImage); ?>";
    var URL_imageManager = "<?php echo ($URL_imageManager); ?>";
    var URL_imageUp = "<?php echo ($URL_imageUp); ?>";
    var URL_getMovie = "<?php echo ($URL_getMovie); ?>";
    var URL_home = "<?php echo ($URL_home); ?>";
</script>
<script type="text/javascript" src="/Public/plugins/Ueditor/ueditor.config.js"></script>
<script type="text/javascript" src="/Public/plugins/Ueditor/ueditor.all.js"></script>
<script type="text/javascript" charset="utf-8" src="/Public/plugins/Ueditor/lang/zh-cn/zh-cn.js"></script>
<link href="/Public/plugins/daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css" />
<script src="/Public/plugins/daterangepicker/moment.min.js" type="text/javascript"></script>
<script src="/Public/plugins/daterangepicker/daterangepicker.js" type="text/javascript"></script>
<style>
    .edui-popup-content{
         height:auto !important;
     }
</style>
<div class="wrapper">
    <div class="breadcrumbs" id="breadcrumbs">
	<ol class="breadcrumb">
	<?php if(is_array($navigate_admin)): foreach($navigate_admin as $k=>$v): if($k == '后台首页'): ?><li><a href="<?php echo ($v); ?>"><i class="fa fa-home"></i>&nbsp;&nbsp;<?php echo ($k); ?></a></li>
	    <?php else: ?>    
	        <li><a href="<?php echo ($v); ?>"><?php echo ($k); ?></a></li><?php endif; endforeach; endif; ?>          
	</ol>
</div>

    <section class="content">
        <!-- Main content -->
        <div class="container-fluid">
            <div class="pull-right">

                <a href="javascript:history.go(-1)" data-toggle="tooltip" title="" class="btn btn-default" data-original-title="返回"><i class="fa fa-reply"></i></a>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-list"></i>商品详情</h3>
                </div>
                <div class="panel-body">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab_tongyong" data-toggle="tab">通用信息</a></li>
<!--                        <li><a href="#tab_goods_desc" data-toggle="tab">描述信息</a></li>-->
                        <li><a href="#tab_goods_images" data-toggle="tab">商户相册</a></li>
                    </ul>
                    <!--表单数据-->
                    <form method="post" id="addEditGoodsForm">
                    
                        <!--通用信息-->
                    <div class="tab-content">                 	  
                        <div class="tab-pane active" id="tab_tongyong">
                           
                            <table class="table table-bordered">
                                <tbody>
                                <tr>
                                    <td>商户名称:</td>
                                    <td>
                                        <input type="text" value="<?php echo ($goodsInfo["goods_name"]); ?>" name="goods_name" class="form-control" style="width:350px;"/>
                                        <span id="err_goods_name" style="color:#F00; display:none;"></span>                                        
                                    </td>
                                </tr>
                                <tr>
                                    <td>商户简介:</td>
                                    <td>
	                                    <textarea rows="3" cols="50" name="goods_remark"><?php echo ($goodsInfo["goods_remark"]); ?></textarea>
                                        <span id="err_goods_remark" style="color:#F00; display:none;"></span>
                                         
                                    </td>                                                                       
                                </tr>
                                <tr>
                                    <td>所在地</td>
                                    <td>                                                                               
                                        <input type="text" value="<?php echo ($goodsInfo["city"]); ?>" name="city" class="input-sm"/>
                                        <span id="err_goods_sn" style="color:#F00; display:none;"></span>
                                    </td>
                                </tr>

                                <tr>
                                    <td>咨询数:</td>
                                    <td>
                                        <input type="text" value="<?php echo ($goodsInfo["sum"]); ?>" name="sum" class="input-sm" onkeyup="this.value=this.value.replace(/[^\d.]/g,'')" onpaste="this.value=this.value.replace(/[^\d.]/g,'')" />
                                        <span id="err_shop_price" style="color:#F00; display:none;"></span>					                                        
                                    </td>
                                </tr>  
                                <tr>
                                    <td>口碑值:</td>
                                    <td>
                                        <input type="text" value="<?php echo ($goodsInfo["koubei"]); ?>" name="koubei" class="input-sm" onkeyup="this.value=this.value.replace(/[^\d.]/g,'')" onpaste="this.value=this.value.replace(/[^\d.]/g,'')" />
                                        <span id="err_market_price" style="color:#F00; display:none;"></span>					                                        
                                    </td>
                                </tr>

                                <tr>
                                    <td>上传商户主题图片:</td>
                                    <td>
                                        <input type="button" value="上传图片"  onclick="GetUploadify(1,'','goods','call_back');"/>
                                        <input type="text" class="input-sm"  name="original_img" id="original_img" value="<?php echo ($goodsInfo["original_img"]); ?>"/>
                                        <?php if($goodsInfo['original_img'] != null): ?>&nbsp;&nbsp;
                                            <a target="_blank" href="<?php echo ($goodsInfo["original_img"]); ?>" id="original_img2">
                                                <img width="25" height="25" src="<?php echo ($goodsInfo["original_img"]); ?>">
                                            </a><?php endif; ?>
                                        <span id="err_original_img" style="color:#F00; display:none;"></span>					                                        
                                    </td>
                                </tr>


                           <!--     <tr>
                                    <td>设置:</td>
                                    <td>
                                    	<input type="checkbox" checked="checked" value="<?php echo ($goodsInfo["is_on_sale"]); ?>" name="is_on_sale"/> 上架&nbsp;&nbsp;
	                                &lt;!&ndash;<input type="checkbox" checked="checked" value="<?php echo ($goodsInfo["is_free_shipping"]); ?>" name="is_free_shipping"/> 包邮&nbsp;&nbsp;&ndash;&gt;
                                        <input type="checkbox" checked="checked" value="<?php echo ($goodsInfo["is_recommend"]); ?>" name="is_recommend"/>推荐&nbsp;&nbsp;
                                        <input type="checkbox" checked="checked" value="<?php echo ($goodsInfo["is_new"]); ?>" name="is_new"/>新品&nbsp;&nbsp;
                                    </td>
                                </tr> -->
                                <tr>
                                    <td>商户关键词:</td>
                                    <td>
                                        <input type="text" class="form-control" value="<?php echo ($goodsInfo["keywords"]); ?>" name="keywords"/>
                                        <span id="err_keywords" style="color:#F00; display:none;"></span>
                                    </td>
                                </tr>                                    
                                <tr>
                                    <td>商户详情描述:</td>
                                    <td width="85%">
										<textarea class="span12 ckeditor" id="goods_content" name="goods_content" title=""><?php echo ($goodsInfo["goods_content"]); ?></textarea>
                                        <span id="err_goods_content" style="color:#F00; display:none;"></span>                                         
                                    </td>                                                                       
                                </tr>   
                                </tbody>                                
                                </table>
                        </div>
                         <!--其他信息-->
                         
                        <!-- 商户相册-->
                        <div class="tab-pane" id="tab_goods_images">
                            <table class="table table-bordered">
                                <tbody>
                                <tr>                                    
                                    <td>                                    
                                    <?php if(is_array($goodsImages)): foreach($goodsImages as $k=>$vo): ?><div style="width:100px; text-align:center; margin: 5px; display:inline-block;" class="goods_xc">
                                            <input type="hidden" value="<?php echo ($vo['image_url']); ?>" name="goods_images[]">
                                            <a onclick="" href="<?php echo ($vo['image_url']); ?>" target="_blank"><img width="100" height="100" src="<?php echo ($vo['image_url']); ?>"></a>
                                            <br>
                                            <a href="javascript:void(0)" onclick="ClearPicArr2(this,'<?php echo ($vo['imgage_url']); ?>')">删除</a>
                                        </div><?php endforeach; endif; ?>
                                    
                                        <div class="goods_xc" style="width:100px; text-align:center; margin: 5px; display:inline-block;">
                                            <input type="hidden" name="goods_images[]" value="" />
                                            <a href="javascript:void(0);" onclick="GetUploadify(10,'','goods','call_back2');"><img src="/Public/images/add-button.jpg" width="100" height="100" /></a>
                                            <br/>
                                            <a href="javascript:void(0)"></a>
                                        </div>                                        
                                    </td>
                                </tr>                                              
                                </tbody>
                            </table>
                        </div>
                         <!--商户相册-->

                    </div>              
                    <div class="pull-right">
                        <input type="hidden" name="goods_id" value="<?php echo ($goodsInfo["goods_id"]); ?>">
                        <button class="btn btn-primary" onclick="ajax_submit_form('addEditGoodsForm','<?php echo U('Goods/addEditGoods?is_ajax=1');?>');" title="" data-toggle="tooltip" type="button" data-original-title="保存">保存</button>
                    </div>
			    </form><!--表单数据-->
                </div>
            </div>
        </div>    <!-- /.content -->
    </section>
</div>
<script type="text/javascript">

    var editor;
    $(function () {
        //具体参数配置在  editor_config.js 中
        var options = {
            zIndex: 999,
            initialFrameWidth: "95%", //初化宽度
            initialFrameHeight: 400, //初化高度
            focus: false, //初始化时，是否让编辑器获得焦点true或false
            maximumWords: 99999, removeFormatAttributes: 'class,style,lang,width,height,align,hspace,valign',//允许的最大字符数 'fullscreen',
            pasteplain: true, autoHeightEnabled: true,
            autotypeset: {
                mergeEmptyline: true,        //合并空行
                removeClass: true,           //去掉冗余的class
                removeEmptyline: false,      //去掉空行
                textAlign: "left",           //段落的排版方式，可以是 left,right,center,justify 去掉这个属性表示不执行排版
                imageBlockLine: 'center',    //图片的浮动方式，独占一行剧中,左右浮动，默认: center,left,right,none 去掉这个属性表示不执行排版
                pasteFilter: false,          //根据规则过滤没事粘贴进来的内容
                clearFontSize: false,        //去掉所有的内嵌字号，使用编辑器默认的字号
                clearFontFamily: false,      //去掉所有的内嵌字体，使用编辑器默认的字体
                removeEmptyNode: false,      //去掉空节点
                                             //可以去掉的标签
                removeTagNames: {"font": 1},
                indent: false,               // 行首缩进
                indentValue: '0em'           //行首缩进的大小
            },
            toolbars: [
                ['fullscreen', 'source', '|', 'undo', 'redo',
                    '|', 'bold', 'italic', 'underline', 'fontborder',
                    'strikethrough', 'superscript', 'subscript',
                    'removeformat', 'formatmatch', 'autotypeset',
                    'blockquote', 'pasteplain', '|', 'forecolor',
                    'backcolor', 'insertorderedlist',
                    'insertunorderedlist', 'selectall', 'cleardoc', '|',
                    'rowspacingtop', 'rowspacingbottom', 'lineheight', '|',
                    'customstyle', 'paragraph', 'fontfamily', 'fontsize',
                    '|', 'directionalityltr', 'directionalityrtl',
                    'indent', '|', 'justifyleft', 'justifycenter',
                    'justifyright', 'justifyjustify', '|', 'touppercase',
                    'tolowercase', '|', 'link', 'unlink', 'anchor', '|',
                    'imagenone', 'imageleft', 'imageright', 'imagecenter',
                    '|', 'insertimage', 'emotion', 'insertvideo',
                    'attachment', 'map', 'gmap', 'insertframe',
                    'insertcode', 'webapp', 'pagebreak', 'template',
                    'background', '|', 'horizontal', 'date', 'time',
                    'spechars', 'wordimage', '|',
                    'inserttable', 'deletetable',
                    'insertparagraphbeforetable', 'insertrow', 'deleterow',
                    'insertcol', 'deletecol', 'mergecells', 'mergeright',
                    'mergedown', 'splittocells', 'splittorows',
                    'splittocols', '|', 'print', 'preview', 'searchreplace']
            ]
        };
        editor = new UE.ui.Editor(options);
        editor.render("goods_content");

    });
</script>

<script>
    /*
     * 以下是图片上传方法
     */
 
    // 上传商品图片成功回调函数
    function call_back(fileurl_tmp){
        $("#original_img").val(fileurl_tmp);
    	$("#original_img2").attr('href', fileurl_tmp);
    }
 
    // 上传商品相册回调函数
    function call_back2(paths){
        
        var  last_div = $(".goods_xc:last").prop("outerHTML");	
        for (i=0;i<paths.length ;i++ )
        {                    
            $(".goods_xc:eq(0)").before(last_div);	// 插入一个 新图片
                $(".goods_xc:eq(0)").find('a:eq(0)').attr('href',paths[i]).attr('onclick','').attr('target', "_blank");// 修改他的链接地址
            $(".goods_xc:eq(0)").find('img').attr('src',paths[i]);// 修改他的图片路径
                $(".goods_xc:eq(0)").find('a:eq(1)').attr('onclick',"ClearPicArr2(this,'"+paths[i]+"')").text('删除');
            $(".goods_xc:eq(0)").find('input').val(paths[i]); // 设置隐藏域 要提交的值
        } 			   
    }
    /*
     * 上传之后删除组图input     
     * @access   public
     * @val      string  删除的图片input
     */
    function ClearPicArr2(obj,path)
    {
    	$.ajax({
                    type:'GET',
                    url:"<?php echo U('Admin/Uploadify/delupload');?>",
                    data:{action:"del", filename:path},
                    success:function(){
                                  $(obj).parent().remove(); // 删除完服务器的, 再删除 html上的图片				 
                    }
		});
    }
</script>
</body>
</html>