$(function(){
    $('.type-select').on('change',function(){
        type_param_div = $(this).parents('.permission-one').find('.type_param');
        type_param_div.find('span').hide();
        val = $(this).val();
        if(val==1){
            type_param_div.find('span.type_param_all').show();
        }else if(val==2){
            type_param_div.find('span.type_param_area').show();
        }else if(val==3){
            type_param_div.find('span.type_param_business').show();
        }else if(val==7){
            type_param_div.find('span.type_param_group').show();
        }else if(val==8){
            type_param_div.find('span.type_param_user').show();
        }
    });

    $('.type-select').change();
});
