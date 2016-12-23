var progress_html = '';
var filename_list = [];
var _dir_id = $('#var_dir_id').val();
var _p_id = $('#var_p_id').val();
var _dir_route = $('#var_dir_route').val();
var qiniuDomain = $('#qiniuDomain').val();
var pickfileId = $('#pickfileId').val();
var fileurlId = $('#fileurlId').val();
var uploader = Qiniu.uploader({
    runtimes: 'html5,flash,html4',    //上传模式,依次退化
    browse_button: pickfileId,       //上传选择的点选按钮，**必需**
    uptoken_url: '/dir/get-uptoken',
    //uptoken_url: '/dir/get-uptoken?saveKey=file:'+_dir_route+'$(year)$(mon)$(day)$(hour)$(min)$(sec)$(ext)',
    //uptoken_url: '/dir/get-uptoken?saveKey=file:'+_dir_route+'$(key)$(ext)',
    //Ajax请求upToken的Url，**强烈建议设置**（服务端提供）
    // uptoken : '<Your upload token>',
    //若未指定uptoken_url,则必须指定 uptoken ,uptoken由其他程序生成
    unique_names: false,
    //unique_names: true,
    // 默认 false，key为文件名。若开启该选项，SDK会为每个文件自动生成key（文件名）
    save_key: false,
    //save_key: true,
    // 默认 false。若在服务端生成uptoken的上传策略中指定了 `sava_key`，则开启，SDK在前端将不对key进行任何处理
    domain: qiniuDomain,
    //bucket 域名，下载资源时用到，**必需**
    container: pickfileId+'_container',           //上传区域DOM ID，默认是browser_button的父元素，
    max_file_size: '2000mb',           //最大文件体积限制
    flash_swf_url: '/js/qiniu/Moxie.swf',  //引入flash,相对路径
    max_retries: 0,                   //上传失败最大重试次数
    dragdrop: true,                   //开启可拖曳上传
    drop_element: pickfileId+'_container',       //拖曳上传区域元素的ID，拖曳文件或文件夹后可触发上传
    chunk_size: '4mb',                //分块上传时，每片的体积
    auto_start: true,                 //选择文件后自动上传，若关闭需要自己绑定事件触发上传
    init: {
        'FilesAdded': function(up, files) {
            // 文件添加进队列后,处理相关的事情
            //查询目录下文件名，防止重名
            $.ajax({
                url: '/dir/get-filename-list',
                type: 'post',
                async: false,
                data: {
                    dir_id:_dir_id,
                    p_id:_p_id
                },
                dataType:'json',
                success: function (data) {
                    filename_list = data;
                }
            });
            plupload.each(files, function(file) {
                if(filename_list.indexOf(file.name)>-1){
                    progress_html = '<div class="progress-item">'+
                        '<div class="progress-title">'+
                        file.name+
                        '</div>'+
                        '<div class="progress-striped active progress" style="color:red;">文件名已存在，取消上传</div>'+
                        '</div>';
                    $('#upload_progress').append(progress_html);
                    uploader.stop();
                    return false;
                }else{
                    progress_html = '<div class="progress-item">'+
                        '<div class="progress-title">'+
                        file.name+
                        '</div>'+
                        '<div class="progress-striped active progress" id="upload-progress-'+file.id+'">'+
                        '<div style="width:0%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="0" role="progressbar" class="progress-bar">上传中<span class="sr-only">0% Complete</span></div>'+
                        '</div>'+
                        '</div>';
                    $('#upload_progress').append(progress_html);
                }
            });
            /*$('#upload_progress').append('<div>上传的文件中，有文件名已存在，取消上传</div>');
             uploader.stop();*/
            /*$('#upload-progress-1').show();
             $('#'+fileurlId+'_upload_txt').html('<span style="color:#894A38">上传中,请稍等...</span>');*/

        },
        'BeforeUpload': function(up, file) {
            // 每个文件上传前,处理相关的事情

        },
        'UploadProgress': function(up, file) {

            // 每个文件上传时,处理相关的事情
            $('#upload-progress-'+file.id+' .progress-bar').css('width',file.percent+'%');
            $('#upload-progress-'+file.id+' .progress-bar').html(file.percent+'% <span class="sr-only"></span>');
            $('#upload-progress-'+file.id+' .progress-bar .sr-only').html(file.percent+'%');
            //$('#upload-progress-1 .progress-bar').css('width',file.percent+'%');
            //$('#upload-progress-1 .progress-bar').html(file.percent+'% <span class="sr-only"></span>');
            //$('#upload-progress-1 .progress-bar .sr-only').html(file.percent+'%');
            //$('#'+fileurlId+'_upload_txt').html('<span style="color:#894A38">上传中,请稍等...&nbsp;&nbsp;'+file.loaded+'/'+file.size+'</span>');
        },
        'FileUploaded': function(up, file, info) {
            var res = $.parseJSON(info);
            //console.log(res.key);
            /*console.log(info);
             console.log(res.key);*/


            ////$('#'+fileurlId+'').val(res.key);
            //$('#'+fileurlId+'_preview').attr('src','').attr('src',qiniu_domain+res.key);
            ////$('#'+fileurlId+'_upload_txt').html('<span style="color:green;">上传成功</span>');
            // 每个文件上传成功后,处理相关的事情
            // 其中 info 是文件上传成功后，服务端返回的json，形式如
            // {
            //    "hash": "Fh8xVqod2MQ1mocfI4S4KpRL6D98",
            //    "key": "gogopher.jpg"
            //  }
            // 参考http://developer.qiniu.com/docs/v6/api/overview/up/response/simple-response.html
            // var domain = up.getOption('domain');
            // var res = parseJSON(info);
            // var sourceLink = domain + res.key; 获取上传成功后的文件的Url
            //$('#save-submit').click();

//console.log(res);
            $.ajax({
                url: '/dir/save',
                type: 'post',
                async: false,
                data: {
                    dir_id:_dir_id,
                    filename_real:res.key,
                    filename:file.name,
                    filesize:file.size,
                    flag:1,
                    p_id:_p_id
                },
                dataType:'json',
                success: function (data) {
                    //console.log(res.key);
                    if(data.result){
                        if(_p_id>0){
                            //console.log('/dir?p_id='+_p_id);
                            //location.href='/dir?p_id='+_p_id;
                        }else if(_dir_id>0){
                            //console.log('/dir?dir_id='+_dir_id);

                            //location.href='/dir?dir_id='+_dir_id;
                        }
                    }else{
                        $('#'+fileurlId+'_upload_txt').html('<span style="color:red;">没有上传权限</span>');
                    }
                }
            });


        },
        'Error': function(up, err, errTip) {
            //上传出错时,处理相关的事情
            //$('#'+fileurlId+'_upload_txt').html('<span style="color:red">上传出错</span>');
            /*console.log(up);
             console.log(err);
             console.log(errTip);*/
        },
        'UploadComplete': function(up, files) {

            /*plupload.each(files, function(file) {

             console.log(file);
             });*/
            setTimeout(function(){
                if(_p_id>0){
                    //console.log('/dir?p_id='+_p_id);
                    location.href='/dir?p_id='+_p_id;
                }else if(_dir_id>0){
                    //console.log('/dir?dir_id='+_dir_id);

                    location.href='/dir?dir_id='+_dir_id;
                }
            },1000);



            //队列文件处理完毕后,处理相关的事情
        },
        'Key': function(up, files) {
            /*console.log(up);
             console.log(files);*/

            return 'file:'+_dir_route+files.id+files.name;
        }
    }
});