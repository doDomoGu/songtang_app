$(function () {
    $('#create-submit-btn').click(function(){
        $('#createContent .errormsg-text').html('').hide();
        var category_ids = [];
        $('input[name="create-category-select[]"]:checked').each(function(){
            category_ids.push($(this).val());
        });

        var category_id = category_ids.join(',');

        $.ajax({
            url: '/admin/task/create',
            type: 'post',
            //async : false,
            dataType: 'json',
            data: {
                title: $('#createContent .create-title').val(),
                district_id: $('#createContent .create-district-select').val(),
                industry_id: $('#createContent .create-industry-select').val(),
                company_id: $('#createContent .create-company-select').val(),
                category_id: category_id,
                department_id: $('#createContent .create-department-select').val()
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


    $('#edit-submit-btn').click(function(){
        $('#editContent .errormsg-text').html('').hide();
        var category_ids = [];
        $('input[name="edit-category-select[]"]:checked').each(function(){
            category_ids.push($(this).val());
        });

        var category_id = category_ids.join(',');

        $.ajax({
            url: '/admin/task/edit',
            type: 'post',
            //async : false,
            dataType: 'json',
            data: {
                task_id: $('#editContent .edit-task_id').val(),
                title: $('#editContent .edit-title').val(),
                category_id: category_id
            },
            success: function (data) {
                if(data.result){
                    location.href='/admin/task';
                }else{
                    $('#editContent .errormsg-text').html(data.errormsg).show();

                }
            }
        });
    });

    $('#edit-form-submit-btn').click(function(){
        $('#editFormContent .errormsg-text').html('').hide();
        var form_ids = [];
        $('input[name="edit-form-select[]"]:checked').each(function(){
            form_ids.push($(this).val());
        });

        var form_id = form_ids.join(',');

        $.ajax({
            url: '/admin/task/task-form-edit',
            type: 'post',
            //async : false,
            dataType: 'json',
            data: {
                task_id: $('#editFormContent .edit-task_id').val(),
                form_id: form_id
            },
            success: function (data) {
                if(data.result){
                    location.href='/admin/task';
                }else{
                    $('#editFormContent .errormsg-text').html(data.errormsg).show();

                }
            }
        });
    });

    $('#checkall').click(function(){
        if($(this).prop('checked')){
            $('input[name="create-category-select[]"]').prop('checked',true);
        }else{
            $('input[name="create-category-select[]"]').prop('checked',false);
        }

        //if()
    })

    $('.complete-btn').on('click',function() {
        var id = $(this).attr('data-id');
        if (confirm('启用这个模板？')) {
            location.href = '/admin/task/set-complete?id=' + id;
        }
    });

    $('.complete2-btn').on('click',function() {
        var id = $(this).attr('data-id');
        if (confirm('暂停这个模板？x')) {
            location.href = '/admin/task/set-complete2?id=' + id;
        }
    });

    $('.del-btn').click(function(){
        var task_id = $(this).attr('data-id');
        if(confirm('确认要删除这个任务表（模板）吗？')){
            $.ajax({
                url: '/admin/task/delete',
                type: 'post',
                //async : false,
                dataType: 'json',
                data: {
                    id: task_id
                },
                success: function (data) {
                    if(data.result){
                        location.href='/admin/task';
                    }else{
                        alert('删除失败！');
                        //$('#createContent .errormsg-text').html(data.errormsg).show();
                    }
                }
            });
        }else{
            return false;
        }

    });


    $('.edit-btn').click(function(){
        var task_id = $(this).attr('data-id');


        $.ajax({
            url: '/admin/task/get',
            type: 'post',
            //async : false,
            dataType: 'json',
            data: {
                id: task_id
            },
            success: function (data) {
                if(data.result){
                    $('#editContent .edit-title').val(data.info.title);
                    $('#editContent .edit-task_id').val(task_id);

                    var _cate_select = data.info.category_ids.split(',');

                    $('#editContent .edit-category-select input[name="edit-category-select[]"]').prop('checked',false);
                    $('#editContent .edit-category-select input[name="edit-category-select[]"]').each(function () {
                        if(_cate_select.indexOf($(this).val())>-1){
                            $(this).prop('checked',true);
                        }
                    })

                    $('#editModal').modal('show');
                }else{
                    alert(data.errormsg);
                    return false;
                    //$('#createContent .errormsg-text').html(data.errormsg).show();
                }
            }
        });

    });


    $('.edit-form-btn').click(function(){
        var task_id = $(this).attr('data-id');


        $.ajax({
            url: '/admin/task/task-form-get',
            type: 'post',
            //async : false,
            dataType: 'json',
            data: {
                id: task_id
            },
            success: function (data) {
                if(data.result){
                    $('#editFormContent .edit-title').val(data.info.title);
                    $('#editFormContent .edit-task_id').val(task_id);

                    var _form_select = data.info.form_ids.split(',');

                    $('#editFormContent .edit-form-select input[name="edit-form-select[]"]').prop('checked',false);
                    $('#editFormContent .edit-form-select input[name="edit-form-select[]"]').each(function () {
                        if(_form_select.indexOf($(this).val())>-1){
                            $(this).prop('checked',true);
                        }
                    })

                    $('#editFormModal').modal('show');
                }else{
                    alert(data.errormsg);
                    return false;
                    //$('#createContent .errormsg-text').html(data.errormsg).show();
                }
            }
        });

    });


    $('.create-category-select input').each(function(){
         var disabledArr = ['t1','t2','t3','t4'];
         if(disabledArr.indexOf($(this).val())>-1){
             $(this).attr('disabled','disabled');
         }
    });

    $('.edit-category-select input').each(function(){
        var disabledArr = ['t1','t2','t3','t4'];
        if(disabledArr.indexOf($(this).val())>-1){
            $(this).attr('disabled','disabled');
        }
    });


    $('#edit-checkall').click(function(){
        if($(this).prop('checked')){
            $('input[name="edit-category-select[]"]').prop('checked',true);
        }else{
            $('input[name="edit-category-select[]"]').prop('checked',false);
        }

        //if()
    })

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



    $('#search-btn').click(function(){
        var par = '';
        var page = $('.pagination li.active a').html();
        if(page>1){
            par += 'page='+page;
        }

        $('.search_cond').each(function() {
            if (par != '') {
                par += '&';
            }
            par += this.name + '=' + this.value;
        });
        location.href = '/admin/task/index?'+par;

        /*

        $('#editContent .errormsg-text').html('').hide();
        var category_ids = [];
        $('input[name="edit-category-select[]"]:checked').each(function(){
            category_ids.push($(this).val());
        });

        var category_id = category_ids.join(',');

        $.ajax({
            url: '/admin/task/edit',
            type: 'post',
            //async : false,
            dataType: 'json',
            data: {
                task_id: $('#editContent .edit-task_id').val(),
                title: $('#editContent .edit-title').val(),
                category_id: category_id
            },
            success: function (data) {
                if(data.result){
                    location.href='/admin/task';
                }else{
                    $('#editContent .errormsg-text').html(data.errormsg).show();

                }
            }
        });*/
    });

    $('.copy-btn').click(function(){
        $('.copy-task_id').val($(this).attr('data-id'));
        $('.copy-title').val($(this).attr('data-title'));
        $('#copy-old-title').html($(this).attr('data-title'));
        $('#copyModal').modal('show');
    });


    $('#copy-submit-btn').click(function(){
        $('#copyContent .errormsg-text').html('').hide();


        var copy_from_id = $('.copy-task_id').val();
        var new_title = $('.copy-title').val();

        $.ajax({
            url: '/admin/task/copy',
            type: 'post',
            //async : false,
            dataType: 'json',
            data: {
                copy_from_id: copy_from_id,
                new_title: new_title
            },
            success: function (data) {
                if(data.result){
                    location.href='/admin/task';
                }else{
                    $('#copyContent .errormsg-text').html(data.errormsg).show();

                }
            }
        });
    });

    $('.export-btn').on('click',function(){
        location.href = '/admin/task/export';
    })


});