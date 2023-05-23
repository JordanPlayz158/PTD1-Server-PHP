<!DOCTYPE html>
<html lang="en">
<head>
    <title>Pokémon Center - Giveaways</title>
    <meta charset="UTF-8">
    <link rel='stylesheet' type='text/css' href='/_static/css/base.css'>
    <link rel='stylesheet' type='text/css' href='/_static/css/suckerfish.css'>
    <link rel='stylesheet' type='text/css' href="/_static/css/style.css">
    <style>
        .pagination {
            display: inline-block;
        }
    </style>
    <script type='text/javascript' src='/_static/js/tracking.js'></script>
    <script>
        window.addEventListener('load', function () {
            const endTimeElements = document.getElementsByClassName('endTime');

            for (let i = 0; i < endTimeElements.length; i++) {
                endTimeElements[i].innerText = new Date(endTimeElements[i].innerText).toLocaleString('en-US');
            }

            const userTimezoneElements = document.getElementsByClassName('userTimezone');

            for (let i = 0; i < userTimezoneElements.length; i++) {
                userTimezoneElements[i].innerText = Intl.DateTimeFormat().resolvedOptions().timeZone;
            }
        });
    </script>
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
                    <div class="title"><p>Giveaways - <a
                                href="https://ptd1.jordanplayz158.xyz/games/ptd/checkPokemon.php">Go Back</a></p></div>
                    <div class="content">
                        <p>Here is a list of giveaways.</p>
                        <p>To look at concluded giveaways, <a href="completedGiveaways.php">Click Here!</a></p>
                    </div>
                </div>
                <div id="pokemonResult"></div>
                @foreach($giveaways as $giveaway)
                    <x-giveaway :id="$giveaway->id"/>
                @endforeach
            </td>
        </tr>
        <tr>
            <td></td>
            <td style="text-align: center">
                @php
                    $page = app('request')->input('page');

                    $lastPage = (int) ceil($giveaways->total() / $giveaways->perPage());

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
