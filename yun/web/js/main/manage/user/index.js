$(function(){
    $('#searchBtn').click(function(){
        $('#s_position_id').val($('#pos_id_div').html());
        $('#searchForm').submit();
    })
});