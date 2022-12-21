<td id="sidebar">
    <!-- Profile -->
    <div class="block">
        <div class="title"><p>Current Profile:</p></div>
        <div class="content">
            <div class="profile_top">
                <div class="avatar">
                    <img id="saveAvatar" src="/_static/images/avatars/{{ $avatar }}.png" alt="[AVATAR]">
                </div>
                <div class="name" id="saveName">{{ $name }}</div>
            </div>
            <div class="profile_middle">
                <span class="info_text">Badges:</span><span id="badges">{{ $badges }}</span><br>
                <span class="info_text">Money:</span><span id="money">{{ $money }}</span>
            </div>
        </div>
    </div>
    <div id="saves">
        @foreach($saves as $save)
            <form action="/profile/" method="POST">
                <input type="hidden" id="save" name="save" value="{{ $save->num }}">
                <div id="{{ $save->num }}" class="block" style="cursor: pointer;">
                    <button type="submit" style="width: 100%; background-color: #FFFFFF; border: none">
                        <div>
                            <div class="avatar">
                                <img src="/_static/images/avatars/{{ $save->avatar }}.png" alt="[Avatar]">
                                {{ $save->nickname }}
                            </div>
                            <hr>
                            <div class="profile_middle">
                                <span class="info-text">Badges:</span>
                                <span>{{ $save->badges }}</span>
                                <br>
                                <span class="info_text">Money:</span>
                                <span>{{ $save->money }}</span>
                            </div>
                        </div>
                    </button>
                </div>
                @csrf
            </form>
        @endforeach
    </div>
</td>
