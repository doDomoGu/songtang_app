$(function () {
    $('#create-btn').click(function(){
        $('#createContent .errormsg-text').html('').hide();
        var p_id = $('#createContent .position_p_id').val();
        $.ajax({
            url: '/setting/position-create',
            type: 'post',
            //async : false,
            dataType: 'json',
            data: {
                name: $('#createContent .create-name').val(),
                alias: $('#createContent .create-alias').val(),
                p_id: p_id
            },
            success: function (data) {
                if(data.result){
                    location.href='/setting/position?p_id='+p_id;
                }else{
                    $('#createContent .errormsg-text').html(data.errormsg).show();

                }
            }
        });
    });
});