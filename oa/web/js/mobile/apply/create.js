$(function(){
    $('.data-area_id').on('change',function(){
        getTaskSelect();
    });

    $('.data-business_id').on('change',function(){
        getTaskSelect();
    });

    var getTaskSelect = function(){
        area_id = $('.data-area_id').val();
        business_id = $('.data-business_id').val();
        $.ajax({
            url: '/apply/get-task-list',
            type: 'post',
            //async : false,
            dataType: 'json',
            data: {
                area_id:area_id,
                business_id:business_id
            },
            success: function (data) {
                if(data.result){
                    $('.data-task_id').html(data.html);
                }else{
                    //$('.task-preview').html(data.errormsg).show();

                }
            }
        });
    };
    getTaskSelect();

    $('#dialog-failure .weui-dialog__btn').click(function(){
        $('#dialog-failure').hide();
    });

    $('#submit-btn').click(function(){
        title = $('.data-title').val();
        task_id = $('.data-task_id').val();
        message = $('.data-message').val();
        $.ajax({
            url: '/apply/do-create',
            type: 'post',
            //async : false,
            dataType: 'json',
            data: {
                title:title,
                task_id:task_id,
                message:message
            },
            success: function (data) {
                if(data.result){
                    $('#dialog-success').show();
                }else{
                    $('#dialog-failure').show();
                    //$('.task-preview').html(data.errormsg).show();

                }
            }
        });
    })
});