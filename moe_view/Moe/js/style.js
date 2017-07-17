$(function(){
	bottomTop(['#index','#member','#group']);
	/* 用于select点击跳转 */
	$('.selectchange').change(function(){
			window.location.href=$(this).val();
		}
	);
	$('.list_page').find('select').change(function(){
			window.location.href=$(this).val();
		}
	);
	$('#comment').css('top', $('#group').find('.l').height()+'px');
	$('#comment').css('top', $('#member_right').height()+'px');
	$('#comment').css('display','block');
});
/**
 * 固定bottom底部
 */
function bottomTop(arr){
	for(var i=0;i<arr.length;i++){
		if($(arr[i]).length>0){
			mainh = 0;
			$(arr[i]).find('div').each(function(){
				divh = parseInt($(this).css('height'));
				if(divh > mainh){
					mainh = divh;
				}
			});
			$('#bottom').css('position','relative');
			$('#bottom').css('top',mainh);
		}
	}
	$('#bottom').css('display','block');
}
/**
 * 投稿效果
 * @param file
 * @param did
 */
function changeShowLoad(file,did) {
	$(did).slideDown();
	showUploadImg(file,did+' img', 600);
}
/**
 * file上传无刷新显示
 * @param file
 * @param divid
 * @param max	最大宽高
 */
function showUploadImg(file,imgid,max) {
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
 * 表单tag中文逗号替换
 * @param str
 */
function tag_flag_replace(str) {
	$(str).val($(str).val().replace(/\，/g,','));
}
/* 投稿页表单检查 */
function tougao(divid) {
	fm = $(divid);
	if(fm.find('#picname').val()==''){
		alert('作品名不能为空');
	}else if(fm.find('#picname').val().length > 32){
		alert('作品名超过了32个字符');
	}else if(fm.find('#picinfo').val()==''){
		alert('作品简介不能为空');
	}else if(fm.find('#pictag').val().split(',') > 10){
		alert('标签个数超过10个');
	}else{
		fm.submit();
		return true;
	}
	return false;
}
/* 作品评分效果 */
if($('#grade_input_h').width()==0){
	$('#grade_input').on('mouseenter',function () {
		$(this).mousemove(function (e) {
			x = (e.layerX === undefined)?e.offsetX:e.layerX;
			$(this).find('div').width(x);
		});
	});
}
function grade_click(pid) {
	grade = parseInt($('#grade_input_h').width()/26)+1;
	data = 'pid='+ pid + '&grade=' + grade;
	$.ajax({
		url: '?v=memberImgGrade',
		type: 'GET',
		data: data,
		dataType: 'text',
		success: function (str) {
			$('#grade_input').off();
			alert(str);
		}
	})
}
/* 群组加入退出效果 */
function changegroup(id) {
	gp = $('#changegroup');
	if(gp.text() == '加入'){
		data = 'gid='+ id;
		$.ajax({
			url: '?v=groupAdd',
			type: 'GET',
			data: data,
			dataType: 'text',
			success: function (str) {
				if(str=='加入成功') {gp.text('退出');}
				alert(str);
			}
		})
	}else if(gp.text() == '退出'){
		data = 'gid='+ id;
		$.ajax({
			url: '?v=groupDel',
			type: 'GET',
			data: data,
			dataType: 'text',
			success: function (str) {
				if(str=='退出成功') {gp.text('加入');}
				alert(str);
			}
		})
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
/* 头像上传相关 */
function changeShowhead(file) {
	showUploadImg(file,'#memberupdhead .imghead img',160);
	imgAutoWHmax('#memberupdhead .imghead img',160);
	$('#memberupdhead .imghead img').load(function () {
		changeImgsize('#memberupdhead .imghead');
		moveImgposition('#memberupdhead .imghead');
	}).each(function () {
		if(this.complete) $(this).load();
	});
}
function memberUpdheadSub() {
	$('#memberupdhead input[name="headW"]').attr('value',parseFloat($('#memberupdhead .imghead img').css('width')));
	$('#memberupdhead input[name="headH"]').attr('value',parseFloat($('#memberupdhead .imghead img').css('height')));
	$('#memberupdhead input[name="headT"]').attr('value',parseFloat($('#memberupdhead .imghead img').css('top')));
	$('#memberupdhead input[name="headL"]').attr('value',parseFloat($('#memberupdhead .imghead img').css('left')));
	$('#memberupdhead form').submit();
}