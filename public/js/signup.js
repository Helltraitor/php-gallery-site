function emailCheck() {
    /*
     According to [https://codefool.tumblr.com/post/15288874550/list-of-valid-and-invalid-email-addresses]
     needs to check EXISTING of address on special services. Here basic validator.
    */
    const email = document.getElementById('signupEmail');
    if (email.value.length > 320)
        return false;
    const dog = email.value.indexOf('@') > 0;
    const dot = email.value.indexOf('.', dog);
    return dog > 0 && dot > dog && dot !== (email.value.length - 1);
}

function nameCheck() {
    /*
    Check if name is acceptable.
    There is no method 'isalpha' that's why needs to check every symbol instead.
     */
    const name = document.getElementById('signupName');
    if (name.length > 64)
        return false;
    const words = name.value.split(' ');
    const pattern = /[A-Za-zА-Яа-яЁё]/;
    for (let i = 0; i < words.length; i++) {
        const word = words[i];
        if (word === '')
            return false;
        for (let j = 0; j < word.length; j++) {
            if (!pattern.test(word.charAt(j))) return false
        }
    }
    return true;
}

function passwordCheck() {
    const password = document.getElementById('signupPassword');
    return password.value.length > 5
        && /[0-9]/.test(password.value)
        && /[a-z]/.test(password.value)
        && /[A-Z]/.test(password.value);
}

function confirmPasswordCheck() {
    const password = document.getElementById('signupPassword');
    const confirmPassword = document.getElementById('signupConfirmPassword');
    return password.value === confirmPassword.value;
}

function changeColor(element, setRed) {
    if (setRed)
        element.classList.add('is-invalid');
    else
        element.classList.remove('is-invalid');
}

function emailTrigger() {
    let email = document.getElementById('signupEmail');
    changeColor(email, email.value && !emailCheck());
}

function nameTrigger() {
    let name = document.getElementById('signupName');
    changeColor(name, name.value && !nameCheck());
}

function passwordTrigger() {
    const password = document.getElementById('signupPassword');
    changeColor(password, password.value && !passwordCheck());
}

function confirmPasswordTrigger() {
    const confirmPassword = document.getElementById('signupConfirmPassword');
    changeColor(confirmPassword, confirmPassword.value && !confirmPasswordCheck());
}

function signupTrigger() {
    const signupConfirmPD = document.getElementById('signupConfirmPD');

    let signup = document.getElementById('signupConfirm');
    // Ordered by frequency (from lowest to highest)
    if (
        signupConfirmPD.checked
        && emailCheck()
        && confirmPasswordCheck()
        && passwordCheck()
        && nameCheck())
    {
        if (signup.classList.contains('disabled'))
            signup.classList.remove('disabled');
    } else {
        if (!signup.classList.contains('disabled'))
            signup.classList.add('disabled');
    }
}