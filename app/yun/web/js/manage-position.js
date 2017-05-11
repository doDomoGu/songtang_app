var p_id;
var p_id2;


$('#pos-select-1').change(function(){
    p_id = $(this).val();
    if(p_id>0)
        location.href = 'position?p_id='+p_id;
    else
        location.href = 'position';
});

$('#pos-select-2').change(function(){
    p_id2 = $(this).val();
    if(p_id2>0)
        location.href = 'position?p_id='+p_id2;
    else{
        p_id = $('#pos-select-1').val();
        if(p_id>0)
            location.href = 'position?p_id='+p_id;
        else
            location.href = 'position';
    }

});

$('.viewUserBtn').click(function(){
   $('#form-p_id').val($(this).attr('p_id'));
   $('#view-user-form').submit();
});