@php
    $giveawayId = $giveaway->id;
    $giveawayPokemon = $giveaway->pokemon;
    $giveawayHost = $giveaway->owner;
    $endDate = \Carbon\Carbon::parse($giveaway->complete_at);

    $isClosed = $endDate->isPast();

    $height = 129 * ($giveawayPokemon->count() / 3);
@endphp

<div id="giveaway_{{ $giveawayId }}" class="block">
    <p style="text-align: center"><b>{{$isClosed ? 'ENDED ' : ''}}Giveaway</b></p>
    @if($giveaway->title !== null)
        <h2 style="text-align: center">"{{ $giveaway->title }}"</h2>
    @endif
    <p style="text-align: center">Hosted by: {{ $giveawayHost->nickname ?? 'Satoshi' }} (User ID: {{ $giveawayHost->user->id }})<img src="/_static/images/avatars/{{ $giveawayHost->avatar }}.png" alt="[Avatar]"></p>
    <p style="text-align: center">{{ $isClosed ? 'Ended' : 'Ends' }}: <b class="endTime">{{ $endDate->toIso8601String() }}</b> (Your Timezone is: <span class="userTimezone"></span>)</p>
    <hr>
    <div id="pokemon" style="display: inline-block">
        @foreach($giveawayPokemon as $giveawayPoke)
            <x-pokemon :id="$giveawayPoke->pokemon_id" type="NONE"/>
        @endforeach
    </div>
    <hr>
    <div style="text-align: center">
        @if(!$isClosed)
            @if($personal)
                <a href="/giveaways/{{ $giveawayId }}/cancel">Cancel</a>
            @else
                <a href="/giveaways/{{ $giveawayId }}/join">Join</a>
                <a> | </a>
                <a href="/giveaways/{{ $giveawayId }}/leave">Leave</a>
            @endif
            <br>
        @endif
        <a style="text-decoration: underline" href="/giveaways/{{ $giveawayId }}/participants">{{ $giveaway->participants()->count() }}</a> <b>Entered</b>
        <br>
        @switch($giveaway->type)
            @case(\App\Enums\Giveaway::SINGLE_WINNER)
                <b>1 Winner</b>
                @break
            @case(\App\Enums\Giveaway::MULTIPLE_WINNERS)
                <b>{{ $giveaway->pokemon()->count() }} Winners</b>
                @break
        @endswitch
        <br>
        <p>Giveaway ID: {{ $giveawayId }}</p>
    </div>
</div>
