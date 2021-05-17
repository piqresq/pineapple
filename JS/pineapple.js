
const mailRegex = /^[\w-\.0-9]+@{1}([\w-])+\.+[\w]{2,4}$/;

let submit = false,
    checked = false;
checkedErr = false;

function setError(text) {
    const err = document.getElementById('err');
    err.classList.add('error');
    err.innerHTML = text;
    document.getElementById('submit').disabled = true;
    document.getElementById('submit').classList.remove('enabled-arrow');
    document.getElementById('submit').classList.add('disabled-arrow');
    submit = false;

}

function removeError() {
    const err = document.getElementById('err');
    err.classList.remove('error');
    err.innerHTML = '';
    document.getElementById('submit').disabled = false;
    document.getElementById('submit').classList.add('enabled-arrow');
    document.getElementById('submit').classList.remove('disabled-arrow');
    submit = true;
}

function validateInput() {
    const input = document.getElementById('mail-field').value.toLowerCase();
    if (mailRegex.test(input) === false) {
        setError('Enter correct mail');
    }
    else if (input.split('.')[input.split('.').length - 1] === 'co') {
        setError('We are not accepting subscriptions from Colombia emails');
    }
    else {
        removeError();
    }

}

function checkboxCheck(check) {
    if (check.checked === true){
        checked = true;
        console.log(checkedErr);
        if(checkedErr)removeError();
    }
    else {
        checked = false;
    }
}

function validateSubmit() {
    const mailValue = document.getElementById('mail-field').value;
    if (mailValue === "") {
        setError('Field can\'t be empty');
    }
    else if (checked === false && document.querySelector('#terms-check:checked') == null) {
        setError('You must accept to terms & conditions');
        checkedErr = true;
        return false;
    }

    else {

        //JS success window

        document.getElementById('ssimg').style.display = 'block';
        document.getElementById('header').classList.add('sucs-header');
        document.getElementById('info').classList.add('sucs-info');
        document.getElementById('info').style.paddingBottom="2.5rem";
        document.getElementById('form').style.display = 'none';
        
        return false;
    }
    checkedErr = false;
    return false;
}