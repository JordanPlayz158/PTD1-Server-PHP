<!DOCTYPE html>
<html lang="en">
<head>
    <title>Pokémon Center - View Trades</title>
    <meta charset="UTF-8">
    <link rel='stylesheet' type='text/css' href='/_static/css/base.css'>
    <link rel='stylesheet' type='text/css' href='/_static/css/suckerfish.css'>
    <link rel='stylesheet' type='text/css' href="/_static/css/style.css">
    <style>
        /* The switch - the box around the slider */
        /* https://www.w3schools.com/howto/howto_css_switch.asp */
        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }

        /* Hide default HTML checkbox */
        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        /* The slider */
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }

        input:checked + .slider {
            background-color: #2196F3;
        }

        input:focus + .slider {
            box-shadow: 0 0 1px #2196F3;
        }

        input:checked + .slider:before {
            -webkit-transform: translateX(26px);
            -ms-transform: translateX(26px);
            transform: translateX(26px);
        }

        /* Rounded sliders */
        .slider.round {
            border-radius: 34px;
        }

        .slider.round:before {
            border-radius: 50%;
        }
    </style>
    <!--<script type='text/javascript' src='logging.js'></script>-->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
            integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous">
    </script>
    <script src="/_static/js/base.js"></script>
    <script src="/_static/js/moves.js"></script>
    <script src="/_static/js/utils.js"></script>
    <script>
        let saveNum;

        window.onload = () => {
            loadProfile(() => {
                $(function () {
                    saveNum = getCookie('save');

                    if (saveNum === null || saveNum < 0) {
                        saveNum = 0;
                    } else if (saveNum > 2) {
                        saveNum = 2;
                    }

                    console.log(saveNum);

                    $.ajax({
                        url: '/api/saves/' + saveNum + '?exclude=user_id,advanced,advanced_a,challenge,classic,classic_a,npcTrade,shinyHunt,version,created_at,updated_at,pokemon.offers,pokemon.requests',
                        type: "GET",
                        success: function (save) {
                            if (!successCheck(save)) {
                                return;
                            }

                            initProfile(save['avatar'], save['nickname'], save['badges'], save['money'], saveNum);

                            console.log(save);
                        },
                        error: function (error) {
                            console.log(error);
                        },
                    })

                    $.ajax({
                        url: '/api/saves/' + saveNum + '/pokemon/all',
                        type: "GET",
                        success: function (pokes) {
                            if (!successCheck(pokes, POKEMON)) {
                                return;
                            }

                            console.log(pokes);

                            offersDiv(pokes);
                            offersDiv(pokes, 0);
                        },
                        error: function (error) {
                            console.log(error);
                        },
                    });
                });
            });
        }

        function offersDiv(pokes, offer = 1) {
            const offersIds = [];

            pokes.forEach(function (poke) {
                console.log(poke);

                const divName = offer === 1 ? 'offers' : 'requests';
                const offers = poke[divName];

                offers.forEach(function (offer) {
                    console.log(offer);
                    const offerId = offer.id;

                    if(offersIds.includes(offerId)) {
                        return;
                    }

                    offersIds.push(offerId);

                    // Following this for 2 column div (kind of)
                    // https://stackoverflow.com/questions/42146618/how-to-get-floatright-button-to-vertically-align-in-the-middle
                    const offerDiv = document.createElement('div');
                    offerDiv.classList.add('block');
                    offerDiv.id = 'offer_' + offerId;

                    const leftColumnTitle = document.createElement('p');
                    leftColumnTitle.innerText = 'Offers';
                    leftColumnTitle.style.float = 'left';

                    const leftColumn = document.createElement('div');
                    leftColumn.style.width = '75%';

                    const rightColumnTitle = document.createElement('p');
                    rightColumnTitle.innerText = 'Requests';
                    rightColumnTitle.style.float = 'right';

                    const rightColumn = document.createElement('div');
                    rightColumn.style.width = '24%';

                    const offerPokemon = offer['offer_pokemon'];

                    offerPokemon.forEach(function (offerPokemon) {
                        console.log(offerPokemon);
                        const pokeDiv = pokemonDiv(offerPokemon['pokemon']);
                        pokeDiv.style.float = 'left';

                        leftColumn.append(pokeDiv);
                    });

                    const requestPokemon = offer['request_pokemon'];

                    // There is hr where is (horizontal rule)
                    // But vr (vertical rule) does not exist
                    // So I am making my own with
                    // https://stackoverflow.com/questions/571900/is-there-a-vr-vertical-rule-in-html/45902079
                    const vr = document.createElement('div');
                    vr.style.borderLeft = '1px solid #000';
                    vr.style.margin = '0 10px 0 0';
                    vr.style.height = (129 * ((offerPokemon.length / 3) + (requestPokemon.length / 3))) + 'px';

                    requestPokemon.forEach(function (requestPokemon) {
                        console.log(requestPokemon);
                        const pokeDiv = pokemonDiv(requestPokemon['pokemon']);
                        pokeDiv.style.float = 'right';
                        rightColumn.append(pokeDiv)
                    });

                    leftColumn.style.display = 'table-cell';
                    vr.style.display = 'table-cell';
                    rightColumn.style.display = 'table-cell';

                    const actions = document.createElement('div');
                    actions.style.textAlign = 'center';

                    const accept = document.createElement('a');
                    accept.text = 'Accept';
                    accept.href = 'javascript:acceptOffer(' + offerId + ')';

                    const separator = document.createElement('a');
                    separator.text = ' | ';

                    const deny = document.createElement('a');
                    deny.text = 'Deny';
                    deny.href = 'javascript:denyOffer(' + offerId + ')';

                    if(offer === 0) {
                        actions.append(accept, separator, deny);
                    } else {
                        deny.text = 'Revoke';
                        actions.append(deny);
                    }

                    offerDiv.append(leftColumnTitle, rightColumnTitle, document.createElement('br') , leftColumn, vr, rightColumn, document.createElement('hr'), actions)

                    document.getElementById(divName).append(offerDiv);
                });
            });
        }

        function divSwitcher(event) {
            let offers = document.getElementById('offers');
            let requests = document.getElementById('requests')

            if(event.target.checked === true) {
                offers.style.display = 'none';
                requests.style.display = 'inline';
            } else {
                offers.style.display = 'inline';
                requests.style.display = 'none';
            }
        }

        function acceptRequest(id) {
            if(window.confirm('Do you wish to accept the offer?\nOnce this is done, this action CANNOT be undone.\nYour pokemon will be swapped, please be sure before performing this action.')) {
                console.log('accepted');
            }
        }

        function denyOffer(id) {
            if(window.confirm('Do you wish to deny YOUR offer for this (these) pokemon?')) {
                $.ajax({
                    url: '/api/offers/' + id,
                    type: "DELETE",
                    success: function (result) {
                        console.log(result);

                        if(result['success'] === true) {
                            document.getElementById('offer_' + id).remove();
                        }
                    },
                    error: function (error) {
                        console.log(error);
                    },
                });
            }
        }
    </script>
</head>
<body>
<div id="header"></div>
<div id="content">
    <div id="nav"></div>
    <table id="content_table">
        <tbody>
        <tr>
            <td id="sidebar"></td>
            <td id="main">
                <a style="float: right;">Offers / Requests</a>
                <br>
                <!-- Rounded switch -->
                <!-- https://www.w3schools.com/howto/howto_css_switch.asp -->
                <label class="switch" style="float: right;">
                    <input type="checkbox" oninput="divSwitcher(event)">
                    <span class="slider round"></span>
                </label>

                <div id="offers">
                    <div class="block">
                        <div class="title"><p>Offers and Requests - <a
                                    href="https://ptd1.jordanplayz158.xyz/games/ptd/checkPokemon.php?save=0">Go Back</a></p></div>
                        <div class="content">
                            <p>Here is a list of your Offers from this profile</p>
                        </div>
                    </div>
                </div>
                <div id="requests" style="display: none">
                    <div class="block">
                        <div class="title"><p>Offers and Requests - <a
                                    href="https://ptd1.jordanplayz158.xyz/games/ptd/checkPokemon.php?save=0">Go Back</a></p></div>
                        <div class="content">
                            <p>Here is a list of your Requests from this profile</p>
                        </div>
                    </div>
                </div>
                <div id="pokemonResult"></div>
            </td>
        </tr>
        </tbody>
    </table>
</div>
</body>
</html>
