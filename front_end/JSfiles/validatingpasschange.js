/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
    function validatechangepassword()
    {
        var oldpass = document.forms["changepassform"]["oldpass"];
        var newpass = document.forms["changepassform"]["newpass"];
        var confirmpass = document.forms["changepassform"]["confnewpass"];
        if(oldpass.value === "" || newpass.value === "" || confirmpass.value === "")
        {
            alert('Must enter all fields');
            return false;
        }
        if(newpass.value !== confirmpass.value){
            alert('New password and Confirm password are not same');
            return false;
        }
        return true;
    }
    function onchangepass()
    {
        if(validatechangepassword())
        {   
            document.forms["changepassform"].submit();
            document.forms["changepassform"].reset();
        }
    }

