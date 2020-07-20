/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
function isNumberKey(evt)
{
    var charCode = (evt.which) ? evt.which : event.keyCode;
    if (charCode > 31 && (charCode < 45 || charCode > 57)) {
        return false;
    }
    return true;
}
function isNumberOnly(evt)
{
    var charCode = (evt.which) ? evt.which : event.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}
function typecheck()
{
    var user_id = document.forms["MoneyForm"]["user_id"];
    var amount = document.forms["MoneyForm"]["amount"];
    var userid = user_id.value;
    if (user_id.value === "" || amount.value === "")
    {
        alert('Must enter all fields');
        return false;
    } else {
        return true;
    }
}
function SubmitMoneyTrans()
{
    if (typecheck())
    {
        document.forms["MoneyForm"].submit(); //first submit
        document.forms["MoneyForm"].reset(); //and then reset the form values
    }
}




