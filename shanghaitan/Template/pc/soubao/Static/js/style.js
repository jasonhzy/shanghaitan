// JavaScript Document
$(function(){
 //城市选择
 $(".tab-city").click(function(){
  $(".city").toggleClass("citycur");
  $(".citylist").toggle();	 
 })	
 $(".citylist dd a").click(function(){
  var cityname=$(this).text();
  $(".cityname").text(cityname);
  $(".citylist").hide();
  $(".city").removeClass("citycur")	 
 })
 //搜索下拉
 $(".search-nav ul li").click(function(){
  var texts=$(this).text();
  $("#seatext").text(texts)	 
 })
 //版块选项卡 遍历
 $(".tab li").click(function(){
  $(this).addClass("labcur").siblings("li").removeClass("labcur");	 
 })
 var $tab=$('section');
 $tab.each(function(){
	$(this).find(".tablist").eq(0).show();
	var tabli=$(this).find(".tab li")
	var tablist=$(this).find(".tablist")
	tabli.click(function(){
	 var index=$(this).index();
	 $(tablist).eq(index).fadeIn().siblings(tablist).hide();
	})
 })	
 //个人资料禁止编辑
 $(".disable-editor input,.disable-editor textarea").attr("readonly","readonly")
 $(".disable-editor select").attr("disabled","disabled")
 //编辑
 $(".mate-bianji").click(function(){
  $(this).hide();
  $(this).next(".mate-baocun").fadeIn();	
  $(this).parents("h3").next(".mate-r-mess").find("table").removeClass("disable-editor");
  $(this).parents("h3").next(".mate-r-mess").find("table select").removeAttr("disabled");
 })
 //保存
 $(".mate-baocun").click(function(){
  $(this).hide();
  $(this).prev(".mate-bianji").fadeIn();	
  $(this).parents("h3").next(".mate-r-mess").find("table").addClass("disable-editor");
  $(this).parents("h3").next(".mate-r-mess").find("table select").attr("disabled","disabled");
 })
})
	
	
	