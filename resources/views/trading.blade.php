<!DOCTYPE html>
<html lang="en">
<head>
    <title>Trading</title>
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

                            if (save['pokemon'] != null) {
                                let counter = 3;
                                let row = -1;
                                save['pokemon'].forEach(poke => {
                                    if (counter % 3 === 0) {
                                        document.getElementById('contentDiv').innerHTML += "<div style='width: 100%; display: flex; flex-direction: row; justify-content: center;' class='center' id='row" + counter + "'>"
                                        row = counter;
                                    }

                                    let elementId = 'contentDiv';

                                    if (document.getElementById('row' + row) !== null) {
                                        elementId = 'row' + row;
                                    }

                                    console.log(poke);

                                    document.getElementById(elementId).append(pokemonDiv(poke));

                                    if (counter % 6 === 0) {
                                        document.getElementById('contentDiv').innerHTML += '</div>'
                                    }

                                    counter++;
                                })
                            } else {
                                document.getElementById('contentDiv').innerText += 'No Pokemon Found!'
                            }

                            console.log(save);
                        },
                        error: function (error) {
                            console.log(error);
                        },
                    });
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
                    <div class="title center">
                        <p>Pokemon</p>
                    </div>
                    <div class="content center" id="contentDiv">
                        <div id="pokemonResult"></div>
                    </div>
                </div>
            </td>
        </tr>
        </tbody>
    </table>
</div>
