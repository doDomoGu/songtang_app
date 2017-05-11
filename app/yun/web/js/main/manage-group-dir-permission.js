var dir_id;
var dir_id2;
var group_id;
var _url;

$('#dir-select-1').change(function(){
    dir_id = $(this).val();
    group_id = $('#group_id').val();
    _url = 'group-dir-permission?id='+group_id;
    if(dir_id>0)
         _url = _url +'&dir_id='+dir_id;
    location.href = _url;
});

$('#dir-select-2').change(function(){
    dir_id2 = $(this).val();
    group_id = $('#group_id').val();
    _url = 'group-dir-permission?id='+group_id;
    if(dir_id2>0)
        _url = _url +'&dir_id='+dir_id2;
    else{
        dir_id = $('#dir-select-1').val();
        if(dir_id>0)
            _url = _url +'&dir_id='+dir_id;
    }
    location.href = _url;

});

$('.row-check').click(function(){
    pid = $(this).attr('data-pid');
    if($(this).prop('checked')){
        $('.pm-'+pid).prop('checked',true);
    }else{
        $('.pm-'+pid).prop('checked',false);
    }
});

$('.column-check').click(function(){
    pid = $(this).attr('data-pid');
    permission = $(this).attr('data-permission');
    childids = $('#p-'+pid+'-childs').val().split(',');
    for(i in childids){
        $('#pm-'+childids[i]+'-'+permission).prop('checked',$(this).prop('checked'));
    }

    /*if($(this).prop('checked')){
        for(i in childids)
        $('.pm-'+pid).prop('checked',true);
    }else{
        $('.pm-'+pid).prop('checked',false);
    }*/
});