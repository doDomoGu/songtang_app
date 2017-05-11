$(function(){
    dir_ids_count = $('#dir-ids-count').val();
    dir_ids = eval($('#dir-ids').html());

    for(i in dir_ids){

        str = dir_ids[i]+': ';
        $.ajax({
            url: '/manage/dir-deploy-cache',
            async: false,
            data: {dir_id:dir_ids[i]},
            dataType: "json",
            success: function(response) {
                if(response.result){
                    str += '生成缓存成功 '+response.time;
                }else{
                    str += '生成缓存失败';
                }

            }
        });
        str += ' ('+(parseInt(i)+1)+'/'+dir_ids_count +')<Br/>';
        $('#deploy-show').prepend(str);
    }
})