function update(user_id,phone_number)
{
    var r = confirm("Are you sure to update the contact???");
    if (r === true) {
        ajax(user_id,phone_number);
    } else {
        window.location.reload();
    }
}

function ajax(user_id, phone_number)
{
//    alert("ajax(id,num)");
    var httpxml;
    try
    {
        // Firefox, Opera 8.0+, Safari
        httpxml = new XMLHttpRequest();
    } catch (e)
    {
        // Internet Explorer
        try
        {
            httpxml = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e)
        {
            try
            {
                httpxml = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e)
            {
                alert("Your browser does not support AJAX!");
                return false;
            }
        }
    }
    function stateChanged()
    {
        if (httpxml.readyState === 4)
        {
            ///////////////////////
            //alert(httpxml.responseText); 
            var myObject = JSON.parse(httpxml.responseText);
            if (myObject.value.status === 'success')
            {
                //var oldphonenumber= phone_number;

                var relation_id = 'relation_' + myObject.data.user_id + myObject.data.phone_number;
                document.getElementById(relation_id).innerHTML = myObject.data.relation;

                var pname_id = 'p_name_' + myObject.data.user_id + myObject.data.phone_number;
                document.getElementById(pname_id).innerHTML = myObject.data.p_name;

                var phone_id = 'phone_number_' + myObject.data.user_id + myObject.data.phone_number;
                document.getElementById(phone_id).innerHTML = myObject.data.phone_number;

                var calltype = 'calltype_name_' + myObject.data.user_id + myObject.data.phone_number;
                document.getElementById(calltype).innerHTML = myObject.data.calltype;
                
                var speeddial_id = 'speed_dial_' + myObject.data.user_id + myObject.data.phone_number;
                document.getElementById(speeddial_id).innerHTML = myObject.data.speed_dial;

                var startdate_id = 'start_date_' + myObject.data.user_id + myObject.data.phone_number;
                document.getElementById(startdate_id).innerHTML = myObject.data.start_date;

                var enddate_id = 'end_date_' + myObject.data.user_id + myObject.data.phone_number;
                document.getElementById(enddate_id).innerHTML = myObject.data.end_date;

                document.getElementById("msgDsp").innerHTML = myObject.value.message;
                var sid = 's' + myObject.data.user_id + myObject.data.phone_number;
                document.getElementById(sid).innerHTML = "<input type=button value='Edit' onclick=edit_contact(" + myObject.data.user_id + "," + myObject.data.phone_number + ")>";
                setTimeout("document.getElementById('msgDsp').innerHTML=' '", 2000)
            }// end of if status is success 
            else
            {   // if status is not success 
                //alert("status is not success");
                document.getElementById("msgDsp").innerHTML = myObject.value.message;
                document.getElementById("msgDsp").style.borderColor = 'red';
                setTimeout("document.getElementById('msgDsp').style.display='none'", 2000)
            } // end of if else checking status

        }
    }

    var url = "UpdateContact.php";
    var data_relation = 'data_relation' + user_id + phone_number;
    var data_pname = 'data_pname' + user_id + phone_number;
    var data_phone = 'data_phone' + user_id + phone_number;
    var data_calltype = 'data_calltype' + user_id + phone_number;
    var data_speeddial = 'data_speeddial' + user_id + phone_number;
    var data_startdate = 'data_startdate' + user_id + phone_number;
    var data_enddate = 'data_enddate' + user_id + phone_number;

    var relation = document.getElementById(data_relation).value;
    var pname = document.getElementById(data_pname).value;
    var phonenumber = document.getElementById(data_phone).value;
    var calltype = document.getElementById(data_calltype).value;
    var speeddial = document.getElementById(data_speeddial).value;
    var start = document.getElementById(data_startdate).value;
    var end = document.getElementById(data_enddate).value;

    var parameters = "relation=" + relation + "&pname=" + pname + "&phonenumber=" + phonenumber;
    parameters = parameters + "&speeddial=" + speeddial + "&startdate=" + start + "&enddate=" + end;
    parameters = parameters + "&userid=" + user_id + "&oldnumber=" + phone_number + "&calltype=" + calltype;
//    alert(parameters);
    httpxml.onreadystatechange = stateChanged;
    httpxml.open("POST", url, true)
    httpxml.setRequestHeader("Content-type", "application/x-www-form-urlencoded")
//    alert(parameters);
    document.getElementById("msgDsp").style.borderColor = '#ffffff';
    document.getElementById("msgDsp").style.display = 'inline'
    document.getElementById("msgDsp").innerHTML = "Sairam .... ";
    httpxml.send(parameters);
    //alert("parameters sent");
    ////////////////////////////////
}

function del(user_id, phone_number)
{
//    var txt;
    var r = confirm("Are you sure to delete the contact???");
    if (r === true) {
//        txt = "You pressed OK!";
        deletecontact(user_id,phone_number);
    } else {
//        txt = "You pressed Cancel!";
    }
}


function deletecontact(user_id, phone_number)
{
    //alert("delete(id,num)");
    var httpxml;
    try
    {
        // Firefox, Opera 8.0+, Safari
        httpxml = new XMLHttpRequest();
    } catch (e)
    {
        // Internet Explorer
        try
        {
            httpxml = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e)
        {
            try
            {
                httpxml = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e)
            {
                alert("Your browser does not support AJAX!");
                return false;
            }
        }
    }
//    alert("before state change function");
    function stateChanged()
    {
        if (httpxml.readyState === 4)
        {
            ///////////////////////
//            alert("sairam");
            //alert(httpxml.responseText); 
            var myObject = JSON.parse(httpxml.responseText);
            if (myObject.value.status === 'success')
            {
//                var u_id= user_id;
//                var number= phone_number;
                document.getElementById("msgDsp").innerHTML = myObject.value.message;
//                var sid='s'+myObject.data.user_id+myObject.data.phone_number;
                setTimeout("document.getElementById('msgDsp').innerHTML=' '", 2000)
            }// end of if status is success 
            else
            {   // if status is not success 
                alert("status is not success");
                document.getElementById("msgDsp").innerHTML = myObject.value.message;
                document.getElementById("msgDsp").style.borderColor = 'red';
                setTimeout("document.getElementById('msgDsp').style.display='none'", 2000)
            } // end of if else checking status

        }
    }
//    alert("url delete-ajax");
    var url = "deletecontact.php";
    var parameters = "userid=" + user_id + "&phonenumber=" + phone_number;
//    alert(parameters);
    httpxml.onreadystatechange = stateChanged;
    httpxml.open("POST", url, true)
    httpxml.setRequestHeader("Content-type", "application/x-www-form-urlencoded")
//    alert(parameters);
    document.getElementById("msgDsp").style.borderColor = '#ffffff';
    document.getElementById("msgDsp").style.display = 'inline'
    document.getElementById("msgDsp").innerHTML = "Sairam deleting contact .... ";
    httpxml.send(parameters);
//    alert("parameters sent");
    ////////////////////////////////
}