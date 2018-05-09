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


                        form_script_bind();


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

                        form_script_bind();

                        /*$('.datepicker-x').datepicker(
                            {
                                dateFormat:'yy-mm-dd'
                            }
                        );
                        $('.form_format').each(function () {
                            form_format_bind(this);
                        })*/
                        /*$('.form_format').each(function () {
                            var t = this;
                            var _for = $(t).attr('data-for');

                            var _type = $(t).attr('data-type');

                            var forObject = $('input[name="form_item['+_for+']');
                            if(forObject.length == 1){
                                forObject.on('input',function(){
                                    //$(t).val(intToChinese($(this).val()))
                                    $(t).val(convertCurrency($(this).val()))
                                })
                            }
                        })*/
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


    //代码如下所示：
    function convertCurrency(money) {
        //汉字的数字
        var cnNums = new Array('零', '壹', '贰', '叁', '肆', '伍', '陆', '柒', '捌', '玖');
        //基本单位
        var cnIntRadice = new Array('', '拾', '佰', '仟');
        //对应整数部分扩展单位
        var cnIntUnits = new Array('', '万', '亿', '兆');
        //对应小数部分单位
        var cnDecUnits = new Array('角', '分', '毫', '厘');
        //整数金额时后面跟的字符
        var cnInteger = '整';
        //整型完以后的单位
        var cnIntLast = '元';
        //最大处理的数字
        var maxNum = 999999999999999.9999;
        //金额整数部分
        var integerNum;
        //金额小数部分
        var decimalNum;
        //输出的中文金额字符串
        var chineseStr = '';
        //分离金额后用的数组，预定义
        var parts;
        if (money == '') { return ''; }
        money = parseFloat(money);
        if (money >= maxNum) {
            //超出最大处理数字
            return '';
        }
        if (money == 0) {
            chineseStr = cnNums[0] + cnIntLast + cnInteger;
            return chineseStr;
        }
        //转换为字符串
        money = money.toString();
        if (money.indexOf('.') == -1) {
            integerNum = money;
            decimalNum = '';
        } else {
            parts = money.split('.');
            integerNum = parts[0];
            decimalNum = parts[1].substr(0, 4);
        }
        //获取整型部分转换
        if (parseInt(integerNum, 10) > 0) {
            var zeroCount = 0;
            var IntLen = integerNum.length;
            for (var i = 0; i < IntLen; i++) {
                var n = integerNum.substr(i, 1);
                var p = IntLen - i - 1;
                var q = p / 4;
                var m = p % 4;
                if (n == '0') {
                    zeroCount++;
                } else {
                    if (zeroCount > 0) {
                        chineseStr += cnNums[0];
                    }
                    //归零
                    zeroCount = 0;
                    chineseStr += cnNums[parseInt(n)] + cnIntRadice[m];
                }
                if (m == 0 && zeroCount < 4) {
                    chineseStr += cnIntUnits[q];
                }
            }
            chineseStr += cnIntLast;
        }
        //小数部分
        if (decimalNum != '') {
            var decLen = decimalNum.length;
            console.log(decLen);
            for (var i = 0; i < decLen; i++) {
                var n = decimalNum.substr(i, 1);
                if (n != '0') {
                    chineseStr += cnNums[Number(n)] + cnDecUnits[i];
                }else if(i==0){
                    chineseStr += '零'
                }
            }
        }
        if (chineseStr == '') {
            chineseStr += cnNums[0] + cnIntLast + cnInteger;
        } else if (decimalNum == '') {
            chineseStr += cnInteger;
        }
        return chineseStr;
    }

    function form_script_bind() {
        $('.datepicker-x').datepicker(
            {
                dateFormat:'yy-mm-dd'
            }
        );
        $('.form_number').each(function(){
            var t = this;
            var _type = $(t).attr('data-type');

            if(_type == 'price'){//金额格式  两位小数
                $(t).on('input',function(){
                    var v = $(t).val();


                    v = v.replace(/[^\d.]/g,"");  //清除“数字”和“.”以外的字符
                    v = v.replace(/\.{2,}/g,"."); //只保留第一个. 清除多余的
                    v = v.replace(".","$#$").replace(/\./g,"").replace("$#$",".");
                    v = v.replace(/^(\-)*(\d+)\.(\d\d).*$/,'$1$2.$3');//只能输入两个小数
                    if(v.indexOf(".")< 0 && v !=""){//以上已经过滤，此处控制的是如果没有小数点，首位不能为类似于 01、02的金额
                        v = parseFloat(v);
                    }

                    $(t).val(v);


                    //$(t).val(intToChinese($(this).val()))
                    //$(t).val(convertCurrency($(this).val()))
                })



            }
        })



        $('.form_format').each(function () {
            var t = this;
            var _for = $(t).attr('data-for');

            var _type = $(t).attr('data-type');
            var forObject;

            if(_type == 'upper'){
                forObject = $('input[name="form_item['+_for+']');
                if(forObject.length == 1){
                    forObject.on('input',function(){
                        //$(t).val(intToChinese($(this).val()))
                        $(t).val(convertCurrency($(this).val()))
                    })
                }
            }else if(_type == 'table_sum'){
                var _table = _for.split('|')[0];
                var _col = _for.split('|')[1];
                forObject = $('input[name^="form_item['+_table+']"][name$="['+_col+']"]')

                if(forObject.length>0){
                    forObject.each(function(){
                        $(this).on('input',function(){
                            var _sum = 0;
                            forObject.each(function(){
                                _sum += $(this).val()!=''?parseFloat($(this).val()):0;
                            })
                            $(t).val(_sum)
                        })
                    })
                }
            }

        })



    }
});