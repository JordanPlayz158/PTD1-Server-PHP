<!DOCTYPE html>
<html lang="en">
<head>
    <title>Pokémon Center - Abandon Pokemon</title>
    <meta charset="UTF-8">
    <link rel='stylesheet' type='text/css' href='/_static/css/base.css'>
    <link rel='stylesheet' type='text/css' href='/_static/css/suckerfish.css'>
    <link rel='stylesheet' type='text/css' href="/_static/css/style.css">
    <script type='text/javascript' src='/_static/js/tracking.js'></script>
    <style>
        #main .block.pokemon_compact {
            float: none;
            display: inline-block;
        }
    </style>
</head>
<body>
@include('components.header')
<div id="content">
    @include('components.nav')
    <table id="content_table">
        <tbody>
        <tr>
            <x-profiles/>
            <td id="main">
                <div class="block">
                    <div class="title"><p>Pokemon - <a
                                href="/games/ptd/createTrade.php">Go Back</a></p></div>
                    <div class="content">
                        <p>You wish to abandon this Pokémon up for trade?</p>
                    </div>
                </div>

                <div id="tradeUi" class="block">
                    <h2 style="text-align: center">Abandon this pokemon?</h2>
                    <x-pokemon :id="$id" type="NONE" style="display: block; margin: auto;"/>
                    <div style="text-align: center">
                        <form method="post">
                            @csrf
                            <button type="submit" style="color: red"><b>Abandon?</b></button>
                        </form>
                    </div>
                </div>
            </td>
        </tr>
        </tbody>
    </table>
</div>
</body>
</html>
