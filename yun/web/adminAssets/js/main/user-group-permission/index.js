$(function () {
    $('#add-btn').click(function(){
        $.ajax({
            url: '/admin/user-group-permission/add-group',
            type: 'post',
            data: {
                group_name:$('#add-group-name').val()
            },
            dataType: 'json',
            success: function (data) {
                if(data.success==true){
                    location.href = '/admin/user-group-permission';
                }
            }
        });
    })
});