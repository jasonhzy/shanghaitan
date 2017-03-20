//页码特效
$(function(){
	$(".page ul li a").click(function(){
		$(this).addClass("pageactive");
		$(this).parent().siblings().children('a').removeClass('pageactive');
	});
})

//减计数器
$(document).on('click','.jishuqi .jian',function(){
	var count = parseInt($(this).next('td').text());
	if(count>1){
		count--;
		$(this).next('td').text(count);
		$(this).parent().parent().parent().parent().parent().parent('.gglc').attr('data-count',count);
		//alert($(this).parent().parent().tagName);
		//$(this).parent().parent().attr('data-count',count);
	}
});
//加计数器
$(document).on('click','.jishuqi .addc',function(){
	var addcount = parseInt($(this).prev('td').text());
	addcount++;	
	$(this).prev('td').text(addcount);
	$(this).parent().parent().parent().parent().parent().parent('.gglc').attr('data-count',addcount);
	$(this).parent().parent().attr('data-count',addcount);
});

//智能服务--右边在线咨询特效
var t;
//判断当滚动条滚动时右边在线咨询移动到顶部时固定
window.onscroll=function(){ 
   t=document.documentElement.scrollTop||document.body.scrollTop; 
   if(t>145||t==145){
   	   $(".bonneright").addClass('bonnerightfixed');
   	   //$(".col1-ul").addClass('bonnerightfixed');
   }
   else if(t<145){
   	   $(".bonneright").removeClass('bonnerightfixed');
   	   //$('.col1-ul').removeClass('bonnerightfixed');
   }
}



//智能所有页面点击选择菜单

