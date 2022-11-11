<!DOCTYPE html>
<html lang="en">
<head>
    <title>Pokémon Center - Make an Offer</title>
    <meta charset="UTF-8">
    <link rel='stylesheet' type='text/css' href='/_static/css/base.css'>
    <link rel='stylesheet' type='text/css' href='/_static/css/suckerfish.css'>
    <link rel='stylesheet' type='text/css' href="/_static/css/style.css">
    <!--<script type='text/javascript' src='logging.js'></script>-->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
            integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous">
    </script>
    <script src="/_static/js/moves.js"></script>
    <script src="/_static/js/utils.js"></script>
    <script src="/_static/js/js.cookie.js"></script>
    <style>
        /**** POKEMON COMPACT ****/
        #main .block.shadow {
            background-color: #B72DEA;
        }
        #sidebar .block.shadow {
            background-color: #B72DEA;
        }
        #main .block.shiny {
            background-color: #DCF0FF;
        }
        #sidebar .block.shiny {
            background-color: #DCF0FF;
        }

        .block.gold {
            background-color: #DFB846;
        }
        .block.silver {
            background-color: #C6BAAA;
        }

        .block .title.middle img {
            margin-top: -7px;
        }

        .block.pokemon_compact {
            float: left;
            width: 300px;
            min-height: 32px;
            margin: 0 10px 10px 0;
            clear: none;
        }

        .block.pokemon_compact.wide {
            width: 510px;
        }
        .block.pokemon_compact.wide2 {
            width: 400px;
        }
        .block.pokemon_compact .image {
            float: left;
            margin-bottom: 5px;
            height: 32px;
            width: 32px;
        }

        .block.pokemon_compact .image.star {
            height: 18px;
            width: 18px;
            margin-bottom: 0;
            margin-top: 3px;
        }

        .block.pokemon_compact .name {
            float: left;
            line-height: 30px;
            margin-left: 7px;
            width: 261px;
            font-weight: bold;
        }

        .block.pokemon_compact.shiny .name {
            width: auto;
        }

        .block.pokemon_compact .level {
            float: right;
            margin-top: -34px;
            text-align: right;
            width: 45px;
        }

        .block.pokemon_compact.shiny .level {
            margin-top: 0;
        }

        .block.pokemon_compact .level2 {
            float: right;
            margin-top: 0;
            text-align: right;
            width: 45px;
        }
        .block.pokemon_compact .moves {
            clear: left;
            width: 300px;
        }

        .block.pokemon_compact .moves table {
            border-collapse: collapse;
            width: 100%;
            height: 54px;
            border-top: 1px solid #8C8C8C;
        }

        .block.pokemon_compact .moves .left {
            border-bottom: 1px solid #8C8C8C;
            border-right: 1px solid #8C8C8C;
            text-align: right;
            padding: 2px 5px 2px 2px;
            width: 50%;
        }

        .block.pokemon_compact .moves .right {
            border-bottom: 1px solid #8C8C8C;
            padding: 2px 2px 2px 5px;
            width: 50%;
        }

        .block.pokemon_compact .actions {
            padding-top: 6px;
            text-align: center;
        }

        .block.pokemon_compact .menu_box {
            float: right;
            width: 190px;
            height: 101px;
            margin: 0 0 0 9px;
            padding: 2px 5px 5px;
            border-left: 1px solid #8C8C8C;
            display: table;
        }

        .block.pokemon_compact .menu_box .menu {
            vertical-align: middle;
            display: table-cell;
        }

        .block.pokemon_compact .menu_box .menu p {
            margin: 15px 0 0;
        }

        .block.pokemon_compact .menu_box .menu p:first-child {
            margin: 0;
        }
    </style>
    <script>
        const pokeId = new URLSearchParams(window.location.search).get('id')

        $(function () {
            $("#header").load("../../_static/html/header.html");
            $("#nav").load("../../_static/html/nav.html");

            let saveNum = Cookies.get('save');

            if(saveNum === undefined || saveNum === null || saveNum < 0) {
                saveNum = 0;
            } else if(saveNum > 2) {
                saveNum = 2;
            }

            $.ajax({
                url: '/api/saves/' + saveNum + '/pokemon',
                type: "GET",
                success: function (pokes) {
                    if(!successCheck(pokes, POKEMON)) {
                        return;
                    }

                    const pokemon = document.getElementById('pokemon');

                    pokes.forEach(poke => {
                        const pokeDiv = pokemonDiv(poke);
                        //pokeDiv.classList.add('draggableDivHeader')
                        pokeDiv.draggable = true;
                        pokeDiv.style.cursor = 'move';
                        pokeDiv.id = poke['id'];

                        pokeDiv.style.float = 'none';

                        pokeDiv.removeChild(pokeDiv.lastChild);

                        pokeDiv.setAttribute('ondragstart', 'drag(event)');

                        pokemon.append(pokeDiv)
                    });

                    console.log(pokes);
                },
                error: function (error) {
                    console.log(error);
                },
            });

            $.ajax({
                url: '/api/pokemon/' + pokeId,
                type: "GET",
                success: function (poke) {
                    if(!successCheck(poke, POKEMON)) {
                        return;
                    }

                    let pokemon = pokemonDiv(poke);
                    pokemon.style.alignContent = 'center';
                    pokemon.style.margin = 'auto';
                    pokemon.style.float = 'none';
                    document.getElementById('tradePokemon').append(pokemon);

                    console.log(poke);
                },
                error: function (error) {
                    console.log(error);
                },
            });
        });

        let parentNode = null;

        // https://www.w3schools.com/tags/att_global_draggable.asp
        function allowDrop(ev) {
            let target = ev.target;
            while(target != null) {
                if(target.classList.contains('isDroppable')) {
                    parentNode = target;
                    ev.preventDefault();
                    break;
                }

                target = target.parentNode;
            }
        }

        function drag(ev) {
            ev.dataTransfer.setData("id", ev.target.id);
        }

        function drop(ev) {
            var data = ev.dataTransfer.getData("id");
            parentNode.appendChild(document.getElementById(data));
            ev.preventDefault();
        }
        // End w3schools

        function submitRequest() {
            let offer = document.getElementById('offer').childNodes;

            let offeredPokemon = [];
            for (let i = 0; i < offer.length; i++) {
                offeredPokemon[i] = offer[i].id;
            }

            console.log(offeredPokemon);

            let body = {'offerIds': offeredPokemon};
            const headers = {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                // 'Content-Type': 'application/x-www-form-urlencoded',
            };

            fetch('/api/pokemon/' + pokeId + '/offer', {
                method: 'POST',
                headers: headers,
                body: JSON.stringify(body)
            }).then(result => result.json()).then(result => {
                if (!successCheck(result, POKEMON)) {
                    return;
                }

                console.log(result);
            }).catch(error => {
                console.log(error);
            })
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
            <td id="sidebar">
                <div class="block isDroppable" ondrop="drop(event)" ondragover="allowDrop(event)" id="pokemon" style="height: 100%"></div>
            </td>
            <td id="main">
                <div class="block">
                    <div class="title"><p>Latest Trades - <a
                                href="/games/ptd/checkPokemon.php">Go Back</a></p></div>
                    <div class="content">
                        <p>Here is a list of the latest Pokémon up for trade.</p>
                    </div>
                </div>

                <div id="tradeUi" class="block">
                    <h2 style="text-align: center">Trade for</h2>
                    <div id="tradePokemon"></div>
                    <h2 style="text-align: center">Offered Pokemon</h2>
                    <div id="offer" class="block isDroppable" ondrop="drop(event)" ondragover="allowDrop(event)" style="height: 250px; width: 90%; visibility: visible"></div>
                    <button type="button" onclick="submitRequest()">Make request</button>
                </div>
                <div id="pokemonResult"></div>
            </td>
        </tr>
        </tbody>
    </table>
</div>
</body>
</html>
