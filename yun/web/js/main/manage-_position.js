var p_id;
var p_id2;
$(function(){
    p_id = $('#s_position_id').val();
    $.ajax({
        url: '/manage/position-select-ajax',
        type: 'get',
        data: {
            p_id:p_id
        },
        //dataType:'json',
        success: function (data) {
            $('#pos-select-div').html(data);
        }
    });


    $('#pos-select-div').on('change','.pos-select-group',function(){
        p_id2 = $(this).val();

        if(p_id2>0){



            //$(this).nextAll().remove();

            $.ajax({
                url: '/manage/position-select-ajax',
                type: 'get',
                data: {
                    p_id:p_id2
                },
                //dataType:'json',
                success: function (data) {
                    $('#pos-select-div').html(data);
                }
            });
        }else{
            if($(this).index()>0)
                p_id2 = $(this).prev().val();
            $(this).nextAll().remove();
        }
        $('#pos_id_div').html(p_id2);
        $('#pos_id_input').val(p_id2);
        $('#pos_id_input').change();
    });
});

