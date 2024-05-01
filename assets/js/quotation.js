$(document).ready(function() {
    $("#email").on("change", function(){
        var thiss        = $(this);
        var email_address   = $(this).val();
        $('#email').parent(".ajaxcheck").children('i').remove();
        $.ajax({
            type: 'POST',
            url: 'quotation/validate_email/',
            data: 'email='+email_address,
            success: function (response) {
                if ( response == 'false' ){
                    $('#email').parent(".ajaxcheck").append('<i class="glyphicon glyphicon-remove"><span>Already exists</span></i>');
                    $(".submitbtn").attr('disabled',true);
                } else {
                    $('#email').parent(".ajaxcheck").append('<i class="glyphicon glyphicon-ok green"></i>');
                     $(".submitbtn").attr('disabled',false);
                }
            }
        });
    });


    $("#company_name").on("change", function(){
        var thiss        = $(this);
        var company_name   = $(this).val();
        $('#company_name').parent(".ajaxcheck").children('i').remove();
        $.ajax({
            type: 'POST',
            url: 'quotation/validate_company/',
            data: 'company_name='+company_name,
            success: function (response) {
                if ( response == 'false' ){
                    $('#company_name').parent(".ajaxcheck").append('<i class="glyphicon glyphicon-remove"><span>Already exists</span></i>');
                    $(".submitbtn").attr('disabled',true);
                } else {
                    $('#company_name').parent(".ajaxcheck").append('<i class="glyphicon glyphicon-ok green"></i>');
                     $(".submitbtn").attr('disabled',false);
                }
            }
        });
    });
});

function editor_description(){
    $("#description").val($(".note-editable").html());
}