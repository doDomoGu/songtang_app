$(function(){

    $('#loginform-username').focus();

    var _height = $(window).outerHeight() - $('#site-login header').outerHeight() - $('#site-login footer').outerHeight();

    $('#site-login #login-form-section').height(_height);

    var _height2 = $('#login-form-section article').height();

    var _height3 = parseInt(parseInt(_height-_height2) / 2);

    var _height4 = parseInt(_height3 * 2);



    $('#site-login #login-form-section').css('padding-top',_height3+'px');
    $('#site-login #login-form-section').css('padding-bottom',_height3+'px');

    $('#site-login #login-form-section').height(parseInt(_height-_height4));
});