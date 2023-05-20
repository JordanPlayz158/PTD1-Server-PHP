<!DOCTYPE html>
<html lang="en">
<head>
    <title>Debug</title>
    <meta charset="UTF-8">
    <link rel='stylesheet' type='text/css' href='/_static/css/base.css'>
    <link rel='stylesheet' type='text/css' href='/_static/css/suckerfish.css'>
    <link rel='stylesheet' type='text/css' href="/_static/css/style.css">
</head>
<body>
@include('components.header')
<div id="content">
    @include('components.nav')
    <table id="content_table">
        <tbody>
        <tr>
            <td id="sidebar">
                <div class="block">
                    <div id="result" class=""></div>
                    <div class="title">
                        <p>Debug</p>
                    </div>
                    <div class="content">
                        <p>This is the page to help the developer track down issues.</p>
                    </div>
                </div>
            </td>
            <td id="main">
                <div class="block">
                    <div class="title">
                        <p>Pokémon Tower Defense: Debugging</p>
                    </div>
                    <div class="content">
                        <style>
                            pre {
                                white-space: pre-wrap;
                                word-break: keep-all
                            }
                        </style>
                        <h3>User:</h3>
                        <pre>{{ $user }}</pre>
                        <h3>Saves:</h3>
                        <ul>
                            @php
                                $saves = $user->saves()->get();
                            @endphp

                            @foreach($saves as $save)
                                <li>{{ $save->num }}:</li>
                                <pre>{{ $save }}</pre>
                                <h4>Pokemon:</h4>
                                <ul>
                                    @php
                                        $pokemon = $save->pokemon()->get();
                                    @endphp

                                    @for($i = 0; $i < sizeof($pokemon); $i++)
                                        @php
                                            $poke = $pokemon[$i];
                                        @endphp

                                        <li>{{ $i + 1 }}</li>
                                        <pre>{{ $poke }}</pre>
                                    @endfor
                                </ul>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </td>
        </tr>
        </tbody>
    </table>
</div>
</body>
</html>
