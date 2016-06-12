/**
 * Created by Seth on 12/06/16.
 */

function checkEmailAvailability() {
    //$("#loaderIcon").show();
    jQuery.ajax({
        url: "/registration/checkemail",
        data:'email='+$("#email").val(),
        type: "POST",
        success:function(data){
            $("#email-availability-status").html(data);
            //$("#loaderIcon").hide();
        },
        error:function (){}
    });
}
function checkPasswordLength() {
    //$("#loaderIcon").show();
    jQuery.ajax({
        url: "/registration/checkpass",
        data:'password='+$("#password").val(),
        type: "POST",
        success:function(data){
            $("#password-length-status").html(data);
            //$("#loaderIcon").hide();
        },
        error:function (){}
    });
}
function checkUsernameAvailability() {
    //$("#loaderIcon").show();
    jQuery.ajax({
        url: "/registration/checkusername",
        data:'username='+$("#username").val(),
        type: "POST",
        success:function(data){
            $("#username-availability-status").html(data);
            //$("#loaderIcon").hide();
        },
        error:function (){}
    });
}
