$('#editModalContent button.btn').click(function(){
    _filename_new = $('#editModalContent .filename_new').val();
    _file_id = $('#editModalContent .file_id').val();
    _dir_id = $('#var_dir_id').val();
    _p_id = $('#var_p_id').val();
    /*console.log(_dirname);*/
    if(_filename_new!=''){
        $.ajax({
            url: '/dir/edit-filename',
            type: 'post',
            data: {
                filename_new:_filename_new,
                file_id:_file_id
            },
            dataType:'json',
            success: function (data) {
                if(data.result){
                    if(_dir_id>0){
                        location.href='/dir?dir_id='+_dir_id;
                    }
                    if(_p_id>0){
                        location.href='/dir?p_id='+_p_id;
                    }
                }else{
                    $('#editModalContent .edit-error').html('<span style="color:red;">'+data.error+'</span>');
                }
            }
        });
    }else{
        $('#editModalContent .edit-error').html('<span style="color:red;">不能为空！</span>');
    }
});