<!DOCTYPE html>
<html lang="en">
<head>
    <title>Pokémon Center - Offer Confirmation</title>
    <meta charset="UTF-8">
    <link rel='stylesheet' type='text/css' href='/_static/css/base.css'>
    <link rel='stylesheet' type='text/css' href='/_static/css/suckerfish.css'>
    <link rel='stylesheet' type='text/css' href="/_static/css/style.css">
    <script type='text/javascript' src='/_static/js/tracking.js'></script>
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
                <div id="offers">
                    <div class="block">
                        <div class="title"><p>Offer Action Confirmation - <a
                                    href="https://ptd1.jordanplayz158.xyz/games/ptd/checkPokemon.php?save=0">Go Back</a></p></div>
                        <div class="content">

                        </div>
                    </div>

                    <x-offer :id="$id" type="NONE"/>
                    <p class="msg warning-msg">Are you sure you wish to x this offer?</p>

                </div>
            </td>
        </tr>
        </tbody>
    </table>
</div>
</body>
</html>
