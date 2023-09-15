@if($isUpForTrade)
    <a href="/games/ptd/recall/{{ $id }}">Recall</a>
@else
    <a href="/games/ptd/trade/{{ $id }}">Trade</a>
@endif
 |
<a href="/games/ptd/changePokemonNickname/{{ $id }}">Change Nickname</a>
 |
<a href="/games/ptd/abandon/{{ $id }}">Abandon</a>
