$(function(){
    $('.task-preview').html('').hide();
    $('.task-select').on('change',function(e) {
        var task_id = $(this).val();
        $('.task-preview').html('').hide();

        $('#applycreateform-form_id').html('<option value="">==请选择==</option>');
        $('.form-content').html('').hide();
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
                        $('#applycreateform-form_id').html(data.formSelectHtml);
                        $('.form-content').html(data.formContentHtml).show();
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

        $('#applycreateform-task_category').on('change',function(){
            $('#applycreateform-task_id').html('<option value="">==请选择==</option>');
            $('.task-preview').html('').hide();
            $('#applycreateform-form_id').html('<option value="">==请选择==</option>');
            $('.form-content').html('').hide();


            $.ajax({
                url: '/apply/get-task-list',
                type: 'post',
                //async : false,
                dataType: 'json',
                data: {
                    task_category:$(this).val()
                },
                success: function (data) {
                    if(data.result){
                        $('#applycreateform-task_id').html(data.html);
                    }else{
                        //alert(data.errormsg);
                        //$('.task-preview').html(data.errormsg).show();

                    }
                }
            });
        })
    }

    $('#applycreateform-task_category').change();
});