$(function () {
    $('#create-btn').click(function(){
        $('#createContent .errormsg-text').html('').hide();
        $.ajax({
            url: '/setting/department-create',
            type: 'post',
            //async : false,
            dataType: 'json',
            data: {
                name: $('#createContent .create-name').val(),
                alias: $('#createContent .create-alias').val(),
                p_id: $('#createContent .department_p_id').val()
            },
            success: function (data) {
                if(data.result){
                    var p_id= $('#createContent .department_p_id').val();
                    if(p_id>0){
                        location.href='/setting/department?p_id='+p_id;
                    }else{
                        location.href='/setting/department';
                    }
                }else{
                    $('#createContent .errormsg-text').html(data.errormsg).show();

                }
            }
        });
    });

    $('#editModal').on('show.bs.modal',function(e) {
        $('#editContent .errormsg-text').html('').hide();
        $('#editContent .new-name').val('');
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
                type: 'department'
            },
            success: function (data) {
                if(data.result){
                    var p_id= $('#createContent .department_p_id').val();
                    if(p_id>0){
                        location.href='/setting/department?p_id='+p_id;
                    }else{
                        location.href='/setting/department';
                    }
                }else{
                    $('#editContent .errormsg-text').html(data.errormsg).show();

                }
            }
        });
    });

    $('.order-change').click(function(){
        if($(this).hasClass('disabled')){
            return false;
        }else{
            if(confirm('确定要更改排序么？')){
                var _id = $(this).attr('data-id');
                var _act = $(this).attr('data-act');
                $.ajax({
                    url: '/setting/change-ord',
                    type: 'post',
                    //async : false,
                    dataType: 'json',
                    data: {
                        id: _id,
                        type: 'department',
                        act: _act
                    },
                    success: function (data) {
                        if(data.result){
                            location.href='/setting/department';
                        }else{
                            alert(data.errormsg);
                        }
                    }
                });
            }
        }
    });

});