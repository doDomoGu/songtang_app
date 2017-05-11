$(function(){


    var _height = $(window).outerHeight() - $('#site-login header').outerHeight() - $('#site-login footer').outerHeight();

    /*$('#site-login .main-content').height(_height);*/

    var _height2 = $('.main-content').height();

    if(_height <= _height2){
        _height3 = 0;
        _height4 = 0;


    }else{
        var _height3 = parseInt(parseInt(_height-_height2) / 2);


        var _height4 = parseInt(_height3 * 2);
    }





    $('#site-login .success-div ').css('margin-top',_height3+'px');
    $('#site-login .main-content').css('margin-bottom',_height3+'px');

    $('#site-login .main-content').height(parseInt(_height-_height4));
});