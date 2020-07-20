/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
//Disable the back browser button after user press logout and destroy session
history.pushState(null, document.title, location.href);
window.addEventListener('popstate', function (event)
{
  history.pushState(null, document.title, location.href);
});


function fun(){
var x = document.getElementById("snackbar")
x.className = "show";
setTimeout(function(){ x.className = x.className.replace("show",""); },3000);

}