$(function () {
    $('#area-select').on('change',function(){
        var aid = $(this).val();
        var href = '/structure';
        if(aid>0)
            href = href+'?aid='+aid;
        location.href = href;
    });

    $('#business-select').on('change',function(){
        var aid = $('#area-select').val();
        var bid = $(this).val();
        var href = '/structure?aid='+aid;
        if(bid>0)
            href = href+'&bid='+bid;
        location.href = href;
    });

    $('#addModal').on('show.bs.modal',function(e) {
        var flag = true;
        $('#addContent .errormsg-text').html('').hide();
        $('#addContent .aname-text').html('');
        $('#addContent .bname-text').html('');
        $('#addContent .pname-text').html('');
        $('#addContent .new-cid-select').html('').show();
        $('#add-btn').attr('disabled',false);
        var btn = $(e.relatedTarget);
        var aid = btn.data("aid");
        var bid = btn.data("bid");
        var p_id = btn.data("p_id");

        $('#addContent .aid-value').val(aid);
        $('#addContent .bid-value').val(bid);
        $('#addContent .p_id-value').val(p_id);
        //$('#addContent .pname-text').html(pname);
        if(p_id>0)
            $('#addModal .modal-title').html('添加子部门');
        else
            $('#addModal .modal-title').html('添加部门');
        //var type = btn.data("type");

        $.ajax({
            url: '/setting/get-info',
            type: 'post',
            async : false,
            dataType: 'json',
            data: {
                id: aid,
                type: 'area'
            },
            success: function (data) {
                if(data.result){
                    $('#addContent .aname-text').html(data.info.name);
                }else{
                    $('#addContent .errormsg-text').html(data.errormsg).show();
                    flag = false;
                }
            }
        });

        if(flag){
            $.ajax({
                url: '/setting/get-info',
                type: 'post',
                async : false,
                dataType: 'json',
                data: {
                    id: bid,
                    type: 'business'
                },
                success: function (data) {
                    if(data.result){
                        $('#addContent .bname-text').html(data.info.name);
                    }else{
                        $('#addContent .errormsg-text').html(data.errormsg).show();
                        flag = false;
                    }
                }
            });

            if(flag){
                if(p_id>0){
                    $.ajax({
                        url: '/setting/get-info',
                        type: 'post',
                        async : false,
                        dataType: 'json',
                        data: {
                            id: p_id,
                            type: 'department'
                        },
                        success: function (data) {
                            if(data.result){
                                $('#addContent .pname-text').html(data.info.name);
                            }else{
                                $('#addContent .errormsg-text').html(data.errormsg).show();
                                flag = false;
                            }
                        }
                    });
                }else{
                    $('#addContent .pname-text').html('---');
                }

                if(flag){
                    $.ajax({
                        url: '/structure/get-items2',
                        type: 'post',
                        async : false,
                        dataType: 'json',
                        data: {
                            aid:aid,
                            bid:bid,
                            p_id:p_id
                        },
                        success: function (data) {
                            if(data.result){
                                var html = '';
                                for(var i in data.items){
                                    html += '<option value="'+data.items[i].id+'" >'+data.items[i].name+'</option>';
                                }
                                $('#addContent .new-did-select').show();
                                $('#addContent .new-did-select').html(html);
                            }else{
                                $('#addContent .new-did-select').hide();
                                $('#addContent .errormsg-text').html(data.errormsg).show();
                                flag = false;
                            }
                        }
                    });
                }
            }
        }

        if(flag==false){
            $('#add-btn').attr('disabled',true);
        }

    });

    $('#add-btn').on('click',function(){
        aid = $('#addContent .aid-value').val();
        bid = $('#addContent .bid-value').val();
        new_did = $('#addContent .new-did-select').val();

        $.ajax({
            url: '/structure/add',
            type: 'post',
            async : false,
            dataType: 'json',
            data: {
                aid:aid,
                bid:bid,
                new_did:new_did
            },
            success: function (data) {
                if(data.result){
                    location.href = location.href;
                }else{
                    $('#addContent .errormsg-text').html(data.errormsg).show();
                }
            }
        });
    })
});