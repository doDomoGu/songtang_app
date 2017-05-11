$(function(){
    $('#tab-sel li').eq(0).addClass('active');

    $('.cont').eq(0).show();


    $('#tab-sel li').click(function(){
        var i = $(this).index();
        $('#tab-sel li').removeClass('active');
        $('#tab-sel li').eq(i).addClass('active');
        $('.cont').hide();
        $('.cont').eq(i).show();
    })
});