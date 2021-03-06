$(function () {
    $('#create-submit-btn').click(function(){
        $('#createContent .errormsg-text').html('').hide();
        var category_ids = [];
        $('input[name="create-category-select[]"]:checked').each(function(){
            category_ids.push($(this).val());
        });

        var category_id = category_ids.join(',');

        $.ajax({
            url: '/admin/task/form-create',
            type: 'post',
            //async : false,
            dataType: 'json',
            data: {
                title: $('#createContent .create-title').val(),
                category_id: category_id
            },
            success: function (data) {
                if(data.result){
                    location.href='/admin/task/form';
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
            url: '/admin/task/form-edit',
            type: 'post',
            //async : false,
            dataType: 'json',
            data: {
                form_id: $('#editContent .edit-form_id').val(),
                title: $('#editContent .edit-title').val(),
                category_id: category_id
            },
            success: function (data) {
                if(data.result){
                    location.href='/admin/task/form';
                }else{
                    $('#editContent .errormsg-text').html(data.errormsg).show();

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

    /*$('.complete-btn').on('click',function() {
        var id = $(this).attr('data-id');
        if (confirm('确认已完成设置？（确认后无法再返回设置）')) {
            location.href = '/admin/task/set-complete?id=' + id;
        }
    });

    $('.complete2-btn').on('click',function() {
        var id = $(this).attr('data-id');
        if (confirm('改为未完成')) {
            location.href = '/admin/task/set-complete2?id=' + id;
        }
    });*/

    $('.del-btn').click(function(){
        var id = $(this).attr('data-id');
        if(confirm('确认要删除这个表单吗？表单和模板的关系也会解除！！')){
            $.ajax({
                url: '/admin/task/form-del',
                type: 'post',
                //async : false,
                dataType: 'json',
                data: {
                    form_id: id
                },
                success: function (data) {
                    if(data.result){
                        location.href='/admin/task/form';
                    }else{
                        alert('删除失败！');
                        //$('#createContent .errormsg-text').html(data.errormsg).show();
                    }
                }
            });
        }else{
            return false;
        }

    })


    $('.edit-btn').click(function(){
        var _id = $(this).attr('data-id');


        $.ajax({
            url: '/admin/task/form-get',
            type: 'post',
            //async : false,
            dataType: 'json',
            data: {
                id: _id
            },
            success: function (data) {
                if(data.result){
                    $('#editContent .edit-title').val(data.info.title);
                    $('#editContent .edit-form_id').val(_id);

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

    })



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
    });

    $('.complete-btn').on('click',function() {
        var id = $(this).attr('data-id');
        if (confirm('启用这个表单？')) {
            location.href = '/admin/task/form-set-complete?id=' + id;
        }
    });

    $('.complete2-btn').on('click',function() {
        var id = $(this).attr('data-id');
        if (confirm('暂停这个表单')) {
            location.href = '/admin/task/form-set-complete2?id=' + id;
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