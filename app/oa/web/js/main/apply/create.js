$(function(){
    $('.task-preview').html('').hide();
    $('.task-select').on('change',function(e) {
        var task_id = $(this).val();
        $('.task-preview').html('').hide();
        if(task_id>0){
            $.ajax({
                url: '/apply/get-task-preview',
                type: 'post',
                //async : false,
                dataType: 'json',
                data: {
                    task_id:task_id
                },
                success: function (data) {
                    if(data.result){
                        $('.task-preview').html(data.html).show();
                    }else{
                        $('.task-preview').html(data.errormsg).show();

                    }
                }
            });
        }
    });

    if($('#applycreateform-task_category').html()!=undefined){
        $('#applycreateform-task_category option').each(function(){
            if($(this).val()[0] == 't'){
                $(this).attr('disabled',true);
            }
        })

        /*$('#applycreateform-task_category').on('change',function(){
            $.ajax({
                url: '/apply/get-task-preview',
                type: 'post',
                //async : false,
                dataType: 'json',
                data: {
                    task_id:task_id
                },
                success: function (data) {
                    if(data.result){
                        $('.task-preview').html(data.html).show();
                    }else{
                        $('.task-preview').html(data.errormsg).show();

                    }
                }
            });
        })*/
    }
});