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
});