var dir_id;
var dir_id2;


$('#dir-select-1').change(function(){
    dir_id = $(this).val();
    if(dir_id>0)
        location.href = 'dir?dir_id='+dir_id;
    else
        location.href = 'dir';
});

$('#dir-select-2').change(function(){
    dir_id2 = $(this).val();
    if(dir_id2>0)
        location.href = 'dir?dir_id='+dir_id2;
    else{
        dir_id = $('#dir-select-1').val();
        if(dir_id>0)
            location.href = 'dir?dir_id='+dir_id;
        else
            location.href = 'dir';
    }

});