$(function(){
    /* site-nav */
    $('.side-nav > ul.nav').on('mouseenter','> li.menu-list',function() {
        $(this).addClass('nav-hover');
    }).on('mouseleave','> li.menu-list',function(){
        $(this).removeClass('nav-hover');
    });

    $('.side-nav > ul.nav > li.menu-list').on('click','> a',function(){
        var li_menu = $(this).parent('.menu-list');
        var is_active = li_menu.hasClass('nav-active');
        var all_li_menu = $('.side-nav > ul.nav > li.menu-list');
        all_li_menu.removeClass('nav-active');
        all_li_menu.find('.sub-menu-list').collapse('hide');
        all_li_menu.find('> a').addClass('collapsed');

        if(!is_active){
            $(this).next('.sub-menu-list').collapse('show');
            li_menu.addClass('nav-active');
            $(this).removeClass('collapsed');
        }
    });


    /* alert */

    if($('.top-alert').length>0){
        $('.top-alert > .alert').on('close.bs.alert', function () {
            //$(this).css('margin-bottom','0');
            $(this).parent('.top-alert').css('height','0');
            $(this).parent('.top-alert').css('margin-bottom','0');
        });



        $('#alert-section').css ('top','-52px');
        $('#alert-section').stop().animate({'top':6},500);

        setTimeout(function(){
            //$('.top-alert').stop().animate({'height':0,'margin-bottom':0},2000);
            $('#alert-section').stop().animate({'top':'-52'},1000);
        },3000);
    }

});