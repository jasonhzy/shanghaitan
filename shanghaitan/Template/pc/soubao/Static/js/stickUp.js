/**
 * Created by liusongjin on 15-4-4.
 */
$(function () {
	//导航定位的高度
	//var menunum=$("#menu li").length;
	//var menuheight=menunum*14+(menunum-1)*30;
	//$("#menu").css("margin-top",-menuheight/2+"px")
    $(window).scroll(function () {
		if($(this).scrollTop() > 560)
		{
			$("#menu").fadeIn()
		}
		else{
			$("#menu").hide()
		}
        var scrollTop = $(document).scrollTop();
        var documentHeight = $(document).height();
        var windowHeight = $(window).height();
        var contentItems = $("#content").find(".item");
        var currentItem = "";

        if (scrollTop+windowHeight==documentHeight) {
            currentItem= "#" + contentItems.last().attr("id");
        }else{
            contentItems.each(function () {
                var contentItem = $(this);
                var offsetTop = contentItem.offset().top;
                if (scrollTop > offsetTop - 100) {//此处的200视具体情况自行设定，因为如果不减去一个数值，在刚好滚动到一个div的边缘时，菜单的选中状态会出错，比如，页面刚好滚动到第一个div的底部的时候，页面已经显示出第二个div，而菜单中还是第一个选项处于选中状态
                    currentItem = "#" + contentItem.attr("id");
                }
            });
        }
        if (currentItem != $("#menu").find(".current").attr("href")) {
            $("#menu").find(".current").removeClass("current");
            $("#menu").find("[href=" + currentItem + "]").addClass("current");
			//$("#menu").find("[href=" + currentItem + "] em").css("background","#f00");
        }
    });
});
