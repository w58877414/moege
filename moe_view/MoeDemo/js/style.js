$(function($) {
	//左侧栏滑动跟随到边固定的特效
	wsh = $(window).scrollTop();
	duflag = 0;	//0为上，1为下
	$(window).scroll(function () {
		lh = $('#left').height();
		lt = parseInt($('#left').css('top'));
		ws = $(window).scrollTop();
		wh = $(window).height();
		ps = $('#left').css('position');
		if(ws<wsh){
			if(ps=='fixed' && duflag==1){
				$('#left').css('position','absolute');
				$('#left').css('top',ws-(lh-wh));
				$('#left').css('bottom','auto');
			}else if(lt>=ws){
				$('#left').css('position','fixed');
				$('#left').css('top',0);
				$('#left').css('bottom','auto');
			}
			duflag = 0;
		}
		if(ws>wsh){
			if (ps=='fixed' && duflag==0){
				$('#left').css('position','absolute');
				$('#left').css('top',ws);
				$('#left').css('bottom','auto');
			}else if (lt+lh<=ws+wh){
				$('#left').css('position','fixed');
				$('#left').css('bottom','0');
				$('#left').css('top','auto');
			}
			duflag = 1;
		}
		wsh = ws;
	});

	$('.pages').find('select').change(function(){
			window.location.href=$(this).val();
		}
	);
});

/**
 * 重要（勿删）：为了解决滚动条后置所出现的宽度获取不正确
 * @param scrollnnn		//滚动条兼容参数
 */
var scrollnnn = 17;
$(window).resize(function(){
	if(scrollnnn != 1){
		scrollnnn = 1;
	}
});
/**
 * 自适应元素间距(格式divid(ul) li)
 * @param divid
 * @returns {boolean}
 * @constructor
 */
function AutoMargin(divid){
	$(divid).each(function () {
		if($(this).length > 0){
			outW = parseFloat($(this).innerWidth()-scrollnnn);	//总宽(scrollnnn是因为兼容性，因为家在顺序导致的滚动条后置，出现的宽度获取不正确)
			inW = parseFloat($(this).find('li').outerWidth());	//小元素宽度
			max = parseFloat($(this).find('li').length);
			if(max*inW<=outW){
				dif = outW-max*inW;
				mar = dif/max/2.0;
			}else{
				dif = outW%inW;
				num = Math.floor(outW/inW);
				mar = dif/num/2.0;
			}
			$(this).find('li').css({ "margin-left": mar, "margin-right": mar });
		}else{
			return false;
		}
	});
}

/**
 * file上传无刷新显示
 * @param file
 * @param divid
 * @param max	最大宽高
 */
function showUploadImg(file,imgid,max) {
	$(imgid).css('left','0');
	$(imgid).css('top','0');
	if (file.files && file.files[0])
	{
		var reader = new FileReader();
		reader.readAsDataURL(file.files[0]);
		reader.onload = function(evt){
			$(imgid).attr('src',evt.target.result);
		}
	}else{   //针对IE8以下
		$(imgid).attr('src','file:///'+file.value);
	}
}

/**
 * 自适应图片宽高，并绝对居中
 * @param imgid
 * @param max
 */
function imgAutoWH(imgid,max) {
	$(imgid).each(function () {
        $(this).load(function () {
            $(this).css('display','block');
            if($(this).width() > $(this).height()){
                $(this).width(max);
                $(this).css('margin-top',(max-max/$(this).width()*$(this).height())/2);
                $(this).css('height','auto');
            }else{
                $(this).height(max);
                $(this).css('margin','0 auto');
                $(this).css('width','auto');
            }
        }).each(function () {
            if(this.complete) $(this).load();
        });
	})
}
/**
 * 自适应图片宽高，撑满max
 * @param imgid
 * @param max
 */
function imgAutoWHmax(imgid,max) {
	$(imgid).each(function () {
        $(this).load(function () {
            $(this).css('display','block');
            if($(this).width() > $(this).height()){
                $(this).height(max);
                $(this).css('width','auto');
            }else{
                $(this).width(max);
                $(this).css('height','auto');
            }
        }).each(function () {
            if(this.complete) $(this).load();
        });
	});
}

/**
 * 鼠标点击移动图片(调用检测边界，超过边界自动还原)
 * @param divid
 */
function moveImgposition(divid) {
	imgPositionBase(divid);
    $(divid).mousedown(function(e){
        e.preventDefault();
        $(divid).css('cursor', 'move');
        xx = e.clientX;
        yy = e.clientY;
        zleft = parseFloat($(divid).find('img').css('left'));
        ztop = parseFloat($(divid).find('img').css('top'));
        $(divid).on("mousemove",function(e) {
            e.preventDefault();
            $(divid).find('img').css('left', zleft+e.clientX-xx);
            $(divid).find('img').css('top', ztop+e.clientY-yy);
        })
    });
    $(divid).on("mouseup mouseout",function () {
        coord = imgMaxSizefordiv(divid);
        if(coord.t) $(divid).find('img').css('top','0');
        if(coord.b) $(divid).find('img').css('top',coord.dt);
        if(coord.l) $(divid).find('img').css('left','0');
        if(coord.r) $(divid).find('img').css('left',coord.dl);
        $(divid).css('cursor', 'auto');
        $(divid).off("mousemove");
    });
}

/**
 * 通过滚轮放大缩小图片(兼容火狐和IE)
 * @param divid
 * @param imgid
 */
function changeImgsize(divid) {
	n = 0;
	s = 0.02;
	h = parseFloat($(divid).find('img').height());
	w = parseFloat($(divid).find('img').width());
    maxh = parseFloat($(divid).height());
    maxw = parseFloat($(divid).width());
	$(divid).on("mousewheel DOMMouseScroll", function (e) {
		var delta = (e.originalEvent.wheelDelta && (e.originalEvent.wheelDelta > 0 ? 1 : -1)) || (e.originalEvent.detail && (e.originalEvent.detail > 0 ? -1 : 1));
		if (delta > 0) {    // 向上滚
			n++;
		} else if (delta < 0 && n*s!=-1 && !imgMaxSizefordiv(divid).a) { // 向下滚（第二条件限制缩小为0时停止）
			n--;
		}
		$(divid).find('img').height(h*(1+n*s));
		$(divid).find('img').width(w*(1+n*s));
        if($(divid).find('img').height()<maxh) $(divid).find('img').height(maxh);
        if($(divid).find('img').width()<maxw) $(divid).find('img').width(maxw);
	});
}

/**
 * 判断图片在各边界是否脱离了填充位置(到边缘则为true)
 * @param divid
 * @returns {{a: boolean, t: boolean, r: boolean, b: boolean, l: boolean, dt: number, dl: number}}
 */
function imgMaxSizefordiv(divid) {
	imgPositionBase(divid);
	maxh = parseFloat($(divid).height());
	maxw = parseFloat($(divid).width());
	itop = parseFloat($(divid).find('img').css('top'));
	ileft = parseFloat($(divid).find('img').css('left'));
	iheight = $(divid).find('img').height();
	iwidth = $(divid).find('img').width();
    (itop>0)? rtop=true : rtop=false;
    (iheight<=(-itop)+maxh)? rbottom=true : rbottom=false;
    (ileft>0)? rleft=true : rleft=false;
    (iwidth<=(-ileft)+maxw)? rright=true : rright=false;
    (rtop || rright || rbottom || rleft)? rall=true: rall=false;
    if(isNaN(itop)||isNaN(ileft)){
        imgPositionBase(divid);
    }
    return {a:rall,t:rtop,r:rright,b:rbottom,l:rleft,dt:maxh-iheight,dl:maxw-iwidth};
}

/**
 * 判断DIV下图片定位属性是否可应用（作为定位动画操作的提前条件）
 * @param divid
 */
function imgPositionBase(divid) {
	if($(divid).find('img').css('position') == 'static'){
		$(divid).find('img').css('position','absolute');
		$(divid).find('img').css('left','0');
		$(divid).find('img').css('top','0');
	}
}

/**
 * 页面滚轮事件的开启以及关闭
 */
function disDocMouseScroll() {
	$(document).on("mousewheel DOMMouseScroll",function (e) {
		e.preventDefault();
	});
}
function enDocMouseScroll() {
	$(document).off("mousewheel DOMMouseScroll");
}

/**
 * 遮罩层的开启以及关闭
 * @param divid
 */
function openMark(divid) {
	$("#mask").addClass("mask").fadeIn("slow");
	$(divid).fadeIn("slow");
	disDocMouseScroll();
}
function closeMark(divid) {
	$(divid).fadeOut("fast");
	$("#mask").css({ display: 'none' });
	enDocMouseScroll();
}
