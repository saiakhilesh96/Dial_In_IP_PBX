// Add Record
function isNumberOnly(evt)
{
    var charCode = (evt.which) ? evt.which : event.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}

function addRecord() {
    // get values
    var plan_name = $("#plan_name").val();
//    alert('from script '+plan_name);
    var calltype_name = $("#calltype_name").val();
    var o_prefix = $("#o_prefix").val();
    var i_prefix = $("#i_prefix").val();
    var charge_paise = $("#charge_paise").val();
    var duration_sec = $("#duration_sec").val();
    var start_date = $("#start_date").val();
    var end_date = $("#end_date").val();
    var sfields = start_date.split('-');
    var efields = end_date.split('-');
    //start_date
    var syear = sfields[0];
    var smonth = sfields[1];
    var sdays = sfields[2];
    //enddate
    var eyear = efields[0];
    var emonth = efields[1];
    var edays = efields[2];

    var totalstartdate = syear + smonth + sdays;
    var totalenddate = eyear + emonth + edays;
//    alert("the totalstartdate is "+totalstartdate);
//    alert("the totalenddate is "+totalenddate);
    // Add record
    if (plan_name != '' && calltype_name != '' && o_prefix != '' && i_prefix != '' && charge_paise != '' && duration_sec != '' && start_date != '' && end_date != '') {

        if (totalstartdate < totalenddate) {
            $.post("addRecord.php", {
                plan_name: plan_name,
                calltype_name: calltype_name,
                o_prefix: o_prefix,
                i_prefix: i_prefix,
                charge_paise: charge_paise,
                duration_sec: duration_sec,
                start_date: start_date,
                end_date: end_date
            }, function (data, status) {
                // close the popup
                $("#add_new_record_modal").modal("hide");
                // read records again
                readRecords();
                // clear fields from the popup
                $("#plan_name").val("");
                $("#calltype_name").val("");
                $("#charge_paise").val("");
                $("#duration_sec").val("");
                $("#start_date").val("");
                $("#end_date").val("");
            });
        } else {
            alert("Check out the Dates please");
        }
    } else {
        alert("Must fill all the Fields");
    }
}

// READ records
function readRecords() {
    $.get("readRecords.php", {}, function (data, status) {
        $(".records_content").html(data);
    });
}


function DeletePlan(date, plan_id, calltype_id) {
//    console.log(plan_id);
//    console.log(calltype_id);
//    console.log(date);
    var childrens = $(date.childNodes);
//    console.log(childrens[11].innerHTML);
//    console.log(childrens[13].innerHTML);
    var start_date = childrens[15].innerHTML;
    var end_date = childrens[17].innerHTML;
    var conf = confirm("Are you sure, do you really want to delete this Plan?");
    if (conf == true) {
        $.post("deletePlan.php", {
            plan_id: plan_id,
            calltype_id: calltype_id,
            start_date: start_date,
            end_date: end_date
        },
                function (data, status) {
                    // reload Users by using readRecords();
                    readRecords();
                    alert('Successfully Deleted the Plan');
                }
        );
    }
}

function GetUserDetails(date, plan_id, calltype_id) {
    // Add User ID to the hidden field for furture usage
    console.log(date);
    console.log(plan_id);
    console.log(calltype_id);
    var childrens = $(date.childNodes);
    console.log(childrens[3].innerHTML);
    console.log(childrens[15].innerHTML);
    console.log(childrens[17].innerHTML);
    var start_date = childrens[15].innerHTML;
    var end_date = childrens[17].innerHTML;
    $("#hidden_plan_id").val(plan_id);
    $("#hidden_calltype_id").val(calltype_id);
    $("#hidden_startdate_id").val(start_date);
    $("#hidden_enddate_id").val(end_date);
    $.post("readPlanDetails.php", {
        plan_id: plan_id,
        calltype_id: calltype_id,
        start_date: start_date,
        end_date: end_date
    },
            function (data, status) {
                // PARSE json data
                var user = JSON.parse(data);
                // puting existing values to the modal popup fields
                $("#update_plan_name").val(childrens[3].innerHTML);
                $("#update_calltype_name").val(childrens[5].innerHTML);
                $("#update_o_prefix").val(childrens[7].innerHTML);
                $("#update_i_prefix").val(childrens[9].innerHTML);
                $("#update_charge_paise").val(user.charge_paise);
                $("#update_duration_sec").val(user.duration_sec);
                $("#update_start_date").val(user.start_date);
                $("#update_end_date").val(user.end_date);
            }
    );
    // Open modal popup
    $("#update_user_modal").modal("show");
}

function UpdateUserDetails() {
    // get values
    var plan_name = $("#update_plan_name").val();
    var calltype_name = $("#update_calltype_name").val();
    var o_prefix = $("#update_o_prefix").val();
    var i_prefix = $("#update_i_prefix").val();
    var charge_paise = $("#update_charge_paise").val();
    var duration_sec = $("#update_duration_sec").val();
    var newstart_date = $("#update_start_date").val();
    var newend_date = $("#update_end_date").val();
    // get hidden field value
    var plan_id = $("#hidden_plan_id").val();
    var calltype_id = $("#hidden_calltype_id").val();
    var start_date = $("#hidden_startdate_id").val();
    var end_date = $("#hidden_enddate_id").val();
    // Update the details by requesting to the server using ajax

    var sfields = newstart_date.split('-');
    var efields = newend_date.split('-');
    //start_date
    var syear = sfields[0];
    var smonth = sfields[1];
    var sdays = sfields[2];
    //enddate
    var eyear = efields[0];
    var emonth = efields[1];
    var edays = efields[2];

    var totalstartdate = syear + smonth + sdays;
    var totalenddate = eyear + emonth + edays;
    if (totalstartdate < totalenddate) {
        var conf = confirm("Are you sure, do you really want to Update this Plan?");
        if (conf == true) {
            $.post("updatePlanDetails.php", {
                plan_id: plan_id,
                calltype_id: calltype_id,
                plan_name: plan_name,
                calltype_name: calltype_name,
                o_prefix: o_prefix,
                i_prefix: i_prefix,
                charge_paise: charge_paise,
                duration_sec: duration_sec,
                newstart_date: newstart_date,
                newend_date: newend_date,
                start_date: start_date,
                end_date: end_date
            },
                    function (data, status) {
                        // hide modal popup
                        $("#update_user_modal").modal("hide");
                        // reload Users by using readRecords();
                        readRecords();
                        alert('Successfully Updated the Plan');
                    }
            );
        }
    } else {
        alert("Check out the Dates please");
    }
}

$(document).ready(function () {
    // READ recods on page load
    readRecords(); // calling function
});