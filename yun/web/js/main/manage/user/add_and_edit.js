$('#pos_id_input').on('change',function(){
    $('#userform-position_id').val($(this).val());
});
/*
if (navigator.userAgent.toLowerCase().indexOf("chrome") >= 0) {
    console.log('ssdd');
    $(window).load(function(){
        console.log('ssdd222');
        $('input:-webkit-autofill').each(function(){
            console.log('123');
            console.log($(this).val());
            console.log($(this).attr('name'));

            var text = $(this).val();
            var name = $(this).attr('name');
            $(this).after(this.outerHTML).remove();
            $('input[name=' + name + ']').val(text);
        });
    });
}*/
