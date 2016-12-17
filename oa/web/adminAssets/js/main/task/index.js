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

    $('#editModal').on('show.bs.modal',function(e) {
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