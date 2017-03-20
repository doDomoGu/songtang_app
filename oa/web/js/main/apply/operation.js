$(function(){
    $('#submit-button').click(function(e){
        e.preventDefault();
        var flag = true;

        if($('#applydoform-message').val()==''){
            if(confirm('没有填写备注信息，确认要提交吗？')){

            }else{
                flag = false;
            }
        }
        if(flag){
            $.ajax({
                url: '/apply/do-operation',
                type: 'post',
                //async : false,
                dataType: 'json',
                data: {
                    result: $('#applydoform-result input:checked').val(),
                    message: $('#applydoform-message').val(),
                    apply_id: $('#apply_id').val()
                },
                success: function (data) {
                    if(data.result){
                        location.href = '/apply/done';
                    }else{
                        alert(data.errormsg);
                        /*$('.task-preview').html(data.errormsg).show();*/

                    }
                }
            });
        }else{
            return false;
        }

    });


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
});