<!DOCTYPE html>
<html lang="en">
<head>
    <title>Pokémon Center - Put Pokemon Up For Trade</title>
    <meta charset="UTF-8">
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
                        <p>You wish to put this Pokémon up for trade?</p>
                    </div>
                </div>

                <div id="tradeUi" class="block">
                    <h2 style="text-align: center">Put Up For Trade?</h2>
                    <x-pokemon :id="$id" type="NONE" style="display: block; margin: auto;"/>
                    <div style="text-align: center">
                        <form method="post">
                            @csrf
                            <button type="submit">Trade?</button>
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
