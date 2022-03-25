//import './utils.js';

$(function () {
    $("#header").load("../../_static/html/header.html");
    $("#nav").load("../../_static/html/nav.html");
    $("#sidebar").load("../../_static/html/profile.html");
});

function loadProfile(callback) {
    $("#sidebar").load("../../_static/html/profile.html", callback);
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
    abandon.href = '/games/ptd/createTrade.php?abandon' + poke['myID'];
    abandon.text = 'Abandon';

    actions.append(trade, ' | ', changeNickname, ' | ', abandon);

    pokeDiv.append(img, nickname, level, moves, actions);

    return pokeDiv;
}

console.log("base.js loaded");