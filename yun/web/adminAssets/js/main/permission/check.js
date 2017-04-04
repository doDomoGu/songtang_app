$(function(){
    var v = $('.check-type').val();
    if(v==1) {
        $('.dir_input').show();
    }else if(v==2){
        $('.filedir_input').show();
    }else if(v==3){
        $('.file_input').show();
    }
});
$('.check-type').change(function(){
    var v = $(this).val();
    $('.dir_input').hide();
    $('.filedir_input').hide();
    $('.file_input').hide();
    if(v==1) {
        $('.dir_input').show();
    }else if(v==2){
        $('.filedir_input').show();
    }else if(v==3){
        $('.file_input').show();
    }
});
