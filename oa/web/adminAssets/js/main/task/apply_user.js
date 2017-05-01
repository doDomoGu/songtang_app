$(function () {
    $('#create-btn').click(function(){
        $('#createContent .errormsg-text').html('').hide();
        var tid =$('#createContent .task-id').val();
        $.ajax({
            url: '/admin/task/apply-user-add',
            type: 'post',
            //async : false,
            dataType: 'json',
            data: {
                tid : tid,
                user_id :$('#createContent .create-user-id').val()
            },
            success: function (data) {
                if(data.result){
                    location.href='/admin/task/apply-user?tid='+tid;
                }else{
                    $('#createContent .errormsg-text').html(data.errormsg).show();

                }
            }
        });
    });


    $('.submit-btn').click(function(){
        $('.errmsg').html('').hide();
        var tid =$('.task-id').val();
        var userids = [];
        $('input[name="user_check[]"]:checked').each(function(){
            userids.push($(this).val());
        });
        var user_id = userids.join(',');

        $.ajax({
            url: '/admin/task/apply-user-add2',
            type: 'post',
            //async : false,
            dataType: 'json',
            data: {
                tid : tid,
                user_id : user_id
            },
            success: function (data) {
                if(data.result){
                    location.href='/admin/task/apply-user?tid='+tid;
                }else{
                    $('#createContent .errormsg-text').html(data.errormsg).show();

                }
            }
        });
    });

    $('#checkAll').click(function(){
       if($(this).prop('checked')==true){
           $('input[name="user_check[]"]').each(function(){
               $(this).prop('checked',true);
           });
       }else{
           $('input[name="user_check[]"]').each(function(){
               $(this).prop('checked',false);
           });
       }
    });
});