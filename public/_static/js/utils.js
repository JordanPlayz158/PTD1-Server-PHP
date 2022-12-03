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
        if(data['errorCode'] !== null && data['errorCode'] === -1) {
            window.location.href = "/login";
            return false;
        }

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

function pokemonDiv(pokemon) {
    const pokeDiv = document.createElement('div');
    const pokemonId = pokemon['id'];
    const pokemonNum = pokemon['pNum'];

    pokeDiv.id = 'trade_' + pokemonId;
    pokeDiv.classList.add('block', 'pokemon_compact');

    const img = document.createElement('img');
    img.className = 'image';

    const shiny = pokemon['shiny'];
    img.src = '/_static/images/pokemon/' + pokemonNum + '_' + shiny + '.png';

    if (shiny === 1) {
        pokeDiv.classList.add('shiny');
    } else if (shiny === 2)  {
        pokeDiv.classList.add('shadow');
        img.src = '/_static/images/pokemon/' + pokemonNum + '_0.png';
    }
    img.alt = '[Avatar]';

    const nickname = document.createElement('span');
    nickname.className = 'name';
    nickname.innerText = pokemon['nickname'];

    const level = document.createElement('span');
    level.className = 'level';
    level.innerText = 'Lvl ' + pokemon['lvl'];

    const moves = document.createElement('div');
    moves.className = 'moves';

    const moves_table = document.createElement('table');
    const moves_tbody = document.createElement('tbody');

    for (let i = 1; i < 4; i += 2) {
        const moves_tr = document.createElement('tr');

        let move = document.createElement('td');
        move.className = 'left';
        move.innerText = get_move(pokemon['m' + i]);

        moves_tr.append(move);

        move = document.createElement('td');
        move.className = 'right';
        move.innerText = get_move(pokemon['m' + (i + 1)]);

        moves_tr.append(move);

        moves_tbody.append(moves_tr);
    }

    moves_table.append(moves_tbody);
    moves.append(moves_table);

    const actions = document.createElement('div');
    actions.className = 'actions';
    actions.id = 'create_' + pokemonId;

    // For right now it won't allow requests for pokemon with auto-trading just put them up on trading center
    // and let people make offers and let user accept or deny
    let trade = document.createElement('a');
    //trade.href = '/games/ptd/tradeMeSetup.php?save=' + saveNum + '&pokeId=' + poke['myID'];
    trade.href = 'javascript:trade(' + pokemonId + ')';
    trade.text = 'Trade';

    let changeNickname = document.createElement('a');
    changeNickname.href = '/games/ptd/changePokeNickname.php?pokeId=' + pokemonId;
    changeNickname.text = 'Change Nickname'

    let abandon = document.createElement('a');
    abandon.href = 'javascript:abandon(' + pokemonId + ')';
    abandon.text = 'Abandon';

    actions.append(trade, ' | ', changeNickname, ' | ', abandon);

    pokeDiv.append(img, nickname, level, moves, actions);

    if(shiny === 1) {
        const star = document.createElement('img');
        star.classList.add('image', 'star');
        star.src = '/_static/images/star_small.png';

        pokeDiv.insertBefore(star, level);

        // Add to #main .block.shiny
        /*<img class="image" src="/_static/images/star_small.png" style="height: 18px;width: 18px;margin-bottom: 0px;margin-top: 3px;">
            // You can redirect the image link to your own domain too

            // Edit for #main .block.pokemon_compact .name
            Remove 'width: 261px;'

            // Edit for #main .block.pokemon_compact .level
            Remove 'margin-top: -34px;'*/
    }

    return pokeDiv;
}

function trade(id) {
    const result = window.confirm("Trade the pokemon?");
    let body = {'pokemon_id': id}
    const headers = {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        // 'Content-Type': 'application/x-www-form-urlencoded',
    };

    if(result) {
        authenticatedFetch(fetch('/api/trade/', {
            method: 'POST',
            headers: headers,
            body: JSON.stringify(body)

        }))
            .then(result => result.json())
            .then(result => {
                if (result['success'] === true) {
                    document.getElementById('trade_' + id).remove()
                }

                console.log(result);
            }).catch(error => {
                console.log(error);
        })
    }
}

function abandon(id) {
    const result = window.confirm("Abandon the pokemon?");

    if(result) {
        const headers = {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            // 'Content-Type': 'application/x-www-form-urlencoded',
        };

        const save = getCookie('save');

        authenticatedFetch(fetch(`/api/saves/${save}/pokemon/${id}`, {
            method: 'DELETE',
            headers: headers
        }))
            .then(result => result.json())
            .then(result => {
                if(result['success'] === true) {
                    document.getElementById('trade_' + id).remove();
                }

                console.log(result);
            }).catch(error => {
            console.log(error);
        })
    }
}

function getCookie(cookieName) {
    let name = cookieName + "=";
    let decodedCookie = decodeURIComponent(document.cookie);
    let ca = decodedCookie.split(';');
    for(let i = 0; i <ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

function setCookie(cookieName, cookieValue, expireDays) {
    const d = new Date();
    d.setTime(d.getTime() + (expireDays*24*60*60*1000));
    let expires = "expires="+ d.toUTCString();
    document.cookie = cookieName + "=" + cookieValue + ";" + expires + ";path=/;secure;SameSite=Strict";
}

function jsonFetch(event, apiKey = null) {
    let target = event.target;
    let formData = {};

    for (let i = 0; i < target.length; i++) {
        const element = target.elements[i];
        const elementType = element.getAttribute('type');
        const elementName = element.getAttribute('name');

        switch (elementType) {
            case 'submit':
                continue;
            case 'checkbox':
                formData[elementName] = element.checked;
                break;
            default:
                formData[elementName] = element.value;
        }
    }

    const headers = {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        // 'Content-Type': 'application/x-www-form-urlencoded',
    };

    if(apiKey != null) {
        headers['Authorization'] = 'Bearer ' + apiKey;
    }

    // Default options are marked with *
    return fetch(target.getAttribute('action'), {
        method: target.getAttribute('method'),
        //mode: 'cors', // no-cors, *cors, same-origin
        //cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached
        //credentials: 'include', // include, *same-origin, omit
        headers: headers,
        //redirect: 'follow', // manual, *follow, error
        //referrerPolicy: 'no-referrer', // no-referrer, *no-referrer-when-downgrade, origin, origin-when-cross-origin, same-origin, strict-origin, strict-origin-when-cross-origin, unsafe-url
        body: JSON.stringify(formData) // body data type must match "Content-Type" header
    })
}

function authenticatedFetch(fetch) {
    return fetch.then(response => {
        if(response.redirected) {
            window.location.href = response.url;
            throw new Error('Not logged in');
        }

        if(response.status === 401) {
            window.location.href = '/login';
            throw new Error('Not logged in');
        }

        return response;
    });
}

console.log("utils.js loaded");
