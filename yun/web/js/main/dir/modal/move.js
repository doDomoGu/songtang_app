$('#moveModalContent button.btn').click(function(){
    new_dir_id = $('#moveModalContent .move_dir_id_new').val();
    new_p_id = $('#moveModalContent .move_p_id_new').val();
    if(new_dir_id!='' || new_p_id !=''){
        _c = $('#list-main .file-checkbox:checked').length;
        //_c2 = $('#list-main .filetype .file-checkbox:checked').length;
        _c4 = $('#list-main .delete-enable .file-checkbox:checked').length;
        if(_c>0){
            if(_c!=_c4){
                $('#moveModalContent .move-error').html('<span style="color:red;">有文件不能移动！</span>');
            }else{
                if(confirm('确认要移动这些文件么')){
                    var file_ids = new Array();
                    $('#list-main .file-checkbox:checked').each(function(){
                        file_ids.push($(this).parents('.list-item').attr('data-id'));
                    });
                    /*console.log(file_ids);
                    return false;*/
                    $.ajax({
                        url: '/dir/move-file',
                        type: 'post',
                        data: {
                            new_dir_id:new_dir_id,
                            new_p_id:new_p_id,
                            file_ids:file_ids
                        },
                        dataType:'json',
                        success: function (data) {
                            if(data.result){
                                if(new_p_id>0){
                                    location.href='/dir?p_id='+new_p_id;
                                }else{
                                    location.href='/dir?dir_id='+new_dir_id;
                                }
                            }else{
                                $('#moveModalContent .move-error').html('<span style="color:red;">'+data.error+'</span>');
                            }
                        }
                    });
                }else{
                    return false;
                }
            }
        }else{
            $('#moveModalContent .move-error').html('<span style="color:red;">操作错误！</span>');
        }
    }else{
        $('#moveModalContent .move-error').html('<span style="color:red;">不能为空！</span>');
    }
});


//var p_id;
var p_id2;  //dir表中的 p_id
var p_id3;  //file表中的p_id
$(function(){
    //p_id = $('#s_position_id').val();
    $.ajax({
        url: '/dir/ajax-move-select-dir',
        type: 'get',
        data: {
            p_id:0
        },
        //dataType:'json',
        success: function (data) {
            $('.move_dir_route').html(data);
        }
    });


    $('.move_dir_route').on('change','.pos-select-group',function(){
        p_id2 = $(this).val();

        if(p_id2>0){



            //$(this).nextAll().remove();

            $.ajax({
                url: '/dir/ajax-move-select-dir',
                type: 'get',
                data: {
                    p_id:p_id2
                },
                //dataType:'json',
                success: function (data) {
                    $('.move_dir_route').html(data);
                }
            });
        }else{
            if($(this).index()>0)
                p_id2 = $(this).prev().val();
            $(this).nextAll().remove();
        }
        //$('#pos_id_div').html(p_id2);
        $('.move_dir_id_new').val(p_id2);
        $('.move_dir_id_new').change();

        $('.move_p_id_new').val('');
        $('.move_p_id_new').change();
    });

    $('.move_dir_route').on('change','.pos2-select-group',function(){
        p_id3 = $(this).val();

        if(p_id3>0){



            //$(this).nextAll().remove();

            $.ajax({
                url: '/dir/ajax-move-select-dir',
                type: 'get',
                data: {
                    p_id:p_id2,
                    p_id3:p_id3
                },
                //dataType:'json',
                success: function (data) {
                    $('.move_dir_route').html(data);
                }
            });
        }else{
            if($(this).index()>0)
                p_id3 = $(this).prev().val();
            $(this).nextAll().remove();
        }
        $('.move_p_id_new').val(p_id3);
        $('.move_p_id_new').change();
    });
});

