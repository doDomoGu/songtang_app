$(function(){
    $('select[name="isDistrictLimit"]').change(function(){
        if($(this).val()==1){
            $('#districtCheck').show();
        }else{
            $('#districtCheck').hide();
        }
    })

    $('select[name="isIndustryLimit"]').change(function(){
        if($(this).val()==1){
            $('#industryCheck').show();
        }else{
            $('#industryCheck').hide();
        }
    })

    $('.save-btn').click(function(){
        $('#attr-limit-form').submit();
    })
})