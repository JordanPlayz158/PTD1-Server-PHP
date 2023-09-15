<!DOCTYPE html>
<html lang="en">
<head>
    <title>Pokémon Center - Your Account</title>
    <meta charset="UTF-8">
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
                        <div class="title"><p>Daily Gift - <a href="{{ url()->previous() }}">Go Back</a></p></div>
                        <div>
                            <p>You have {{ number_format($save->money, 0, ',', '.') }} money - <a href="/games/ptd/gameCorner.php">View Prize List</a></p>

                            <br>
                            @if (session('prize'))
                                    <p>Congratulations! You got {{ session('prize') }}!</p>
                            @else
                                @if ($dateCheck)

                                    <p><a href="{{ route('get-gift', ['button' => 1]) }}">Buy a Common Daily Gift for 1.000 money</a></p>

                                    <br>

                                    @if ($save->badges >= 3)
                                        <p><a href="{{ route('get-gift', ['button' => 2]) }}">Buy an Uncommon Daily Gift for 10.000 money</a></p>
                                    @else
                                        <p>You do not have the requirements for the Uncommon Daily Gift in this profile</p>
                                    @endif

                                    <br>

                                    @if ($save->advanced >= 27)
                                        <p><a href="{{ route('get-gift', ['button' => 3]) }}">Buy a Rare Daily Gift for 100.000 money</a></p>
                                    @else
                                        <p>You do not have the requirements for the Rare Daily Gift in this profile</p>
                                    @endif
                                @else
                                    <p>You have already bought your Daily Gift. Come back tomorrow!</p>
                                @endif
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
