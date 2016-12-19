$(function(){
    $('#infoModal').on('show.bs.modal',function(e) {
        var btn = $(e.relatedTarget);
        var id = btn.data("id");

        $.ajax({
            url: '/apply/get-record',
            type: 'post',
            //async : false,
            dataType: 'json',
            data: {
                id: id
            },
            success: function (data) {
                if(data.result){
                    $('#infoContent .content').html(data.html);
                }else{
                    $('#infoContent .errormsg-text').html(data.errormsg).show();

                }
            }
        });

    });
});