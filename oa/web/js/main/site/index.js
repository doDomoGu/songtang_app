$(function () {
    $('#create-btn').click(function(){
        $('#createContent .errormsg-text').html('').hide();
        $.ajax({
            url: '/admin/task/create',
            type: 'post',
            //async : false,
            dataType: 'json',
            data: {
                title: $('#createContent .create-title').val(),
                area_id: $('#createContent .create-area-select').val()
            },
            success: function (data) {
                if(data.result){
                    location.href='/admin/task';
                }else{
                    $('#createContent .errormsg-text').html(data.errormsg).show();

                }
            }
        });
    });

    $('#createModal').on('show.bs.modal',function(e) {
        $('#createContent .errormsg-text').html('').hide();
        var btn = $(e.relatedTarget);
        /*var id = btn.data("id");
        var oldName = btn.data("old-name");
        $('#editContent .id-value').val(id);
        $('#editContent .old-name-text').html(oldName);*/

        $.ajax({
            url: '/apply/get-task',
            type: 'post',
            //async : false,
            dataType: 'json',
            data: {
                /*title: $('#createContent .create-title').val(),
                area_id: $('#createContent .create-area-select').val()*/
            },
            success: function (data) {
                if(data.result){
                    location.href='/admin/task';
                }else{
                    $('#createContent .errormsg-text').html(data.errormsg).show();

                }
            }
        });

    });


    $('#edit-btn').click(function(){
        $('#editContent .errormsg-text').html('').hide();
        $.ajax({
            url: '/setting/edit',
            type: 'post',
            //async : false,
            dataType: 'json',
            data: {
                id: $('#editContent .id-value').val(),
                name: $('#editContent .new-name').val(),
                type: 'area'
            },
            success: function (data) {
                if(data.result){
                    location.href='/setting/area';
                }else{
                    $('#editContent .errormsg-text').html(data.errormsg).show();

                }
            }
        });
    });


});