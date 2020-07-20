// Add Record
function isNumberOnly(evt)
{
    var charCode = (evt.which) ? evt.which : event.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}

function addDevice() {
    // get values
    var location = $("#location").val();
    var deviceid = $("#deviceid").val();
    var devicenumber = $("#devicenumber").val();
//    alert(location);
//    alert(deviceid);
//    alert(devicenumber);
    // Add record
    if (location != '' && deviceid != '' && devicenumber != '') {

        $.post("addDevice.php", {
            location: location,
            deviceid: deviceid,
            devicenumber: devicenumber
        }, function (data, status) {
            // close the popup
            $("#add_new_record_modal").modal("hide");
            // read records again
            alert("Device Added Successfully");
            window.location.reload();
            
//            $("#mytable").reload;
//            $("#mytable").load("Device Allocation.php #mytable")
//            readRecords();
            // clear fields from the popup
            $("#location").val("");
            $("#deviceid").val("");
            $("#devicenumber").val("");
        });
    } else {
        alert("Must fill all the Fields");
    }
}

// READ records
function readRecords() {
    $.get("readRecords.php", {}, function (data, status) {
    $(".records_content").reload;
    });
}


function DeleteDevice(sdg_id,sd_id,sd_extension) {
    console.log(sdg_id);
    console.log(sd_extension);
    console.log(sd_id);
//    var childrens = $(sdg_id.childNodes);
////    console.log(childrens[11].innerHTML);
////    console.log(childrens[13].innerHTML);
//    var start_date = childrens[15].innerHTML;
//    var end_date = childrens[17].innerHTML;
    var conf = confirm("Are you sure, do you really want to delete?");
    if (conf == true) {
        $.post("DeleteDevice.php", {
            sdg_id: sdg_id,
            sd_id: sd_id,
            sd_extension: sd_extension
        },
                function (data, status) {
                    alert('Successfully Deleted');
                    window.location.reload();
                }
        );
    }
}

function GetDeviceDetails(sdg_id, sd_id, sd_extension) {
    // Add User ID to the hidden field for furture usage
//    alert("sairam");
    console.log(sdg_id);
    console.log(sd_id);
    console.log(sd_extension);
    $("#hidden_sdg_id").val(sdg_id);
    $("#hidden_sd_id").val(sd_id);
    $("#hidden_sd_extension").val(sd_extension);
    $.post("readDeviceDetails.php", {
        sdg_id: sdg_id,
        sd_id: sd_id,
        sd_extension: sd_extension
    },
            function (data, status) {
                // PARSE json data
                var Device = JSON.parse(data);
                // puting existing values to the modal popup fields
                $("#update_location").val(Device.sdg_desc);
                $("#update_deviceid").val(Device.sd_id);
                $("#update_devicenumber").val(Device.sd_extension);
            }
    );
    // Open modal popup
    $("#update_Device_modal").modal("show");
}

function UpdateDeviceDetails(){
    // get values
    var location = $("#update_location").val();
    var deviceid = $("#update_deviceid").val();
    var devicenumber = $("#update_devicenumber").val();
    // get hidden field value
    var sdg_id = $("#hidden_sdg_id").val();
    var sd_id = $("#hidden_sd_id").val();
    var sd_extension = $("#hidden_sd_extension").val();
    // Update the details by requesting to the server using ajax
        var conf = confirm("Are you sure, do you really want to Update?");
        if (conf == true) {
            $.post("updateDeviceDetails.php", {
                sdg_id: sdg_id,
                sd_id: sd_id,
                sd_extension: sd_extension,
                location: location,
                deviceid: deviceid,
                devicenumber: devicenumber
            },
                    function (data, status) {
                        // hide modal popup
                        $("#update_Device_modal").modal("hide");
                        // reload Users by using readRecords();
//                        readRecords();
                        alert('Successfully Updated');
                        window.location.reload();
                    }
            );
        }
}

$(document).ready(function () {
    // READ recods on page load
    readRecords(); // calling function
});