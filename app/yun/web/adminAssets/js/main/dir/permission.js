$(function(){
    $('.user-match-type-select').on('change',function(){
        user_match_param_td = $(this).parent().next();
        user_match_param_td.find('.user_match_param_div').hide();
        val = $(this).val();
        if(val==1){
            user_match_param_td.find('.user_match_param_1_div').show();
        }/*else if(val==2){
            type_param_div.find('span.type_param_area').show();
        }*/else if(val==3){
            user_match_param_td.find('.user_match_param_3_div').show();
        }else if(val==7){
            user_match_param_td.find('.user_match_param_7_div').show();
        }/*else if(val==8){
            type_param_div.find('span.type_param_user').show();
        }*/
    });

    $('.user-match-type-select').change();

    $('.save-btn').on('click',function(){
        var dir_id = $('#dir_id').val();

        $.ajax({
            url: '/admin/dir/permission-save',
            type: 'post',
            data: $('#permission-form').serialize(),
            dataType: 'json',
            success: function (data) {
                if(data.result==true){
                    location.href = '/admin/dir/permission?dir_id='+dir_id;
                }
            }
        });
    })
});
