$(function(){
    $('#dialog-failure .weui-dialog__btn').click(function(){
        $('#dialog-failure').hide();
    });

    $('#submit-btn').click(function(){
        apply_id = $('.data-apply_id').val();
        result = $('.data-result').val();
        message = $('.data-message').val();
        $.ajax({
            url: '/apply/do-operation',
            type: 'post',
            //async : false,
            dataType: 'json',
            data: {
                apply_id:apply_id,
                result:result,
                message:message
            },
            success: function (data) {
                if(data.result){
                    $('#dialog-operation-success').show();
                }else{
                    $('#dialog-operation-failure').show();
                    //$('.task-preview').html(data.errormsg).show();

                }
            }
        });
    })
});