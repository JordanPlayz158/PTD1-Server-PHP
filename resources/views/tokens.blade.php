<!DOCTYPE html>
<html lang="en">
<head>
    <title>Pokémon Center - API Keys</title>
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
                        <div class="title"><p>API Keys</p></div>
                        <div class="content">
                            @if($newToken)
                                <h1>This is your new access token's key, this WILL NOT be shown to you again: '{{ $newToken }}'</h1>
                            @endif

                            <form action="/apiKeys/" method="POST" autocomplete="off">
                                @csrf
                                <input value="Create an API Key" type="submit" class="login_btn">
                            </form>

                            <p>These are all the API Keys currently active for your account:</p><br><br>
                            @foreach($tokens as $token)
                                @php $tokenId = $token->id @endphp
                                <div id="{{ $tokenId }}">
                                    <p style="display: inline">ID: {{ $tokenId }} | Last Used: {{ $token->last_used_at ?? 'Never' }} | Created: {{$token->created_at}}</p>
                                    <form action="/apiKeys/{{ $tokenId }}">
                                        <button type="submit" style="float: right">Delete API Key</button>
                                    </form>
                                    <br><br>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>
</body>
</html>
