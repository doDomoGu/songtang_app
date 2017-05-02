$(function () {
    $('#create-btn').click(function(){
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
        if (confirm('确认已完成设置？（确认后无法再返回设置）')) {
            location.href = '/admin/task/set-complete?id=' + id;

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

    })

    $('.create-category-select input').each(function(){
         var disabledArr = ['t1','t2','t3','t4'];
         if(disabledArr.indexOf($(this).val())>-1){
             $(this).attr('disabled','disabled');
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