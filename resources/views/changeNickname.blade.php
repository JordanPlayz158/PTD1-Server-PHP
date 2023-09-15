<!DOCTYPE html>
<html lang="en">
<head>
    <title>Pokémon Center - Change Account Nickname</title>
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
                    <div id="pokemonResult"></div>
                    <div class="title"><p>Change Your Nickname - <a
                                href="{{ url()->previous() }}">Go Back</a></p></div>
                    <div class="content">
                        <p>CURRENT NICKNAME: </p><b>"<span id="accNickname">{{ $name }}</span>"</b>
                        <div class="block">
                            <form action="" method="POST">
                                <label><b>New Account Nickname:</b>
                                    <input id="nickname" class="text" name="name" type="text">
                                </label>
                                <div class="login_actions">
                                    <input id="submitButton" value="Change" type="submit" class="login_btn">
                                </div>
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>
            </td>
        </tr>
        </tbody>
    </table>
</div>
</body>
</html>
