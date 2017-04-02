$(function(){
    var v = $('.check-type').val();
    console.log(v);
    $('.dir_input').hide();
    $('.filedir_input').hide();
    if(v==1) {
        $('.dir_input').show();
    }else if(v==2){
        $('.filedir_input').show();
    }
})
$('.check-type').change(function(){
    var v = $(this).val();
    $('.dir_input').hide();
    $('.filedir_input').hide();
    if(v==1) {
        $('.dir_input').show();
    }else if(v==2){
        $('.filedir_input').show();
    }
})/**
 * Created by dodomogu on 2017/4/3.
 */
