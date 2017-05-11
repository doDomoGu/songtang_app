$('#list-main').on('mouseenter','.grid-style',function(){
    $(this).addClass('list-checked');
    $(this).addClass('list-item-hover');
}).on('mouseleave','.grid-style',function(){
    if($(this).find('.file-checkbox').prop('checked')==false){
        $(this).removeClass('list-checked');
    }
    $(this).removeClass('list-item-hover');
});


var _checkboxClickFlag = false;
$('#list-main').on('click','.file-checkbox',function(){
    _checkboxClickFlag = true;
    if($(this).prop('checked')){
        $(this).parents('.list-item').addClass('list-checked');
    }else{
        $(this).parents('.list-item').removeClass('list-checked');
    }
});


$('#list-main').on('click','.is-dir',function(){
    if(_checkboxClickFlag==false){
        location.href = '/dir?p_id='+$(this).attr('data-id');
    }else{
        _checkboxClickFlag = false;
    }


});
