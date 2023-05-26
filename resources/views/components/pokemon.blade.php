@php
    $nameSuffix = '';

    if($pokemon->tag === null) $pokemon->tag = '';

    switch($pokemon->tag) {
        case 'n':
            break;
        case 'rf':
            $nameSuffix = ' (Regional Forms)';
            break;
        default:
            $nameSuffix = ' (Hacked)';
    }
@endphp

<div id="trade_{{ $pokemon->id }}"
     class="block pokemon_compact{{ $pokemon->shiny === 1 ? ' shiny' : '' }}{{ $pokemon->shiny === 2 ? ' shadow' : '' }}"
     style="{{ $attributes['style'] }}">
    <img class="image" src="/_static/images/pokemon/{{ $pokemon->pNum }}_{{ $pokemon->shiny === 1 ? 1 : 0 }}.png"
         alt="[Avatar]">
    <span class="name">{{ $pokemon->nickname }}{{ $nameSuffix }}</span>
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
        @if($type == \App\Enums\Components\Pokemon\Actions::STANDARD())
            <x-pokemon.standard :id="$pokemon->id"/>
        @elseif($type == \App\Enums\Components\Pokemon\Actions::TRADE())
            <x-pokemon.trade :id="$pokemon->id"/>
        @endif
    </div>
</div>
