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




    $('.btn-op-del').click(function(){
        if(confirm('确认是否要撤销这个申请？')){
            var id = $(this).attr('data-id');

            $.ajax({
                url: '/apply/del',
                type: 'post',
                //async : false,
                dataType: 'json',
                data: {
                    id: id
                },
                success: function (data) {
                    if(data.result){
                        location.href = '/apply/my';
                    }else{
                        alert('撤销失败！刷新重试！');

                    }
                }
            });
        }else{
            return false;
        }
    })
});