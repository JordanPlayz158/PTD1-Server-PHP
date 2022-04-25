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
            window.location.href = "/games/ptd/login.html";
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

function pokemonDiv(poke) {
    var pokeDiv = document.createElement('div');
    pokeDiv.id = 'trade_' + poke['myID'];
    pokeDiv.classList.add('block', 'pokemon_compact');

    var img = document.createElement('img');
    img.className = 'image';

    const shiny = poke['shiny'];
    img.src = '/_static/images/pokemon/' + poke['num'] + '_' + shiny + '.png';

    if (shiny === 1) {
        pokeDiv.classList.add('shiny');
    } else if (shiny === 2)  {
        pokeDiv.classList.add('shadow');
        img.src = '/_static/images/pokemon/' + poke['num'] + '_0.png';
    }
    img.alt = '[Avatar]';

    const nickname = document.createElement('span');
    nickname.className = 'name';
    nickname.innerText = poke['nickname'];

    const level = document.createElement('span');
    level.className = 'level';
    level.innerText = 'Lvl ' + poke['lvl'];

    const moves = document.createElement('div');
    moves.className = 'moves';

    const moves_table = document.createElement('table');
    const moves_tbody = document.createElement('tbody');

    for (let i = 1; i < 4; i += 2) {
        const moves_tr = document.createElement('tr');

        let move = document.createElement('td');
        move.className = 'left';
        move.innerText = get_move(poke['m' + i]);

        moves_tr.append(move);

        move = document.createElement('td');
        move.className = 'right';
        move.innerText = get_move(poke['m' + (i + 1)]);

        moves_tr.append(move);

        moves_tbody.append(moves_tr);
    }

    moves_table.append(moves_tbody);
    moves.append(moves_table);

    const actions = document.createElement('div');
    actions.className = 'actions';
    actions.id = 'create_' + poke['myID'];

    // For right now it won't allow requests for pokemon with auto-trading just put them up on trading center
    // and let people make offers and let user accept or deny
    let trade = document.createElement('a');
    //trade.href = '/games/ptd/tradeMeSetup.php?save=' + saveNum + '&pokeId=' + poke['myID'];
    trade.href = 'javascript:trade(' + poke['myID'] + ')';
    trade.text = 'Trade';

    let changeNickname = document.createElement('a');
    changeNickname.href = '/games/ptd/changePokeNickname.php?pokeId=' + poke['myID'];
    changeNickname.text = 'Change Nickname'

    let abandon = document.createElement('a');
    abandon.href = 'javascript:abandon(' + poke['myID'] + ')';
    abandon.text = 'Abandon';

    actions.append(trade, ' | ', changeNickname, ' | ', abandon);

    pokeDiv.append(img, nickname, level, moves, actions);

    return pokeDiv;
}

function trade(id) {
    const result = window.confirm("Trade the pokemon?");

    if(result) {
        $.ajax({
            url: '/api/createTrade/',
            type: 'POST',
            data: {
                'save' : getCookie('save'),
                'id' : id,
            },
            success: function (result) {
                if(result['success'] === true) {
                    document.getElementById('trade_' + id).remove()
                }

                console.log(result);
            },
            error: function (error) {
                console.log(error);
            },
        })
    }
}

function abandon(id) {
    const result = window.confirm("Abandon the pokemon?");

    if(result) {
        $.ajax({
            url: '/api/abandonPoke/',
            type: 'POST',
            data: {
                'save' : getCookie('save'),
                'id' : id,
            },
            success: function (result) {
                if(result['success'] === true) {
                    document.getElementById('trade_' + id).remove();
                }

                console.log(result);
            },
            error: function (error) {
                console.log(error);
            },
        })
    }
}



console.log("utils.js loaded");