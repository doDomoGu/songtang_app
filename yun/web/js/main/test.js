$(function(){
    username = 'admin';
    password = '1231234';

    $.ajax({
        url: 'http://api.localsongtang.net/user/login',
        type: 'get',
        data: {
            username:username,
            password:password
        },
        dataType:'json',
        success: function (data) {
            console.log(data);return false;

            $('#pos-select-div').html(data);
        }
    });

});
