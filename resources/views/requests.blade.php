<!DOCTYPE html>
<html lang="en">
<head>
    <title>Pokémon Center - View Trades</title>
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
                <div id="requests">
                    <div class="block">
                        <div class="title"><p>Offers and Requests - <a
                                    href="https://ptd1.jordanplayz158.xyz/games/ptd/checkPokemon.php?save=0">Go Back</a>
                            </p></div>
                        <div class="content">
                            <p>Here is a list of your Requests (from others) <b>TO</b> this profile</p>
                        </div>
                    </div>
                    <h2 style="text-align: center"><a href="offers.php">Offers</a> / Requests</h2>

                    @foreach($pokemon->lazy() as $poke)
                        @foreach($poke->requests as $request)
                            <x-offer :id="$request->id" type="REQUEST"/>
                        @endforeach
                    @endforeach
                </div>
            </td>
        </tr>
        </tbody>
    </table>
</div>
</body>
</html>
