$(function(){
    $('.day,.today').click(function(){
        _day = $(this).attr('this-day');
        location.href = '/manage/user-sign-detail?day='+_day;
    });
});
