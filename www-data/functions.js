function checkAll() {
var everyting=document.getElementById('post-checks');
var boxes=new Array();
for (var x=0;x<everyting.length;x++) {if (everyting[x].type=="checkbox") {boxes[boxes.length]=everyting[x];}}
//now all checkboxes are in the array boxes so lets check all of them...
//USE THE NEXT LINE TO CHECK THEM ALL
//for(var x=0;x<boxes.length;x++) {boxes[x].checked=true;}
//USE THE NEXT LINE TO UNCHECK THEM ALL
//for(var x=0;x<boxes.length;x++) {boxes[x].checked=false;}
//USE THE NEXT LINE TO INVERSE THEM ALL
for(var x=0;x<boxes.length;x++) {if (boxes[x].checked) {boxes[x].checked=false;}else {boxes[x].checked=true;}}
}

function testing (e,myId) {
var s = e.checked ? 'checked' : 'unchecked';

document.getElementById(myId).className='plugin'+s

}

function activatePlugin(myId)
{
    document.getElementById(myId).className='pluginActive';
}

function deactivatePlugin(myId)
{
    document.getElementById(myId).className='pluginNonActive';
}

function disableAutocomplete()
{
    if (!document.getElementById) return false;
    var f = document.getElementById('login');
    var u = f.elements[0];
    f.setAttribute("autocomplete", "off");
    u.focus();
}
function suggestPassword() {
    // restrict the password to just letters and numbers to avoid problems:
    // "editors and viewers regard the password as multiple words and
    // things like double click no longer work"
    var pwchars = "abcdefhjmnpqrstuvwxyz23456789ABCDEFGHJKLMNPQRSTUVWYXZ";
    var passwordlength = 15;    // do we want that to be dynamic?  no, keep it simple :)
    var passwd = document.getElementById('generated_pw');
    passwd.value = '';

    for ( i = 0; i < passwordlength; i++ ) {
        passwd.value += pwchars.charAt( Math.floor( Math.random() * pwchars.length ) )
    }
    document.getElementById('password1').value = passwd.value;
    document.getElementById('password2').value = passwd.value;
    return true;
}
