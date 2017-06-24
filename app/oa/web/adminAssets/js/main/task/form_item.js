$(function () {
    $('#create-btn').click(function(){
        $('#createContent .errormsg-text').html('').hide();
        var form_id =$('#createContent .form-id').val();
        $.ajax({
            url: '/admin/task/form-item-create',
            type: 'post',
            //async : false,
            dataType: 'json',
            data: {
                form_id : form_id,
                key: $('#createContent .create-key').val(),
                label: $('#createContent .create-label').val(),
                label_width: $('#createContent .create-label_width').val(),
                input_width: $('#createContent .create-input_width').val(),
                input_type: $('#createContent .create-input_type-select').val(),
                input_options: $('#createContent .create-input_options').val(),
                position: $('#createContent .create-position').val()
            },
            success: function (data) {
                if(data.result){
                    location.href='/admin/task/form-item?id='+form_id;
                }else{
                    $('#createContent .errormsg-text').html(data.errormsg).show();

                }
            }
        });
    });

    $('#editModal').on('show.bs.modal',function(e) {
        $('#editContent .errormsg-text').html('').hide();
        var btn = $(e.relatedTarget);
        var id = btn.data("id");
        var key = btn.data("key");
        var label = btn.data("label");
        var label_width = btn.data("label_width");
        var input_width = btn.data("input_width");
        var input_type = btn.data("input_type");
        var input_options = btn.data("input_options");
console.log(input_options);

        $('#editContent .edit-form-item-id').val(id);
        $('#editContent .create-key').val(key);
        $('#editContent .create-label').val(label);
        $('#editContent .create-label_width').val(label_width);
        $('#editContent .create-input_width').val(input_width);
        $('#editContent .create-input_type-select').val(input_type);
        $('#editContent .create-input_options').val(input_options);

    });


    $('#edit-submit-btn').click(function(){
        $('#editContent .errormsg-text').html('').hide();
        var form_id =$('#editContent .form-id').val();
        $.ajax({
            url: '/admin/task/flow-edit',
            type: 'post',
            //async : false,
            dataType: 'json',
            data: {
                form_id : form_id,
                item_id: $('#editContent .edit-form-item-id').val(),
                title: $('#editContent .create-title').val(),
                type: $('#editContent .create-type-select').val(),
                user_id: $('#editContent .create-user-select').val()
            },
            success: function (data) {
                if(data.result){
                    location.href='/admin/task/flow?tid='+tid;
                }else{
                    $('#editContent .errormsg-text').html(data.errormsg).show();
                }
            }
        });
    });

    $('.del-btn').click(function(){
        if(confirm('确定要删除这个选项么？')){
            var form_id = $('#createContent .form-id').val();
            var item_id = $(this).attr('data-id');
            $.ajax({
                url: '/admin/task/form-item-del',
                type: 'post',
                //async : false,
                dataType: 'json',
                data: {
                    form_id: form_id,
                    item_id: item_id
                },
                success: function (data) {
                    if(data.result){
                        location.href='/admin/task/form-item?id='+form_id;
                    }else{
                        alert(data.errormsg);
                    }
                }
            });
        }
    });


    $('.del-all-btn').click(function(){
        if(confirm('确定要删除所有选项么？')){
            var _id = $('#createContent .form-id').val();
            $.ajax({
                url: '/admin/task/form-item-del-all',
                type: 'post',
                //async : false,
                dataType: 'json',
                data: {
                    id: _id
                },
                success: function (data) {
                    if(data.result){
                        location.href='/admin/task/form-item?id='+_id;
                    }else{
                        alert(data.errormsg);
                    }
                }
            });
        }
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
                        type: 'area',
                        act: _act
                    },
                    success: function (data) {
                        if(data.result){
                            location.href='/setting/area';
                        }else{
                            alert(data.errormsg);
                        }
                    }
                });
            }
        }
    });



});