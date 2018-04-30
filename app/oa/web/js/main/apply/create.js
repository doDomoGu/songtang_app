$(function(){
    $('.task-preview').html('').hide();
    $('.task-select').on('change',function(e) {
        var task_id = $(this).val();
        var category_id = $('#applycreateform-task_category').val();
        $('.task-preview').html('').hide();

        $('#applycreateform-form_id').html('<option value="">==请选择==</option>');
        $('.form-content').html('').hide();
        if(task_id>0){
            $.ajax({
                url: '/apply/get-task-preview',
                type: 'post',
                //async : false,
                dataType: 'json',
                data: {
                    task_id:task_id,
                    category_id:category_id
                },
                success: function (data) {
                    if(data.result){
                        $('.task-preview').html(data.html).show();

                        $('#applycreateform-form_id').html(data.formSelectHtml);
                        if(data.formContentHtml!='') {
                            $('.form-content').html(data.formContentHtml).show();
                        }

                        $('.datepicker-x').datepicker(
                            {
                                dateFormat:'yy-mm-dd'
                            }
                        );
                        $('.form_format').each(function () {
                            var t = this;
                            var _for = $(t).attr('data-for');

                            var _type = $(t).attr('data-type');

                            var forObject = $('input[name="form_item['+_for+']');
                            if(forObject.length == 1){
                                forObject.on('input',function(){
                                    $(t).val(intToChinese($(this).val()))
                                })
                            }
                        })
                        /*$('.datepicker-x').datetimepicker(
                            {
                                language:'cn',
                                layout: '{picker}{input}',
                                options: {
                                    placeholder: '选择日期'
                                },
                                pluginOptions:{
                                    format :'yyyy-mm-dd',
                                    startDate:'2014-01-01',
                                    todayHighlight:true,
                                    minView:"month",
                                    autoclose:1
                                }
                            }
                        );*/
                    }else{
                        $('.task-preview').html(data.errormsg).show();

                    }
                }
            });
        }
    });

    if($('#applycreateform-task_category').html()!=undefined){
        $('#applycreateform-task_category option').each(function(){
            if($(this).val()[0] == 't'){
                $(this).attr('disabled',true);
            }
        })

        $('#applycreateform-task_category').on('change',function(){
            $('#applycreateform-task_id').html('<option value="">==请选择==</option>');
            $('.task-preview').html('').hide();
            $('#applycreateform-form_id').html('<option value="">==请选择==</option>');
            $('.form-content').html('').hide();


            $.ajax({
                url: '/apply/get-task-list',
                type: 'post',
                //async : false,
                dataType: 'json',
                data: {
                    task_category:$(this).val()
                },
                success: function (data) {
                    if(data.result){
                        $('#applycreateform-task_id').html(data.html);
                    }else{
                        //alert(data.errormsg);
                        //$('.task-preview').html(data.errormsg).show();

                    }
                }
            });
        })
    }

    $('.form-select').on('change',function(){
        var form_id = $(this).val();
        $('.form-content').html('').hide();
        if(form_id>0){
            $.ajax({
                url: '/apply/get-form-preview',
                type: 'post',
                //async : false,
                dataType: 'json',
                data: {
                    form_id:form_id
                },
                success: function (data) {
                    if(data.result){
                        $('.form-content').html(data.html).show();
                        $('.datepicker-x').datepicker(
                            {
                                dateFormat:'yy-mm-dd'
                            }
                        );
                        $('.form_format').each(function () {
                            var t = this;
                            var _for = $(t).attr('data-for');

                            var _type = $(t).attr('data-type');

                            var forObject = $('input[name="form_item['+_for+']');
                            if(forObject.length == 1){
                                forObject.on('input',function(){
                                    $(t).val(intToChinese($(this).val()))
                                })
                            }
                        })
                    }else{
                        //$('.task-preview').html(data.errormsg).show();

                    }
                }
            });
        }
    });

    $('#applycreateform-task_category').change();


    //$('#apply-create-form').on('submit',function(){
    $('#create-submit').on('click',function(){
        var task_form = $('#apply-create-form').find('.task-form-section');
        var alertFlag = false; //是否弹出表格内容必填的错误提示
        if(task_form.length>0){
            for(var i=0;i<task_form.length;i++){
                var form_table = $(task_form[i]).find('li.type-6');
                if(form_table.length>0){
                    for(var j=0;j<form_table.length;j++){
                        var label_length = $(form_table[j]).find('.table-item-label').length;
                        if(label_length>0){
                            var input_item = $(form_table[j]).find('.table-item-input');
                            var input_value_count = 0;
                            var input_value = '';
                            var input_item_value = '';
                            for(var k=0;k<input_item.length;k++){
                                if($(input_item[k]).find('input').length>0){
                                    input_value = $(input_item[k]).find('input').val();
                                }else if($(input_item[k]).find('select').length>0){
                                    input_value = $(input_item[k]).find('select').val();
                                }

                                if($(input_item[k]).find('select.table-item-select-item').length>0){
                                    input_item_value = $(input_item[k]).find('select.table-item-select-item').val();
                                    console.log(input_item_value);
                                }

                                if(input_value){
                                    input_value_count++;
                                }


                                if((k+1)%label_length===0){
                                    if(input_value_count>0 && input_value_count<label_length){
                                        if(input_item_value==''){
                                            alertFlag = true;
                                            break;
                                        }
                                    }
                                    input_item_value = '';
                                    input_value_count = 0;
                                    /*

                                    if(alertFlag){
                                        break;
                                    }*/
                                }
                            }
                        }
                    }
                }
            }
        }
        if(alertFlag){
            alert('"申请表表单"中，每有一个"项目"就必须填写或者选择内容！');
            return false;
        }
    })


    function intToChinese ( str ) {
        str = str+'';
        var len = str.length-1;
        var idxs = ['','十','百','千','万','十','百','千','亿','十','百','千','万','十','百','千','亿'];
        var num = ['零','壹','贰','叁','肆','伍','陆','柒','捌','玖'];
        return str.replace(/([1-9]|0+)/g,function( $, $1, idx, full) {
            var pos = 0;
            if( $1[0] != '0' ){
                pos = len-idx;
                if( idx == 0 && $1[0] == 1 && idxs[len-idx] == '十'){
                    return idxs[len-idx];
                }
                return num[$1[0]] + idxs[len-idx];
            } else {
                var left = len - idx;
                var right = len - idx + $1.length;
                if( Math.floor(right/4) - Math.floor(left/4) > 0 ){
                    pos = left - left%4;
                }
                if( pos ){
                    return idxs[pos] + num[$1[0]];
                } else if( idx + $1.length >= len ){
                    return '';
                }else {
                    return num[$1[0]]
                }
            }
        });
    }
});