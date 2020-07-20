function isNumberOnly(evt)
{
    var charCode = (evt.which) ? evt.which : event.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}

function erase_user_details()
{
    //this function will erase all the data that is entered in the input fields of the newcontact form when a close button is pressed 
    $("#slot_name").val("");
    $("#start_date").val("");
    $("#start_time").val("");
    $("#end_date").val("");
    $("#end_time").val("");
}

// Add Record
function addUser() {
    // get values
    var user_id= $("#user_id").val();
    var user_name = $("#user_name").val();
    var batch= $("#batch").val();
    var room_no= $("#room").val();
    var user_group = $("#usergroup").val();
    var type = $("#usertype").val();
    var course = $("#course").val();
    alert('The parameters are '+user_id+'-'+user_name+'-'+batch+'-'+room_no+'-'+user_group+'-'+type+'-'+course);
    // Add record
    if (user_id != '' && user_name != '' && batch != '' && room_no != '' && user_group != '' && type != '' && course != '') {
            $.post("add_user.php", {
                user_id: user_id,
                user_name: user_name,
                batch: batch,
                room: room_no,
                user_group: user_group,
                type: type,
                course: course
            }, function (data, status) {
                // close the popup
                $("#add_new_record_modal").modal("hide");
                // read records again
                readRecords();
                // clear fields from the popup
                $("#user_id").val("");
                $("#user_name").val("");
                $("#batch").val("");
                $("#room").val("");
                $("#usergroup").val("");
                $("#usertype").val("");
                $("#course").val("");
            });
    } else {
        alert("Must fill all the Fields");
    }
}

// READ records
function readRecords() {
    $.get("read_users.php", {}, function (data, status) {
        $(".records_content").html(data);
    });
}

//the following function is used to delete a selected timeslot 
function DeleteUser(user_id) {
    var conf = confirm("Delete the current Time Slot?");
    if (conf == true) {
//        alert('deleting parameters are ' + ts_id + '//' + day + '//' + start_time + '//' + end_time + '//');
        $.post("delete_user.php", {
            user_id: user_id,
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
function GetUserDetails(user_id) {
//    console.log(slotnum);
    var children = $(slotnum.childNodes);
//    console.log(childrens[7].innerHTML);
    var start_date = children[5].innerHTML;
    var start_time = children[7].innerHTML;
//    alert(start_time);
    var end_date = children[9].innerHTML;
    var end_time = children[11].innerHTML;
//    alert(childrens[5].innerHTML);
//    alert(end_time);
    //alert('get nonRecurring details '+ts_id+'-'+start_date+'-'+start_time+'-'+end_date+'-'+end_time);
    // Add User ID to the hidden field for furture usage
    $("#hidden_slot_id").val(ts_id);
    $("#hidden_start_date").val(start_date);
    $("#hidden_start_time").val(start_time);
    $("#hidden_end_date").val(end_date);
    $("#hidden_end_time").val(end_time);
    $.post("read_nonrec_slotDetails.php", {
        slot_id: ts_id,
        start_date: start_date,
        start_time: start_time,
        end_date: end_date,
        end_time: end_time
    },
            function (data, status) {
                // PARSE json data
                var slot = JSON.parse(data);
                // puting existing values to the modal popup fields
                $("#update_slot_name").val(children[3].innerHTML);
                $("#update_start_date").val(children[5].innerHTML);
                $("#update_start_time").val(children[7].innerHTML);
                $("#update_end_date").val(children[9].innerHTML);
                $("#update_end_time").val(children[11].innerHTML);
//                console.log(children[5].innerHTML);
            }
    );
    // Open modal popup
    $("#update_user_modal").modal("show");
}
//the following function is used for updating a nonrecurring time slot entry
function UpdateUser() {
    // get values
    var slot_name = $("#update_slot_name").val();
    var start_date = $("#update_start_date").val();
    var start_time = $("#update_start_time").val();
    var end_date= $("#update_end_date").val();
    var end_time = $("#update_end_time").val();
    alert('updated values are ' + start_date + '-' + start_time + '-' +'-'+end_date+'-'+end_time);
    // get hidden field value
    var slot_id = $("#hidden_slot_id").val();
    var startdate = $("#hidden_start_date").val();
    var starttime = $("#hidden_start_time").val();
    var enddate = $("#hidden_end_date").val();
    var endtime = $("#hidden_end_time").val();
    alert('old values are '+startdate+'-'+starttime+'-'+enddate+'-'+endtime);
    alert('parameters are '+ slot_id+'-'+slot_name+'-'+startdate+'-'+start_date+'-'+starttime+'-'+start_time+'-'+enddate+'-'+end_date+'-'+endtime+'-'+end_time);



    // Update the details by requesting to the server using ajax
    $.post("updateNonRecurringSlot.php", {
        slot_id: slot_id,
        slot_name: slot_name,
        oldstartdate: startdate,
        start_date: start_date,
        oldstarttime: starttime,
        start_time: start_time,
        oldenddate: enddate,
        end_date: end_date,
        oldendtime: endtime,
        end_time: end_time
    },
            function (data, status) {
                // hide modal popup
                $("#update_user_modal").modal("hide");
                // reload Users by using readRecords();
                readRecords();
                alert('Successfully Updated the Non recurring Slot');
            }
    );
//    alert('parameters are '+ slot_id+'-'+slot_name+'-'+weekday+'-'+day+'-'+starttime+'-'+start_time+'-'+endtime+'-'+end_time);
}

$(document).ready(function () {
    // READ recods on page load
    readRecords(); // calling function
});