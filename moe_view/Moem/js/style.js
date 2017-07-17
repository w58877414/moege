$(function () {
    main_top();
    touch_left();
    width_to_height(['.pic_member_pic > a','.member_pic > a']);
    /* 下拉框点击跳转 */
    if($('.selectchange').length>0){
        $('.selectchange').change(function(){
                window.location.href=$(this).val();
            }
        );
    }
    $('.list_page').find('select').change(function(){
            window.location.href=$(this).val();
        }
    );
    /* select选择显示 */
    if($('#header .type > form > div').length > 0){
        $('#header .type > form > div').each(function () {
            $(this).find('span').text($(this).find('select > option:selected').text());
        })
    }
});
/* 内容距离顶部边距的生成 */
function main_top() {
    $('#main').animate({
        top: $('#header').height()+'px'
    },600);
}
/* 左侧栏显示与关闭 */
function left_menu_open() {
    /*$('#left').addClass('open');*/
	if($('#left').css('display')=='none'){
        $('#left').css('display', 'block');
        $('#left').animate({
            left: '0'
        }, 600);
    }
}
function left_menu_close() {
    /*$('#left').removeClass('open');*/
    if($('#left').css('display')!='none'){
        $('#left').animate({
            left: '-280px'
        }, 600, function () {
            $('#left').css('display', 'none');
        });
    }

}
/* 屏幕左滑右滑操纵left页 */
function touch_left() {
    var point;
    $(document).on('touchstart',function (e) {
        point = e.originalEvent.changedTouches[0].pageX;
    });
    $(document).on('touchend',function (e) {
        var npoint = e.originalEvent.changedTouches[0].pageX;
        ww = $(window).width();
        if(point - npoint > ww*0.4){
            left_menu_close();
        }else if(point - npoint < -ww*0.4){
            left_menu_open();
        }
        point = '';
    });
}
/* 搜索栏下拉 */
function search_change() {
    search = $('#header').find('.search');
    if(search.css('display')=='none'){
        search.slideDown(600);
        setTimeout(function () {
            main_top();
        },600);
    }else{
        search.slideUp(600);
        setTimeout(function () {
            main_top();
        },600);
    }
}
/* 图片高度与宽度成比例（针对一行同高） */
function width_to_height(arr) {
    for(var i=0;i<arr.length;i++){
        if($(arr[i]).length>0){
            console.log($('.member_pic a').width());
            mainw = $(arr[i]).width();
            $(arr[i]).height(mainw);
            $(arr[i]).css('line-height',mainw+'px');
        }
    }
}

/* 群组加入退出效果 */
function changegroup(id) {
    gp = $('#changegroup');
    if(gp.text() == '加入'){
        data = 'gid='+ id;
        $.ajax({
            url: '?m=Moem&v=groupAdd',
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
            url: '?m=Moem&v=groupDel',
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