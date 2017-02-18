$(function () {
    $('.set-auth').click(function(){
        if(confirm('确认要更改权限吗？')){
            user_id = $(this).attr('data-user');
            app = $(this).attr('data-app');
            act = $(this).attr('data-act');
            location.href = '/user-app-auth/change?user_id='+user_id+'&app='+app+'&act='+act;
        }
    });

    $('#add-btn').click(function(){
        $.ajax({
            url: '/user-app-auth/change',
            type: 'get',
            data: {
                user_id:$('#add-user-id').val(),
                app:$('#add-app').val(),
                act:'new'
            },
            dataType: 'json',
            success: function (data) {
                if(data.success==true){
                    location.href = '/user-app-auth';
                }
            }
        });
    })
});