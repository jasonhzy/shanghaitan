<?php if (!defined('THINK_PATH')) exit();?>
                    <form method="post" enctype="multipart/form-data" target="_blank" id="form-order">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);"></td>
                                    </td>
                                    <td class="text-left">
                                        <a href="javascript:sort('user_id');">ID</a>
                                    </td>
                                    <td class="text-left">
                                        <a href="javascript:sort('name');">姓名</a>
                                    </td>
                                    <td class="text-left">
                                        <a href="javascript:sort('nickname');">昵称</a>
                                    </td>
                                    <td class="text-left">
                                        <a href="javascript:sort('age');">年龄</a>
                                    </td>
                                    <td class="text-left">
                                        <a href="javascript:sort('email');">邮箱</a>
                                    </td>
                                    <td class="text-left">
                                        <a href="javascript:sort('mobile');">手机号码</a>
                                    </td>
                                    <td class="text-left">
                                        <a href="javascript:sort('reg_time');">注册日期</a>
                                    </td>
                                    <td class="text-left">
                                        <a href="javascript:sort('is_check');">状态</a>
                                    </td>
                                    <td class="text-right">操作</td>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if(is_array($userList)): $i = 0; $__LIST__ = $userList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$list): $mod = ($i % 2 );++$i;?><tr>
                                        <td class="text-center">
                                            <input type="checkbox" name="selected[]" value="6">
                                            <input type="hidden" name="shipping_code[]" value="flat.flat">
                                        </td>
                                        <td class="text-right"><?php echo ($list["user_id"]); ?></td>
                                        <td class="text-left"><?php echo ($list["name"]); ?></td>
                                        <td class="text-left"><?php echo ($list["nickname"]); ?></td>
                                        <td class="text-left"><?php echo ($list["age"]); ?></td>
                                        <td class="text-left"><?php echo ($list["email"]); ?>
                                           <!-- <?php if(($list['email_validated'] == 0) AND ($list['email'])): ?>(未验证)<?php endif; ?>-->
                                        </td>
                                        <td class="text-left"><?php echo ($list["mobile"]); ?>
                                           <!-- <?php if(($list['mobile_validated'] == 0) AND ($list['mobile'])): ?>(未验证)<?php endif; ?>-->
                                        </td>
                                        <td class="text-left"><?php echo (date('Y-m-d H:i',$list["reg_time"])); ?></td>
                                        <td class="text-left">
                                            <?php if($list[is_check] == 0): ?><span  class="static">等待审核</span><?php endif; ?>
                                            <?php if($list[is_check] == 1): ?><span  class="static">已审核</span><?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <a href="<?php echo U('Admin/user/detail',array('id'=>$list['user_id']));?>" data-toggle="tooltip" title="" class="btn btn-info" data-original-title="查看详情"><i class="fa fa-eye"></i></a>
                                           <!-- <a href="<?php echo U('Admin/user/address',array('id'=>$list['user_id']));?>" data-toggle="tooltip" title="" class="btn btn-info" data-original-title="收货地址"><i class="fa fa-home"></i></a>
                                            <a href="<?php echo U('Admin/order/index',array('user_id'=>$list['user_id']));?>" data-toggle="tooltip" title="" class="btn btn-info" data-original-title="订单查看"><i class="fa fa-shopping-cart"></i></a>-->
                                           <!-- <a href="<?php echo U('Admin/user/account_log',array('id'=>$list['user_id']));?>" data-toggle="tooltip" title="" class="btn btn-info" data-original-title="账户"><i class="glyphicon glyphicon-yen"></i></a>-->
                                            <a href="<?php echo U('Admin/user/delete',array('id'=>$list['user_id']));?>" id="button-delete6" data-toggle="tooltip" title="" class="btn btn-danger" data-original-title="删除"><i class="fa fa-trash-o"></i></a>
                                        </td>
                                    </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </form>
                    <div class="row">
                        <div class="col-sm-6 text-left"></div>
                        <div class="col-sm-6 text-right"><?php echo ($page); ?></div>
                    </div>
<script>
    $(".pagination  a").click(function(){
        var page = $(this).data('p');
        ajax_get_table('search-form2',page);
    });
</script>