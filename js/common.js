$(function(){
	//字体样式
    function setRem(){
        var clientWidth=$(window).width();
        var nowRem=(clientWidth/320*10);
        $("html").css("font-size",parseInt(nowRem, 10)+"px");
    };
    setRem();
    $(function(){
        var timer;
        $(window).bind("resize",function(){
            clearTimeout(timer)
            timer=setTimeout(function(){
                setRem();
            }, 100)
        })
    });
});
//点击底部导航图标时,变换图标颜色
$(".foot div").click(function(){
	$(this).find(".text").css("color","#1B82D2");
	$(this).siblings().find(".text").css("color","#666");		
	//用jQuery获取当前节点的img
	var current_img=$(this).find("img");
	var current_src=current_img.attr("src");						
	var current_pic=current_src.substring(current_src.lastIndexOf('/')+1,current_src.length);
	//图片集合
	var imgs=document.getElementsByName("img");		
	for(var i=0;i<imgs.length;i++){						
		var path=imgs[i].src;
		var start=path.lastIndexOf('/')+1;//起始位置
		var pic=path.substring(start,path.length);
		//判断当前选中节点
		if(pic!=current_pic){				
			imgs[i].src=path.replace("1.png","0.png");
		}else{
			imgs[i].src=path.replace("0.png","1.png");
		}
	}
})