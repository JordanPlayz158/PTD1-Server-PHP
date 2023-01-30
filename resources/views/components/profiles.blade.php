<td id="sidebar">
    <x-profile type="PRIMARY" class=""/>
    <div id="saves">
        @foreach($saves as $save)
            <x-profile type="SECONDARY" :num="$save->num" class=""/>
        @endforeach
    </div>
</td>
