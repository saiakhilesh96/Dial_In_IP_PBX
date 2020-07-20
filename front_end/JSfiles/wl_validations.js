/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
function isNumberOnly(evt)
{
    var charCode = (evt.which) ? evt.which : event.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}

function wl_typecheck()
{
    var user_id = document.forms["newcontactform"]["user_id"];
    var phone = document.forms["newcontactform"]["phone"];
    var relation = document.forms["newcontactform"]["relation"];
    var name = document.forms["newcontactform"]["name"];
    var calltype = document.forms["newcontactform"]["cType"];
    var digit = document.forms["newcontactform"]["speeddial"];
    var start = document.forms["newcontactform"]["start"];
    var end = document.forms["newcontactform"]["end"];

    if (user_id.value === "" || phone.value === "" || relation.value === "" || calltype.value === "Call Type" || digit.value === "" || start.value === "" || end.value === "")
    {
        alert('Must enter all fields');
        return false;
    } else {
        return true;
    }
}
function AddContact()
{
    if (wl_typecheck())
    {
        document.forms["newcontactform"].submit(); //first submit
        document.forms["newcontactform"].reset(); //and then reset the form values
        
    }
}

function erase()
{
    //this function will erase all the data that is entered in the input fields of the newcontact form when a close button is pressed 
    document.forms["newcontactform"].reset();
//    document.forms["filterform"].reset();
}