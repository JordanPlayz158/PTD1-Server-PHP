<!DOCTYPE html>
<html lang="en">
<head>
    <title>Pokémon Center - Your Account</title>
    <meta charset="UTF-8">
    <link rel='stylesheet' type='text/css' href='/_static/css/base.css'>
    <link rel='stylesheet' type='text/css' href='/_static/css/suckerfish.css'>
    <link rel='stylesheet' type='text/css' href="/_static/css/style.css">
    <script type='text/javascript' src='/_static/js/tracking.js'></script>
    <style>
    </style>
</head>
<body>
@include('components.header')
<div id="content">
    @include('components.nav')
    <table id="content_table">
        <tbody>
            <tr>
                <td id="sidebar">
                    <x-profile type="EXTENDED" class="pointer"/>
                </td>
                <td id="main">
                    <div class="block">
                        <div class="title"><p>Game Corner - <a href="/games/ptd/account.php">Go Back</a></p></div>
                        <div>
                            @if ($save->advanced >=  3 && session('id') == null)
                                <p>Welcome to the Game Corner! Now that you are part of Team Rocket you can access the fun of the slots and play to get Team Rocket Exclusive pokemon!</p>
                                
                                <br>

                                <p>You have {{ number_format($user->casino_coins, 0, ',', '.') }} Casino Coins. All Slot Machines cost 5 Casino Coins and you can play up to 50 times a day. One click will make equal to 50 plays.</p>
                                @if (session('prize'))
                                    <br>
                                    <p>Congratulations! You won {{ number_format(session('prize'), 0, ',', '.') }} Casino Coins!</p>
                                @elseif (session('return'))
                                    <br>
                                    <p>You are missing {{ number_format(session('return'), 0, ',', '.') }} Casino Coins.
                                @else  
                                    @if ($dateCheck || $user->last_used_gc == null)
                                        <br>
                                        <a href="{{ route('play-slots') }}">Play Slot Machine 1</a><br><br>
                                        <a href="{{ route('play-slots') }}">Play Slot Machine 2</a><br><br>
                                        <a href="{{ route('play-slots') }}">Play Slot Machine 3</a><br><br>
                                        <a href="{{ route('play-slots') }}">Play Slot Machine 4</a><br><br>
                                        <a href="{{ route('play-slots') }}">Play Slot Machine 5</a><br><br>  
                                        <a href="{{ route('play-slots') }}">Play Slot Machine 6</a><br><br>
                                        <a href="{{ route('play-slots') }}">Play Slot Machine 7</a>    
                    
                                    @else
                                        <br>
                                        <p>You have already played enough slots for today. Come back tomorrow!</p>
                             
                                    @endif
                                @endif
                                       </div>
                                </div>
                                <div class="block">
                                    <div>
                                        <strong>Game Rewards - Normal pokemon have a 1% chance to be shiny.</strong>
                                    </div>
                                </div>
                                 <div class="block">
                                    <div>
                                        <strong>Random Non-Evolved Shadow Pokemon - 300.000 Casino Coins - <a href="{{ route('buy-shadow-pokemon') }}">Buy</a></strong>
                                    </div>
                                </div>
                                <div id="pokemon">
                                    @foreach($pokemon as $poke)
                                        <x-pokemon :id="$poke->id" :cost="$poke->cost" type="GAMECORNER"/>
                                    @endforeach
                                </div>
                            @elseif (session('id'))
                                    <p>Welcome to the Game Corner! Now that you are part of Team Rocket you can access the fun of the slots and play to get Team Rocket Exclusive pokemon!</p>
                                
                                    <br>

                                    <p>You have {{ number_format($user->casino_coins, 0, ',', '.') }} Casino Coins. All Slot Machines cost 5 Casino Coins and you can play up to 50 times a day. One click will make equal to 50 plays.</p>
                            </div>
                        </div>

                                    <div class="block">
                                        <strong>Congratulations! Your prize is the following:</strong>
                                    </div>
                                    <div id="pokemon">
                                        <x-pokemon :id="session('id')"/>
                                    </div>
                            @else
                                <p>Beat Lavender Town and become a part of Team Rocket to use the Game Corner.
                            @endif
                        </div>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>
</body>
</html>
