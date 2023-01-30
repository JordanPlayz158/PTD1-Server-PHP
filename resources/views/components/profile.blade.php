<form action="/profile/" method="POST" class="{{ $class }}">
    <input type="hidden" id="save" name="save" value="{{ $num }}">
    <button type="submit" style="background-color: #ffffff00; border: none; width: 100%">

        @switch($type)
            @case(\App\Enums\Components\Profile::PRIMARY())
                <!-- Profile -->
                <div class="block{{ $class }}">
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
                @break

            @case(\App\Enums\Components\Profile::SECONDARY())
                <div id="{{ $num }}" class="block{{ $class }}" style="cursor: pointer;">
                    <div>
                        <div class="avatar">
                            <img src="/_static/images/avatars/{{ $avatar }}.png" alt="[Avatar]">
                            {{ $name }}
                        </div>
                        <hr>
                        <div class="profile_middle">
                            <span class="info-text">Badges:</span>
                            <span>{{ $badges }}</span>
                            <br>
                            <span class="info_text">Money:</span>
                            <span>{{ $money }}</span>
                        </div>
                    </div>
                </div>
                @break

            @case(\App\Enums\Components\Profile::EXTENDED())
                <div class="block{{ $class }}">
                    <div class="title">
                        <p>Current Account:</p>
                    </div>
                    <div class="content">
                        <div class="profile_top">
                            <div class="avatar">
                                <img id="saveAvatarPrimary" alt="Avatar" style="height:auto;width:auto;"
                                     src="/_static/images/avatars/{{ $avatar }}.png">
                            </div>
                            <div class="name" id="saveNamePrimary">{{ $name }}</div>
                        </div>
                        <div class="profile_middle">
                            <ul style="list-style-type: '- '; padding: 0 0 0 10px;">
                                <li><a href="/games/ptd/changeNickname.html">Change Account Nickname</a></li>
                                <li><a href="/games/ptd/changeAvatar.html">Change Account Avatar</a></li>
                                <li><a href="/games/ptd/reset_password_form.php">Change Account Password</a></li>
                                <li><a href="/games/ptd/resendVerificationEmail.php">Resend Verification Email</a></li>
                                <li><a href="/games/ptd/changeEmail.php">Change Account Email</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                @break
        @endswitch

    </button>
    @csrf
</form>
