<div id="trade_{{ $pokemon->id }}" class="block pokemon_compact {{ $pokemon->shiny === 1 ? 'shiny' : '' }} {{ $pokemon->shiny === 2 ? 'shadow' : '' }}">
    <img class="image" src="/_static/images/pokemon/{{ $pokemon->pNum }}_{{ $pokemon->shiny === 1 ? 1 : 0 }}.png" alt="[Avatar]">
    <span class="name">{{ $pokemon->nickname }}</span>
    @if($pokemon->shiny === 1)
        <img class="image star" src="/_static/images/star_small.png" alt="[Shiny Star]">
    @endif
    <span class="level">Lvl {{ $pokemon->lvl }}</span>
    <div class="moves">
        <table>
            <tbody>
            @for($i = 1; $i < 4; $i += 2)
                <tr>
                    @php
                        $moveVariable = 'm' . $i;
                    @endphp
                    <td class="left">{!! \App\View\Components\Pokemon::getMove($pokemon->$moveVariable) !!}</td>

                    @php
                        $moveVariable = 'm' . ($i + 1);
                    @endphp
                    <td class="right">{!! \App\View\Components\Pokemon::getMove($pokemon->$moveVariable) !!}</td>
                </tr>
            @endfor
            </tbody>
        </table>
    </div>
    <div class="actions" id="create_{{ $pokemon->id }}">
        <a href="/games/ptd/makeAnOffer.html?id={{ $pokemon->id }}" style="text-align: center;">Make an Offer</a>
    </div>
</div>
