<!DOCTYPE html>
<html lang="en">
<head>
    <title>Pokémon Center - Create Giveaway</title>
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
    <script>
        window.addEventListener('load', function () {
            let offset = -new Date().getTimezoneOffset();
            const diff = offset >= 0 ? '+' : '-';
            const pad = n => `${Math.floor(Math.abs(n))}`.padStart(2, '0');

            document.getElementById('timezone-js').setAttribute('value', diff + pad(offset / 60) + ':' + pad(offset % 60));
        });
    </script>
</head>
<body>
<noscript>
    Sorry, this page requires javascript for accurate time conversion from user time to server time because apparently
    no browsers wanted to support `datetime` html input type and opted to support `datetime-local` which does not contain
    a timezone nor is timezone locked but dependent on the user
    and there is no way to get the timezone of the user without JS, whether through http headers or user agent.
    So if you use this page without javascript, the timezone will be assumed to be UTC and not your timezone.
</noscript>
@include('components.header')
<div id="content">
    @include('components.nav')
    <table id="content_table">
        <tbody>
        <tr>
            <x-profiles/>
            <td id="main">
                <div class="block">
                    <div class="title"><p>Create Giveaway - <a
                                href="/games/ptd/checkPokemon.php?save=0">Go Back</a></p></div>
                    <div class="content">
                        <p>Here is a list of your Pokémon from this profile, click on Create Giveaway to create a new Giveaway.</p>
                        <p>NOTE: This will remove the Pokémon from your profile. To get them back to your profile go
                            to the "Your Giveaways" section and hit "Cancel". You have 24 HOURS from the time of creating the giveaway to cancel it.</p>
                    </div>

                </div>

                <div id="giveawayUi" class="block">
                    <h2 style="text-align: center">Make Giveaway with selected pokemon below (selected pokemon will have a <a style="border: 2px solid limegreen;">green border</a> around them)</h2>
                    <div style="text-align: center">
                        <form method="POST">
                            <label for="title">Title for Giveaway:
                                <input type="text" id="title" name="title">
                            </label>
                            <br>
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
                            <input type="datetime-local" id="complete_at" name="endDate">
                            <input type="hidden" id="timezone-js" name="timezone">
                            <br>
                            <label>
                                <input type="radio" name="type" value="0" checked="checked">
                                1 Winner
                            </label>
                            <label>
                                <input type="radio" name="type" value="1">
                                1 Winner <b>per Pokemon</b> (ex. 7 Pokemon = 7 Winners)
                            </label>
                            <br>
                            @csrf
                            <button type="submit">Make giveaway</button>
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
