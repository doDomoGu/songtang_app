/*$('.recycleBtn').modal('show');*/
$('.recycleBtn').click(function(e){
    e.preventDefault();
    if(confirm('确认是否要还原这个文件?')){
        window.location = $(this).attr('href');
    }
});

$('.recycleDeleteBtn').click(function(e){
    e.preventDefault();
    if(confirm('确认是否要彻底删除这个文件?(无法还原)')){
        window.location = $(this).attr('href');
    }
});

$('.recycleDeleteAllBtn').click(function(e){
    e.preventDefault();
    if(confirm('确认是否要清空回收站?(无法还原)')){
        window.location = $(this).attr('href');
    }
});