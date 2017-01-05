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
                area_id: $('#createContent .create-area-select').val(),
                business_id: $('#createContent .create-business-select').val(),
                category_id: $('#createContent .create-category-select').val()
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

    $('.complete-btn').on('click',function(){
        var id = $(this).attr('data-id');
        if(confirm('确认已完成设置？（确认后无法再返回设置）')){
            location.href = '/admin/task/set-complete?id='+id;
        }
    });

    /*$('#editModal').on('show.bs.modal',function(e) {
        $('#editContent .errormsg-text').html('').hide();
        var btn = $(e.relatedTarget);
        var id = btn.data("id");
        var oldName = btn.data("old-name");
        $('#editContent .id-value').val(id);
        $('#editContent .old-name-text').html(oldName);
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
    });*/


});