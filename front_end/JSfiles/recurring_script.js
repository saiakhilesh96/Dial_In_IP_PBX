// Add Record
function addRecurringSlot() {
    // get values
    var slot_name = $("#slot_name").val();
//    alert('the value is '+slot_name);
    var weekday = $("#weekday").val();
    var start_time = $("#start_time").val();
    var end_time = $("#end_time").val();
//    alert(slot_name+weekday+start_time+end_time);
    // Add record
    if(slot_name != '' && weekday != '' && start_time != '' && end_time != ''){
        $.post("addRec_slot.php", {
            slot_name: slot_name,
            weekday: weekday,
            start_time: start_time,
            end_time: end_time
        }, function (data, status) {
            // close the popup
            $("#add_new_record_modal").modal("hide");
            // read records again
            readRecords();
            // clear fields from the popup
            $("#slot_name").val("");
            $("#weekday").val("");
            $("#start_time").val("");
            $("#end_time").val("");
        });
    }else{
        alert("Must fill all the Fields");
    }
}

// READ records
function readRecords() {
    $.get("readRecords.php", {}, function (data, status) {
        $(".records_content").html(data);
    });
}

//the following function is used to delete a selected timeslot 
function DeleteRecurringSlot(slotnum, ts_id, day) {
    var conf = confirm("Delete the current Time Slot?");
    if (conf == true) {
        var children = $(slotnum.childNodes);
        var start_time = children[7].innerHTML;
        var end_time = children[9].innerHTML;
//        alert('deleting parameters are ' + ts_id + '//' + day + '//' + start_time + '//' + end_time + '//');
        $.post("delete_rec_slot.php", {
            slot_id: ts_id,
            day: day,
            start_time: start_time,
            end_time: end_time
        },
                function (data, status) {
                    // reload Users by using readRecords();
                    readRecords();
                    alert('Successfully Deleted the Slot');
                }
        );
    }
}

//the following function gets the existing values and passes the new values to be updated
function GetRecurringDetails(slotnum, ts_id, day) {
//    console.log(slotnum);
    var childrens = $(slotnum.childNodes);
//    console.log(childrens[7].innerHTML);
    var start_time = childrens[7].innerHTML;
//    alert(start_time);
    var end_time = childrens[9].innerHTML;
//    alert(childrens[5].innerHTML);
//    alert(end_time);
    //alert('get Recurring details '+ts_id+day+start_time+end_time);
    // Add User ID to the hidden field for furture usage
    $("#hidden_slot_id").val(ts_id);
    $("#hidden_day").val(day);
    $("#hidden_start_time").val(start_time);
    $("#hidden_end_time").val(end_time);
    $.post("read_rec_slotDetails.php", {
        slot_id: ts_id,
        day: day,
        start_time: start_time,
        end_time: end_time
    },
            function (data, status) {
                // PARSE json data
                var slot = JSON.parse(data);
                // puting existing values to the modal popup fields
                $("#update_slot_name").val(childrens[3].innerHTML);
                $("#update_start_time").val(childrens[7].innerHTML);
                $("#update_end_time").val(childrens[9].innerHTML);
                $("#update_day").val(childrens[5].innerHTML);
                console.log(childrens[5].innerHTML);
            }
    );
    // Open modal popup
    $("#update_user_modal").modal("show");
}
//the following function is used for updating a recurring time slot entry
function UpdateRecurringSlot() {
    // get values
    var slot_name = $("#update_slot_name").val();
    var day = $("#update_day").val();
    var start_time = $("#update_start_time").val();
    var end_time = $("#update_end_time").val();
//    alert('updated values are ' + day + '-' + start_time + '-' + end_time);
    // get hidden field value
    var slot_id = $("#hidden_slot_id").val();
    var weekday = $("#hidden_day").val();
    var starttime = $("#hidden_start_time").val();
    var endtime = $("#hidden_end_time").val();
//    alert('old values are '+weekday+'-'+starttime+'-'+endtime);
//    alert('parameters are '+ slot_id+'-'+slot_name+'-'+weekday+'-'+day+'-'+starttime+'-'+start_time+'-'+endtime+'-'+end_time);



    // Update the details by requesting to the server using ajax
    $.post("updateRecurringSlot.php", {
        slot_id: slot_id,
        weekday: weekday,
        day: day,
        slot_name: slot_name,
        oldstarttime: starttime,
        start_time: start_time,
        oldendtime: endtime,
        end_time: end_time
    },
            function (data, status) {
                // hide modal popup
                $("#update_user_modal").modal("hide");
                // reload Users by using readRecords();
                readRecords();
                alert('Successfully Updated the Slot');
                
            }
    );
//    alert('parameters are '+ slot_id+'-'+slot_name+'-'+weekday+'-'+day+'-'+starttime+'-'+start_time+'-'+endtime+'-'+end_time);
}

$(document).ready(function () {
    // READ recods on page load
    readRecords(); // calling function
});