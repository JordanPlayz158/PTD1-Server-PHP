@php
    $requestId = $trading->id;
    $offerPokemon = $trading->offerPokemon;
    $requestPokemon = $trading->requestPokemon;

    $height = 129 * (($offerPokemon->count() / 3) + ($requestPokemon->count() / 3));
@endphp

<div id="offer_{{ $requestId }}" class="block">
    <p style="float: left">Offers</p>
    <p style="float: right">Requests</p>
    <br>
    <div id="leftColumn" style="width: 75%; display: table-cell">
        @foreach($offerPokemon as $offerPoke)
            <x-pokemon :id="$offerPoke->pokemon_id" type="NONE" style="float: left"/>
        @endforeach
    </div>
    <div id="vr"
         style="border-left: 1px solid rgb(0, 0, 0); margin: 0 10px 0 0; height: {{ $height }}px; display: table-cell"></div>
    <div id="rightColumn" style="width: 24%; display: table-cell">
        @foreach($requestPokemon as $requestPoke)
            <x-pokemon :id="$requestPoke->pokemon_id" type="NONE" style="float: right"/>
        @endforeach
    </div>
    @if($type != \App\Enums\Components\Trading::NONE())
    <hr>
    <div style="text-align: center">
        @if($type == \App\Enums\Components\Trading::OFFER())
            <a href="">Retract</a>
        @elseif($type == \App\Enums\Components\Trading::REQUEST())
            <a href="/offers/{{ $requestId }}/confirm">Accept</a>
            <a> | </a>
            <a href="/offers/{{ $requestId }}/confirm">Reject</a>
        @endif
    </div>
    @endif
</div>
