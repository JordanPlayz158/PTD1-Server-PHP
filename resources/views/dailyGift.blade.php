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
                        <div class="title"><p>Daily Gift - <a href="/games/ptd/account.php">Go Back</a></p></div>
                        <div>
                            <p>You have {{($save->money)}} money - <a href="/games/ptd/gameCorner.php">View Prize List</a></p>
                            
                            <br>
                            @if (session('prize'))
                                    <p>Congratulations! You got {{ session('prize') }} Casino Coins!</p>
                            @else
                                @if ($user->last_used_dg == now()->toDateString())
                                    <p>You have already bought your Daily Gift</p>
                                @else

                                    <a href="{{ route('get-gift', ['button' => 1]) }}">Buy a Common Daily Gift for 1000 money</a>

                                    <br>
                                    <br>

                                    @if ($save->badges >= 3)
                                        <p><a href="{{ route('get-gift', ['button' => 2]) }}">Buy an Uncommon Daily Gift for 10000 money</a></p>
                                    @else
                                        <p>You do not have the requirements for the Uncommon Daily Gift in this profile</p>
                                    @endif  

                                    <br>

                                    @if ($save->advanced > 10)
                                        <p><a href="{{ route('get-gift', ['button' => 3]) }}">Buy a Rare Daily Gift for 100000 money</a></p>
                                    @else
                                        <p>You do not have the requirements for the Rare Daily Gift in this profile</p>
                                    @endif
                                @endif
                            @endif
                        </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>
</body>
</html>
