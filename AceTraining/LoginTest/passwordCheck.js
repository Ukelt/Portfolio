//Check the strngth of the password
passHelp = document.getElementById('passwordHelp');
 newPass = document.getElementById('newPassword');
 newPass.addEventListener('keyup', function(){passwordCheck(newPass.value)});

function passwordCheck(password) {
    var strength = 0;
    if (password.length < 8) {
        passHelp.textContent = 'Too short';
        passHelp.classList.remove('text-warning', 'text-success');
        passHelp.classList.add('text-danger');
        return 'Too short'
    }
    if (password.length > 7) {
        strength += 1;
    }
    // what do the regexes do?
    //
    if (password.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/)) {
        strength += 1;
    } 
    if (password.match(/([a-zA-Z])/) && password.match(/([0-9])/)) {
        strength += 1;
    }
    if (password.match(/([!,%,&,@,#,$,^,*,?,_,~])/)) {
        strength += 1;
    }
    if (password.match(/(.*[!,%,&,@,#,$,^,*,?,_,~].*[!,%,&,@,#,$,^,*,?,_,~])/)) {
        strength += 1;
    }
    if (strength < 2 && password.length > 7) {
        passHelp.textContent = 'Weak';
        passHelp.classList.remove('text-warning', 'text-success');
        passHelp.classList.add('text-danger');
        return 'Weak'
    } else if (strength == 2 && password.length > 7) {
        passHelp.textContent = 'Moderate';
        passHelp.classList.remove('text-danger', 'text-success');
        passHelp.classList.add('text-warning');
        return 'Moderate'
    } else if (strength > 2 && password.length > 7) {
        passHelp.textContent = 'Strong';
        passHelp.classList.remove('text-danger', 'text-warning');
        passHelp.classList.add('text-success');
        return 'Strong'
    }
}