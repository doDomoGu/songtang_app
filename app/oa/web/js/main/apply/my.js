$(function(){
    $('#infoModal').on('show.bs.modal',function(e) {
        var btn = $(e.relatedTarget);
        var id = btn.data("id");

        $.ajax({
            url: '/apply/get-record',
            type: 'post',
            //async : false,
            dataType: 'json',
            data: {
                id: id
            },
            success: function (data) {
                if(data.result){
                    $('#infoContent .content').html(data.html);
                    $('.datepicker-x').datepicker(
                        {
                            dateFormat:'yy-mm-dd'
                        }
                    );
                }else{
                    $('#infoContent .errormsg-text').html(data.errormsg).show();

                }
            }
        });

    });




    $('.btn-op-del').click(function(){
        if(confirm('确认是否要撤销这个申请？')){
            var id = $(this).attr('data-id');

            $.ajax({
                url: '/apply/del',
                type: 'post',
                //async : false,
                dataType: 'json',
                data: {
                    id: id
                },
                success: function (data) {
                    if(data.result){
                        location.href = '/apply/my';
                    }else{
                        alert('撤销失败！刷新重试！');

                    }
                }
            });
        }else{
            return false;
        }
    })

    $('#main').on('click','.print-btn',function(){

        //$("#infoModal .modal-content").jqprint()

        var newWindow=window.open("/apply/print?id="+$(this).attr('data-id'),"_blank");
        //var docStr = $("#infoModal .modal-content").html();
        //newWindow.document.write('<html><head><link href="/assets/88e40878/css/bootstrap.css" rel="stylesheet">');
        //newWindow.document.write('<link href="/css/site.css" rel="stylesheet">');
        //newWindow.document.write('<link href="/css/main/apply/my.css" rel="stylesheet"><title></title></head><body>');
        //newWindow.document.write('<html><head><title></title></head><body>');
        //newWindow.document.write(docStr);
        //newWindow.document.write('</body></html>');


        //newWindow.document.close();
        //newWindow.print();
        //newWindow.close();


/*
        var headhtml = "<html><head>";
        //headhtml += '<link href="/css/site.css" rel="stylesheet">'+
        //    '<link href="/css/main/apply/my.css" rel="stylesheet">';
        headhtml +="<title></title></head><body>";
        var foothtml = "</body>";
        // 获取div中的html内容
        //var newhtml = document.all.item(printpage).innerHTML;
        // 获取div中的html内容，jquery写法如下
         //var newhtml= $("#" + printpage).html();
         var newhtml = $("#infoModal .modal-content").html();

        // 获取原来的窗口界面body的html内容，并保存起来
        var oldhtml = document.body.innerHTML;

        // 给窗口界面重新赋值，赋自己拼接起来的html内容
        document.body.innerHTML = headhtml + newhtml + foothtml;
        // 调用window.print方法打印新窗口
        window.print();

        // 将原来窗口body的html值回填展示
        document.body.innerHTML = oldhtml;
        return false;*/
    });


    $('#main').on('click','.print2-btn',function(){
        location.href = '/apply/finance-export?id='+$(this).attr('data-id');
    });

    $('.search_time').datepicker(
        {
            dateFormat:'yy-mm-dd'
        }
    );

    $('.search_category option').each(function(){
        var disabledArr = ['t1','t2','t3','t4'];
        if(disabledArr.indexOf($(this).val())>-1){
            $(this).attr('disabled','disabled');
        }
    });

    // $('#searchBtn').on('click',function(){
    //
    // })
});