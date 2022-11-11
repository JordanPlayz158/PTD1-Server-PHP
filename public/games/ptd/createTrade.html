<!DOCTYPE html>
<html lang="en">
<head>
    <title>Pokémon Center - Create Trade</title>
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

                    if (saveNum === null || saveNum.length < 1 || saveNum < 0) {
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

                            if (save['pokemon'] != null) {
                                save['pokemon'].forEach(pokemon => {
                                    console.log(pokemon);

                                    document.getElementById('main').append(pokemonDiv(pokemon));
                                })
                            } else {
                                const noPokemon = document.createElement('p');

                                noPokemon.textContent = 'No Pokemon Found!'

                                document.getElementById('main').append(noPokemon);
                            }

                            console.log(save);
                        },
                        error: function (error) {
                            console.log(error);
                        },
                    })
                });
            });
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
                <div class="block">
                    <div class="title"><p>Create Trade - <a
                                href="/games/ptd/checkPokemon.php?save=0">Go Back</a></p></div>
                    <div class="content">
                        <p>Here is a list of your Pokémon from this profile, click on Trade to create a new Trade.</p>
                        <p>NOTE: This will remove the Pokémon from your profile. To get him back to your profile go back
                            to the "Your Trade Request" section and call your Pokémon back.</p>
                    </div>
                </div>
            </td>
        </tr>
        </tbody>
    </table>
</div>
</body>
</html>
