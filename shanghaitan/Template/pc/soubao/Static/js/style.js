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
})
	
	
	