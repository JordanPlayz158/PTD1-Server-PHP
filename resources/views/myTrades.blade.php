<!DOCTYPE html>
<html lang="en">
<head>
    <title>Pokémon Center - View Trades</title>
    <meta charset="UTF-8">
    <style>
        .pagination {
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
                    <div class="title"><p>Create Trade - <a
                                href="{{ url()->previous() }}">Go Back</a></p></div>
                    <div class="content">
                        <p>Here is a list of your Pokémon from this profile, click on Trade to create a new Trade.</p>
                        <p>NOTE: This will remove the Pokémon from your profile. To get him back to your profile go back
                            to the "Your Trade Request" section and call your Pokémon back.</p>
                    </div>
                </div>
                <div id="pokemonResult"></div>
                @foreach($pokemon as $poke)
                    <x-pokemon id="0" :overridePokemon="$poke"/>
                @endforeach
            </td>
        </tr>
        <tr>
            <td></td>
            <td style="text-align: center">
                @php
                    $page = app('request')->input('page');

                    $lastPage = (int) ceil($pokemon->total() / $pokemon->perPage());

                    if($page === null) {
                        $previousPage = 1;
                        $nextPage = 2;
                    } else {
                        $previousPage = max(($page - 1), 1);
                        $nextPage = min(max(($page + 1), 2), $lastPage);
                    }
                @endphp
                <form class="pagination" action="?">
                    <input type="hidden" name="page" value="1" />
                    <button type="submit">First</button>
                </form>
                <form class="pagination" action="?">
                    <input type="hidden" name="page" value="{{ $previousPage }}" />
                    <button type="submit">Previous</button>
                </form>
                <form class="pagination" action="?">
                    <input type="hidden" name="page" value="{{ $nextPage }}" />
                    <button type="submit">Next</button>
                </form>
                <form class="pagination" action="?">
                    <input type="hidden" name="page" value="{{ $lastPage }}" />
                    <button type="submit">Last</button>
                </form>
            </td>
        </tr>
        </tbody>
    </table>
</div>
</body>
</html>
