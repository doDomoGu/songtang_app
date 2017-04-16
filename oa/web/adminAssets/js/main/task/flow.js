$(function () {
    $('#create-btn').click(function(){
        $('#createContent .errormsg-text').html('').hide();
        var tid =$('#createContent .task-id').val();
        $.ajax({
            url: '/admin/task/flow-create',
            type: 'post',
            //async : false,
            dataType: 'json',
            data: {
                tid : tid,
                title: $('#createContent .create-title').val(),
                type: $('#createContent .create-type-select').val(),
                user_id: $('#createContent .create-user-select').val(),
                enable_transfer: $('#createContent .create-enable-transfer-select').val()
            },
            success: function (data) {
                if(data.result){
                    location.href='/admin/task/flow?tid='+tid;
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
        var title = btn.data("title");
        var type = btn.data("type");
        var user_id = btn.data("user-id");
        if(user_id==0){
            user_id='';
        }
        $('#editContent .edit-flow-id').val(id);
        $('#editContent .create-title').val(title);
        $('#editContent .create-type-select').val(type);
        $('#editContent .create-user-select').val(user_id);
    });


    $('#edit-btn').click(function(){
        $('#editContent .errormsg-text').html('').hide();
        var tid =$('#editContent .task-id').val();
        $.ajax({
            url: '/admin/task/flow-edit',
            type: 'post',
            //async : false,
            dataType: 'json',
            data: {
                tid : tid,
                flow_id: $('#editContent .edit-flow-id').val(),
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


    $('#editRelationModal').on('show.bs.modal',function(e){
        $('#editRelationContent .errormsg-text').html('').hide();
        var btn = $(e.relatedTarget);
        var id = btn.data("id");
        var flag = true;
        $.ajax({
            url: '/setting/get-info',
            type: 'post',
            async : false,
            dataType: 'json',
            data: {
                id: id,
                type: 'area'
            },
            success: function (data) {
                if(data.result){
                    $('#editRelationContent .name-text').html(data.info.name);
                    $('#editRelationContent .id-value').val(data.info.id);
                }else{
                    $('#editRelationContent .errormsg-text').html(data.errormsg).show();
                    flag = false;
                }
            }
        });
        if(flag){
            $.ajax({
                url: '/setting/get-relation-items',
                type: 'post',
                async : false,
                dataType: 'json',
                data: {
                    type:'area'
                },
                success: function (data) {
                    if(data.result){
                        var html = '';
                        for(var i in data.items){
                            html += '<label><input name="relationCheck" value="'+data.items[i].id+'" type="checkbox">'+data.items[i].name+'</label> ';
                        }
                        $('.relation-checkboxlist').html(html);
                        //location.href='/dept-setting/area';
                    }else{
                        $('#editRelationContent .errormsg-text').html(data.errormsg).show();
                        flag = false;
                    }
                }
            });
        }



        $.ajax({
            url: '/setting/get-relation-check',
            type: 'post',
            async : false,
            dataType: 'json',
            data: {
                id: id,
                type : 'area'
            },
            success: function (data) {
                if(data.result){
                    $('#editRelationContent input[name="relationCheck"]').each(function(index, element) {
                        if($.inArray(parseInt($(this).val()),data.check)>-1){
                            $(this).prop('checked',true);
                        }
                    });
                }else{
                    $('#editRelationContent .errormsg-text').html(data.errormsg).show();

                }
            }
        });
    });


    $('#edit-relation-btn').click(function(){
        var checks = [];
        $('#editRelationContent input[name="relationCheck"]:checked').each(function(index, element) {
            checks.push($(this).val());
        });

        $('#editRelationContent .errormsg-text').html('').hide();
        $.ajax({
            url: '/setting/edit-relation-check',
            type: 'post',
            //async : false,
            dataType: 'json',
            data: {
                id: $('#editRelationContent .id-value').val(),
                checks: checks,
                type: 'area'
            },
            success: function (data) {
                if(data.result){
                    location.href='/setting/area';
                }else{
                    $('#editRelationContent .errormsg-text').html(data.errormsg).show();

                }
            }
        });
    });
});