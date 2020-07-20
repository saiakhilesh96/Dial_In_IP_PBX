/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function(){
   
    $(".editableBox").change(function(){         
        $(".timeTextBox").val($(".editableBox option:selected").html());
    });
});

$(document).ready(function () {
    $("#selectcaller").change(function () {
        $(this).find("option:selected").each(function () {
            var optionValue = $(this).attr("value");
            //alert(optionValue);
            if (optionValue) {
                $(".callerdata").not("." + optionValue).hide();
                $("." + optionValue).show();
            } else {
                $(".callerdata").hide();
            }
        });
    }).change();
});
//JS for incoming rules
$(document).ready(function () {
    $("#incomingselectreceiver").change(function () {
        $(this).find("option:selected").each(function () {
            var optionValue = $(this).attr("value");
            //alert(optionValue);
            if (optionValue) {
                $(".incomingreceiverdata").not("." + optionValue).hide();
                $("." + optionValue).show();
            } else {
                $(".incomingreceiverdata").hide();
            }
        });
    }).change();
});

$(document).ready(function () {
    $("#selectDevice").change(function () {
        $(this).find("option:selected").each(function () {
            var optionValue = $(this).attr("value");
            //alert(optionValue);
            if (optionValue) {
                $(".Devicedata").not("." + optionValue).hide();
                $("." + optionValue).show();
            } else {
                $(".Devicedata").hide();
            }
        });
    }).change();
});

$(document).ready(function () {
    $("#incomingselectDevice").change(function () {
        $(this).find("option:selected").each(function () {
            var optionValue = $(this).attr("value");
            //alert(optionValue);
            if (optionValue) {
                $(".incomingDevicedata").not("." + optionValue).hide();
                $("." + optionValue).show();
            } else {
                $(".incomingDevicedata").hide();
            }
        });
    }).change();
});


function submitform()
{
    document.forms["datatorules"].submit();
    document.forms["datatorules"].reset();
    //alert("Values are sumitted");
}