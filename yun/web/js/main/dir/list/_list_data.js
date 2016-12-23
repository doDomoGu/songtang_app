$('.list-checkbox').mouseenter(function(){
    $('#file_head ul li.head_col_filename').addClass('list-oncheck');
}).mouseleave(function(){
    $('#file_head ul li.head_col_filename').removeClass('list-oncheck');
});

$('#file_head ul ').on('click','li.head_col_filename:not(".list-oncheck"), li.head_col_filesize, li.head_col_uploadtime',function(){
    location.href = $(this).attr('data-url');
});


$('#list-main').on('mouseenter','.list-style',function(){
    $(this).find('.click_btns').show();
}).on('mouseleave','.list-style',function(){
    $(this).find('.click_btns').hide();
});


$('#list-main').on('click','.click_btns .downloadBtn',function(){
    if(confirm('确认是否要下载这个文件?')){
        //_this = $(this).parents('.info');
        /*_download_times = parseInt(_this.find('.download_times').html())+1;
        _this.find('.download_times').html(_download_times);*/
        download_file('/dir/download?id='+$(this).attr('data-id'));
        //location.href = '/dir/download?id='+$(this).attr('data-id');
    }
});

$('#list-main').on('click','.click_btns .deleteBtn',function(e){
    e.preventDefault();
    if(confirm('确认是否要删除这个文件（移入回收站）?')){
        location.href = $(this).attr('link');
    }
});

$('#list-main').on('click','.click_btns .previewBtn',function(){
    $.ajax({
        url: '/dir/download',
        type: 'get',
        data: {
            id:$(this).attr('data-id'),
            preview:true
        },
        success: function (data) {
            $('#previewContent').html(data);
            $('#previewModal').modal('show');
        }
    });
});