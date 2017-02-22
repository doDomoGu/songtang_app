$('#createDirModalContent button.btn').click(function(){
    district_check = $('#createDirModalContent .district-check').val();
    industry_check = $('#createDirModalContent .industry-check').val();
    _dirname = $('#createDirModalContent .dirname').val();
    /*console.log(_dirname);*/
    if(_dirname!=''){
        _dir_id = $('#var_dir_id').val();
        _p_id = $('#var_p_id').val();
        $.ajax({
            url: '/dir/save',
            type: 'post',
            data: {
                dir_id:_dir_id,
                filename_real:_dirname,
                filename:_dirname,
                filetype:0,
                filesize:0,
                district_check:district_check,
                industry_check:industry_check,
                flag:1,
                p_id:_p_id
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
                    $('#createDirModalContent .create-dir-error').html('<span style="color:red;">'+data.msg+'</span>');
                }
            }
        });
    }else{
        $('#createDirModalContent .create-dir-error').html('<span style="color:red;">文件夹名不能为空！</span>');
    }
});