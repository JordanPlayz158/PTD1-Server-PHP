<!DOCTYPE html>
<html lang="en">
<head>
    <title>Pokémon Center - Offer Confirmation</title>
    <meta charset="UTF-8">
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
                        <div class="title"><p>Offer Action Confirmation - <a
                                    href="{{ url()->previous() }}">Go Back</a></p></div>
                        <div class="content">

                        </div>
                    </div>

                    <x-offer :id="$id" type="NONE"/>
                    <p class="msg warning-msg">Are you sure you wish to x this offer?</p>

                </div>
            </td>
        </tr>
        </tbody>
    </table>
</div>
</body>
</html>
