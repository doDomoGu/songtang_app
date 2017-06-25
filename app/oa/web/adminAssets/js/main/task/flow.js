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
                enable_transfer: $('#createContent .create-enable-transfer-select').val(),
                position: $('#createContent .create-position').val()
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


    $('.delete-all').click(function(){
        if(confirm('确定要更删除所有流程么？')){
            var _id = $('#createContent .task-id').val();
            $.ajax({
                url: '/admin/task/flow-delete-all',
                type: 'post',
                //async : false,
                dataType: 'json',
                data: {
                    id: _id
                },
                success: function (data) {
                    if(data.result){
                        location.href='/admin/task/flow?tid='+_id;
                    }else{
                        alert(data.errormsg);
                    }
                }
            });
        }
    });

    $('.ord-up-btn').click(function(){
        if(confirm('确定要把这个流程往上移么？')){
            var task_id = $('#createContent .task-id').val();
            var flow_id = $(this).attr('data-id');
            $.ajax({
                url: '/admin/task/flow-step-change',
                type: 'post',
                //async : false,
                dataType: 'json',
                data: {
                    task_id: task_id,
                    flow_id: flow_id,
                    action: 'up'
                },
                success: function (data) {
                    if(data.result){
                        location.href='/admin/task/flow?tid='+task_id;
                    }else{
                        alert(data.errormsg);
                    }
                }
            });
        }
    });


    $('.ord-down-btn').click(function(){
        if(confirm('确定要把这个流程往下移么？')){
            var task_id = $('#createContent .task-id').val();
            var flow_id = $(this).attr('data-id');
            $.ajax({
                url: '/admin/task/flow-step-change',
                type: 'post',
                //async : false,
                dataType: 'json',
                data: {
                    task_id: task_id,
                    flow_id: flow_id,
                    action: 'down'
                },
                success: function (data) {
                    if(data.result){
                        location.href='/admin/task/flow?tid='+task_id;
                    }else{
                        alert(data.errormsg);
                    }
                }
            });
        }
    });

    $('.del-btn').click(function(){
        if(confirm('确定要删除这个选项么？')){
            var task_id = $('#createContent .task-id').val();
            var flow_id = $(this).attr('data-id');
            $.ajax({
                url: '/admin/task/flow-del',
                type: 'post',
                //async : false,
                dataType: 'json',
                data: {
                    task_id: task_id,
                    flow_id: flow_id
                },
                success: function (data) {
                    if(data.result){
                        location.href='/admin/task/flow?tid='+task_id;
                    }else{
                        alert(data.errormsg);
                    }
                }
            });
        }
    });
});