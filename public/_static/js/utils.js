const PROFILE = 1;
const POKEMON = 2;

// Number used for multiple error locations
function successCheck(data, number = PROFILE) {
    let prepend = 'profile';

    switch (number) {
        case POKEMON:
            prepend = 'pokemon'
    }

    if(data['success'] === false) {
        let result = document.getElementById(prepend + 'Result');
        result.id = prepend + 'Result';
        result.className = 'error-msg msg';

        let error = document.getElementById(prepend + 'error');

        if(error === null) {
            error = document.createElement('a');
        }

        error.id = prepend + 'error';
        error.innerText = data['error']

        result.append(error);
        return false;
    }

    return true;
}

function validationCheck(data, number = PROFILE) {
    let prepend = 'profile';

    switch (number) {
        case POKEMON:
            prepend = 'pokemon'
    }

    if(data['validation']) {
        let result = document.getElementById(prepend + 'Result');
        result.id = prepend + 'Result';
        result.className = 'warning-msg msg';

        let error = document.getElementById(prepend + 'error');

        if(error === null) {
            error = document.createElement('a');
        }

        error.id = prepend + 'error';
        error.innerText = data['validation']

        result.append(error);
        return false;
    }

    return true;
}

console.log("utils.js loaded");