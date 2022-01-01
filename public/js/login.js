function emailCheck() {
    /*
     According to [https://codefool.tumblr.com/post/15288874550/list-of-valid-and-invalid-email-addresses]
     needs to check EXISTING of address on special services. Here basic validator.
    */
    const email = document.getElementById('loginEmail');
    let dog = email.value.indexOf('@') > 0;
    let dot = email.value.indexOf('.', dog);
    return dog > 0 && dot > dog && dot !== (email.value.length - 1);
}

function passwordCheck() {
    const password = document.getElementById('loginPassword');
    return password.value.length > 5;
}

function changeColor(element, setRed) {
    if (setRed)
        element.classList.add('is-invalid');
    else
        element.classList.remove('is-invalid');
}

function emailTrigger() {
    let email = document.getElementById('loginEmail');
    changeColor(email, email.value && !emailCheck());
}

function passwordTrigger() {
    const password = document.getElementById('loginPassword');
    changeColor(password, password.value && !passwordCheck());
}

function loginTrigger() {
    let login = document.getElementById('loginConfirm');
    if (emailCheck() && passwordCheck()) {
        if (login.classList.contains('disabled'))
            login.classList.remove('disabled');
    } else {
        if (!login.classList.contains('disabled'))
            login.classList.add('disabled');
    }
}