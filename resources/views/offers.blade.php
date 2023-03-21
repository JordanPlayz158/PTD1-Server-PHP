<!DOCTYPE html>
<html lang="en">
<head>
    <title>Pokémon Center - View Trades</title>
    <meta charset="UTF-8">
    <link rel='stylesheet' type='text/css' href='/_static/css/base.css'>
    <link rel='stylesheet' type='text/css' href='/_static/css/suckerfish.css'>
    <link rel='stylesheet' type='text/css' href="/_static/css/style.css">
    <script type='text/javascript' src='/_static/js/tracking.js'></script>
    <!--<script>
        function acceptRequest(id) {
            if(window.confirm('Do you wish to accept the offer?\nOnce this is done, this action CANNOT be undone.\nYour pokemon will be swapped, please be sure before performing this action.')) {
                const headers = {
                    'Accept': 'application/json',
                    // 'Content-Type': 'application/x-www-form-urlencoded',
                };

                authenticatedFetch(fetch('/api/offers/' + id + '/accept', {
                    method: 'POST',
                    headers: headers,
                }))
                    .then(result => result.json())
                    .then(result => {
                        console.log(result);

                        if(result['success'] === true) {
                            document.getElementById('offer_' + id).remove();
                        }
                    }).catch(error => {
                    console.log(error);
                })
            }
        }

        function deny(id, REQUEST = false) {
            let message = 'Do you wish to deny a request for YOUR pokemon?';

            if(REQUEST) {
                message = 'Do you wish to deny YOUR offer for this (these) pokemon?';
            }

            if(window.confirm(message)) {

                const headers = {
                    'Accept': 'application/json',
                    // 'Content-Type': 'application/x-www-form-urlencoded',
                };

                authenticatedFetch(fetch('/api/offers/' + id, {
                    method: 'DELETE',
                    headers: headers,
                }))
                    .then(result => result.json())
                    .then(result => {
                        console.log(result);

                        if(result['success'] === true) {
                            document.getElementById('offer_' + id).remove();
                        }
                }).catch(error => {
                    console.log(error);
                })
            }
        }
    </script>-->
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
                        <div class="title"><p>Offers and Requests - <a
                                    href="https://ptd1.jordanplayz158.xyz/games/ptd/checkPokemon.php?save=0">Go Back</a></p></div>
                        <div class="content">
                            <p>Here is a list of your Offers (to people) <b>FROM</b> this profile</p>
                        </div>
                    </div>
                    <h2 style="text-align: center">Offers / <a href="requests.php">Requests</a></h2>

                    @foreach($pokemon->lazy() as $poke)
                        @foreach($poke->offers as $offer)
                            <x-offer :id="$offer->id" type="OFFER"/>
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
