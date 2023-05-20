<!DOCTYPE html>
<html lang="en">
<head>
    <title>Pokémon Center - API Key Deletion</title>
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
                        <div class="title"><p>API Key Deletion</p></div>
                        <div class="content">
                            <p>The API Key you are about to delete:</p><br><br>
                            <div>
                                <p style="display: inline">ID: {{ $token->id }} | Last Used: {{ $token->last_used_at ?? 'Never' }} | Created: {{$token->created_at}}</p>
                                <br><br>
                            </div>

                            <form method="POST" autocomplete="off">
                                @method('DELETE')
                                @csrf
                                <input value="Delete API Key" type="submit" class="login_btn">
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
