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
        #main .block.accountProfiles {
            cursor: pointer;
            float: left;
            width: 300px;
            height: 120px;
            min-height: 32px;
            margin: 10px 5px 10px 10px;
            clear: none;
        }

        .accountProfiles {
            float: left;
        }

        .pointer {
            cursor: pointer;
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
                <td id="sidebar">
                    <x-profile type="EXTENDED" class="pointer"/>
                </td>
                <td id="main">
                    <div class="block">
                        <div class="title"><p>Select Profile</p></div>
                        <div class="content">
                            <p>Pick which profile you wish to trade with:</p>

                        </div>
                    </div>
                    <div class="title">
                        <strong>
                            <font size="+1">
                                <p>PTD1 Profiles:</p>
                            </font>
                        </strong>
                    </div>
                    @for($i = 0; $i < 3; $i++)
                        <x-profile num="{{ $i }}" class="accountProfiles"/>
                    @endfor
                    <div class="title">
                        <strong>
                            <font size="+1">
                                <p style="clear: both;">PTD2 Profiles: Coming Soon!</p>
                            </font>
                        </strong>
                    </div>
                    <div class="title">
                        <strong>
                            <font size="+1">
                                <p style="clear: both;">PTD3 Profiles: Coming Soon!</p>
                            </font>
                        </strong>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>
</body>
</html>
