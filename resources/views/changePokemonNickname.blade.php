<!DOCTYPE html>
<html lang="en">
<head>
    <title>Pokémon Center - Rename Pokemon</title>
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
                                href="{{ url()->previous() }}">Go Back</a></p></div>
                    <div class="content">
                        <p>You wish to rename this pokemon?</p>
                    </div>
                </div>

                <div id="tradeUi" class="block">
                    <h2 style="text-align: center">Rename pokemon?</h2>
                    <x-pokemon :id="$id" type="NONE" style="display: block; margin: auto; float: none;"/>
                    <div style="text-align: center">
                        <form method="post">
                            <label for="name">New Nickname: </label>
                            <input type="text" name="name">
                            <br>

                            @csrf
                            <button type="submit">Rename?</button>
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
