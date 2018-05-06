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
            var attachments = {};
            var attach_num = 0;
            $('.attachment_files').each(function(){
                var attach_temp = $(this).val().split('|||');
                attachments[attach_num] = {'url':attach_temp[0],'name':attach_temp[1]};
                attach_num++;
            });
            $.ajax({
                url: '/apply/do-operation',
                type: 'post',
                //async : false,
                dataType: 'json',
                data: {
                    result: $('#applydoform-result input:checked').val(),
                    message: $('#applydoform-message').val(),
                    apply_id: $('#apply_id').val(),
                    attachment: attachments
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


    $('.attachment_preview').on('click',function(e){
        e.preventDefault();

        $('#file_preview_iframe').attr('src',$(this).attr('data-url'));

        $('#file_preview_modal').modal('show')
    })
});