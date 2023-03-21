<!DOCTYPE html>
<html lang="en">
<head>
    <title>Pokémon Center - Create Trade</title>
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
                    <div class="title"><p>Create Trade - <a
                                href="/games/ptd/checkPokemon.php?save=0">Go Back</a></p></div>
                    <div class="content">
                        <p>Here is a list of your Pokémon from this profile, click on Trade to create a new Trade.</p>
                        <p>NOTE: This will remove the Pokémon from your profile. To get him back to your profile go back
                            to the "Your Trade Request" section and call your Pokémon back.</p>
                    </div>
                </div>

                <div id="pokemon">
                    @foreach($pokemon->lazy() as $poke)
                        <x-pokemon :id="$poke->id"/>
                    @endforeach
                </div>
            </td>
            <td id="sortTable" style="vertical-align: top">
                <h1>Sort By:</h1>
                <form>
                    @for($i = 0; $i < sizeof($sorts) && $i < sizeof($orderBys); $i++)
                        <input name="sort[]" value="{{ $sorts[$i] }}" hidden>
                        <input name="orderBy[]" value="{{ $orderBys[$i] }}" hidden>
                    @endfor

                    <label for="sort">Column:</label>
                    <select id="sort" name="sort[]">
                        <option value="pNum">Pokemon Number</option>
                        <option value="nickname">Pokemon Nickname</option>
                        <option value="lvl">Pokemon Level</option>
                        <option value="tag">Pokemon Tag</option>
                        <option value="shiny">Pokemon Rarity</option>
                    </select>
                    <br>
                    <label for="orderBy">Order By:</label>
                    <select id="orderBy" name="orderBy[]">
                        <option value="ASC">Ascending</option>
                        <option value="DESC">Descending</option>
                    </select>
                    <br>
                    <button type="submit" style="float: right">Sort</button>
                </form>
                <br>
                <form>
                    <button type="submit" style="float: right">Reset Sort</button>
                </form>
            </td>
        </tr>
        </tbody>
    </table>
</div>
</body>
</html>
