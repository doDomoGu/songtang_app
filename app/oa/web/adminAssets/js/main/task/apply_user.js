$(function () {
    $('.del-btn').click(function(){
        var tid = $(this).attr('data-task_id');
        var id = $(this).attr('data-id');
        location.href = '/admin/task/apply-user-del?id='+id+'&tid='+tid;
    });






    $('#create-submit-btn').click(function(){
        $('#createContent .errormsg-text').html('').hide();
        var tid =$('#createContent .task-id').val();
        $.ajax({
            url: '/admin/task/apply-user-add',
            type: 'post',
            //async : false,
            dataType: 'json',
            data: {
                tid : tid,
                district_id :$('#createContent .create-district-select').val(),
                industry_id :$('#createContent .create-industry-select').val(),
                company_id :$('#createContent .create-company-select').val(),
                department_id :$('#createContent .create-department-select').val(),
                position_id :$('#createContent .create-position-select').val()
            },
            success: function (data) {
                if(data.result){
                    location.href='/admin/task/apply-user?tid='+tid;
                }else{
                    $('#createContent .errormsg-text').html(data.errormsg).show();

                }
            }
        });
    });


    $('.edit-btn').click(function(){
        $('#editContent .edit-id').val($(this).attr('data-id'));
        $('#editContent .edit-district-select').val($(this).attr('district_id'));
        $('#editContent .edit-industry-select').val($(this).attr('industry_id'));
        $('#editContent .edit-company-select').val($(this).attr('company_id'));
        $('#editContent .edit-department-select').val($(this).attr('department_id'));
        $('#editContent .edit-position-select').val($(this).attr('position_id'));

        $('#editModal').modal('show');
    })


    $('#edit-submit-btn').click(function(){
        $('#editContent .errormsg-text').html('').hide();
        var edit_id =$('#editContent .edit-id').val();
        $.ajax({
            url: '/admin/task/apply-user-edit',
            type: 'post',
            //async : false,
            dataType: 'json',
            data: {
                edit_id : edit_id,
                district_id :$('#editContent .edit-district-select').val(),
                industry_id :$('#editContent .edit-industry-select').val(),
                company_id :$('#editContent .edit-company-select').val(),
                department_id :$('#editContent .edit-department-select').val(),
                position_id :$('#editContent .edit-position-select').val()
            },
            success: function (data) {
                if(data.result){
                    location.href='/admin/task/apply-user?tid='+$('#createContent .task-id').val();
                }else{
                    $('#editContent .errormsg-text').html(data.errormsg).show();

                }
            }
        });
    });

    /*$('.submit-btn').click(function(){
        $('.errmsg').html('').hide();
        var tid =$('.task-id').val();
        var userids = [];
        $('input[name="user_check[]"]:checked').each(function(){
            userids.push($(this).val());
        });
        var user_id = userids.join(',');

        $.ajax({
            url: '/admin/task/apply-user-add2',
            type: 'post',
            //async : false,
            dataType: 'json',
            data: {
                tid : tid,
                user_id : user_id
            },
            success: function (data) {
                if(data.result){
                    location.href='/admin/task/apply-user?tid='+tid;
                }else{
                    $('#createContent .errormsg-text').html(data.errormsg).show();

                }
            }
        });
    });

    $('#checkAll').click(function(){
       if($(this).prop('checked')==true){
           $('input[name="user_check[]"]').each(function(){
               $(this).prop('checked',true);
           });
       }else{
           $('input[name="user_check[]"]').each(function(){
               $(this).prop('checked',false);
           });
       }
    });*/
});