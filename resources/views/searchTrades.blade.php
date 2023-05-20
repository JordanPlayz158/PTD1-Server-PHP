<!DOCTYPE html>
<html lang="en">
<head>
    <title>Pokémon Center - Search Trades</title>
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
                <div class="block">
                    <div class="title"><p>Search Trades - <a
                                href="https://ptd1.jordanplayz158.xyz/games/ptd/checkPokemon.php">Go Back</a></p></div>
                    <div class="content">
                        <p>Here you can search for pokemon up for trade by ID.</p>
                        <form action="?">
                            <label>ID:
                                <input id="tradeId" name="tradeId" type="number">
                            </label>
                            <button type="submit">Search!</button>
                        </form>
                    </div>
                </div>
                @php
                    $tradeId = app('request')->input('tradeId', false);
                @endphp

                @if($tradeId)
                    @if(\App\Models\Trade::where('poke_id', '=', $tradeId)->exists())
                        <x-pokemon :id="$tradeId" type="TRADE"/>
                    @else
                        <p class="msg error-msg">The pokemon requested could not be found OR is not up for trade.</p>
                    @endif

                @endif
            </td>
        </tr>
        </tbody>
    </table>
</div>
</body>
</html>
