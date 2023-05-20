<!DOCTYPE html>
<html lang="en">
<head>
    <title>Pokémon Center - Make an Offer</title>
    <meta charset="UTF-8">
    <link rel='stylesheet' type='text/css' href='/_static/css/base.css'>
    <link rel='stylesheet' type='text/css' href='/_static/css/suckerfish.css'>
    <link rel='stylesheet' type='text/css' href="/_static/css/style.css">
    <script type='text/javascript' src='/_static/js/tracking.js'></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
            integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous">
    </script>
    <script src="/_static/js/moves.js"></script>
    <script src="/_static/js/utils.js"></script>
    <script src="/_static/js/js.cookie.js"></script>
    <style>
        #main .block.pokemon_compact {
            float: none;
            display: inline-block;
        }

        .div_same_line {
            float: none;
            display: inline-block;
        }

        .invisible_checkbox {
            display: none;
        }

        input[type=checkbox]:checked + .selected_box {
            border: 2px solid limegreen;
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
                    <div class="title"><p>Latest Trades - <a
                                href="/games/ptd/checkPokemon.php">Go Back</a></p></div>
                    <div class="content">
                        <p>Here is a list of the latest Pokémon up for trade.</p>
                    </div>
                </div>

                <div id="tradeUi" class="block">
                    <h2 style="text-align: center">Trade for</h2>
                    <x-pokemon :id="$id" type="NONE" style="display: block; margin: auto;"/>
                    <h2 style="text-align: center"><a style="border: 2px solid limegreen;">Select</a> Pokemon to offer (Selected Pokemon will have a green border around them)</h2>
                    <div style="text-align: center">
                        <form method="POST">
                            @for($i = 0; $i < $ids->count(); $i++)
                                @php $id = $ids->get($i)->id @endphp

                                <label for="pokemon{{ $i }}" style="cursor: pointer">
                                    <input class="invisible_checkbox" type="checkbox" id="pokemon{{ $i }}" name="pokemon{{ $i }}" value="{{ $id }}">
                                    <div class="div_same_line selected_box">
                                        <x-pokemon :id="$id" type="NONE"/>
                                    </div>
                                </label>
                            @endfor
                            <br>
                            @csrf
                            <button type="submit">Make request</button>
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
