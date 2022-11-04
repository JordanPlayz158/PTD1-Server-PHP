<!DOCTYPE html>
<html lang="en">
<head>
    <title>Pokémon Center - Latest Trades</title>
    <meta charset="UTF-8">
    <link rel='stylesheet' type='text/css' href='/_static/css/base.css'>
    <link rel='stylesheet' type='text/css' href='/_static/css/suckerfish.css'>
    <link rel='stylesheet' type='text/css' href="/_static/css/style.css">
    <!--<script type='text/javascript' src='logging.js'></script>-->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
            integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous">
    </script>
    <script src="/_static/js/base.js"></script>
    <script src="/_static/js/moves.js"></script>
    <script src="/_static/js/utils.js"></script>
    <script>
        window.onload = () => {
            loadProfile(() => {
                $(function () {
                    let saveNum = getCookie('save');

                    if (saveNum === null || saveNum < 0) {
                        saveNum = 0;
                    } else if (saveNum > 2) {
                        saveNum = 2;
                    }

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
                        url: '/api/trades/all',
                        type: "GET",
                        success: function (pokes) {
                            if (!successCheck(pokes, POKEMON)) {
                                return;
                            }

                            // let counter = 1;
                            // let partyDiv = document.createElement('div');
                            // partyDiv.classList.add('block', 'party');
                            pokes.forEach(poke => {
                                console.log(poke);

                                // if(counter === 1) {
                                //     partyDiv = document.createElement('div');
                                //     partyDiv.classList.add('block', 'party');
                                // }

                                // switch (counter) {
                                //     case 1:
                                //         tradeDiv.classList.add('request_left');
                                //         break;
                                //     case 2:
                                //         tradeDiv.classList.add('request_middle');
                                //         break;
                                //     case 3:
                                //         tradeDiv.classList.add('request_right');
                                // }

                                const makeAnOffer = document.createElement('a');
                                makeAnOffer.text = 'Make an Offer';
                                makeAnOffer.href = '/games/ptd/makeAnOffer.html?id=' + poke['id'];
                                makeAnOffer.style.textAlign = 'center';

                                let childNodeNum = 4;

                                if(poke['shiny'] === 1) {
                                    childNodeNum = 5;
                                }

                                const pokemon = pokemonDiv(poke);
                                let actionDiv = pokemon.childNodes[childNodeNum];

                                for (let i = 0; i < actionDiv.childNodes.length;) {
                                    actionDiv.removeChild(actionDiv.childNodes[i]);
                                }

                                actionDiv.appendChild(makeAnOffer);

                                pokemon.replaceChild(actionDiv, pokemon.childNodes[childNodeNum]);



                                // Thought it made it look better but it doesn't
                                //pokemon.insertBefore(document.createElement('hr'), actionDiv);

                                //console.log(pokemon.childNodes);

                                document.getElementById('main').append(pokemon);

                                // partyDiv.append(tradeDiv);
                                //
                                // if (counter % 3 === 0) {
                                //     counter = 1;
                                //     requestTable.append(partyDiv);
                                // } else {
                                //     counter++;
                                // }
                            })



                            console.log(pokes);
                        },
                        error: function (error) {
                            console.log(error);
                        },
                    });
                });
            });
        };
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
                <div class="block">
                    <div class="title"><p>Latest Trades - <a
                                href="https://ptd1.jordanplayz158.xyz/games/ptd/checkPokemon.php">Go Back</a></p></div>
                    <div class="content">
                        <p>Here is a list of the latest Pokémon up for trade.</p>
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
