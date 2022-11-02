<!DOCTYPE html>
<html lang="en">
<head>
    <title>Pokémon Center - Your Account</title>
    <meta charset="UTF-8">
    <link rel='stylesheet' type='text/css' href='/_static/css/base.css'>
    <link rel='stylesheet' type='text/css' href='/_static/css/suckerfish.css'>
    <link rel='stylesheet' type='text/css' href="/_static/css/style.css">
    <!--<script type='text/javascript' src='logging.js'></script>-->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
            integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous">
    </script>
    <script src="/_static/js/base.js" async></script>
    <script src="/_static/js/utils.js" async></script>
    <script src="/_static/js/js.cookie.js" async></script>
    <script defer>
        window.onload = () => {
            //loadProfile(() => {

            let saveNumString = getCookie('save');

            if (saveNumString === null) {
                saveNumString = 0
            }

            let saveNum = Number.parseInt(saveNumString);

            if (isNaN(saveNum)) {
                console.log('Invalid number. 0 Substituted')
                saveNum = 0;
            }

            if (saveNum < 0) {
                saveNum = 0;
            } else if (saveNum > 2) {
                saveNum = 2;
            }

            fetch('/api/saves/?exclude=pokes')
                .then(response => response.json())
                .then(saves => {
                    if (!successCheck(saves)) {
                        return;
                    }

                    for (let i = 0; i < saves.length; i++) {
                        let save = saves[i];

                        let nickname = save['nickname'];
                        let avatarPath = '/_static/images/avatars/' + save['avatar'] + '.png';

                        if (i === saveNum) {
                            document.getElementById('saveNamePrimary').innerText = nickname;
                            document.getElementById('saveAvatarPrimary').setAttribute('src', avatarPath);
                        }

                        document.getElementById('saveName' + i).innerText = nickname;
                        document.getElementById('saveAvatar' + i).setAttribute('src', avatarPath);
                        document.getElementById('badges' + i).innerText = save['badges'];
                        document.getElementById('money' + i).innerText = save['money'];

                        let blockDiv = document.getElementById('profile1_' + i);
                        blockDiv.onclick = function () {
                            Cookies.set('save', i, {expires: 365});
                            location.reload();
                        };
                        blockDiv.style.cursor = 'pointer';
                    }
                    //initProfile(save['avatar'], save['nickname'], save['badges'], save['money'], saveNum);

                    validationCheck(saves);

                    console.log(saves);
                })
            //})
        };
    </script>
</head>
<body>
<div id="header"></div>
<div id="content">
    <div id="nav"></div>
    <table id="content_table">
        <tbody>
            <tr>
                <td id="sidebar">
                    <!-- Profile -->
                    <div class="block">
                        <div class="title">
                            <p>Current Account:</p>
                        </div>
                        <div class="content">
                            <div class="profile_top">
                                <div class="avatar">
                                    <img id="saveAvatarPrimary" alt="Avatar" style="height:auto;width:auto;">
                                </div>
                                <div class="name" id="saveNamePrimary"></div>
                            </div>
                            <div class="profile_middle">
                                <ul style="list-style-type: '- '; padding: 0 0 0 10px;">
                                    <li><a href="/games/ptd/changeNickname.html">Change Account Nickname</a></li>
                                    <li><a href="/games/ptd/changeAvatar.php">Change Account Avatar</a></li>
                                    <li><a href="/games/ptd/reset_password_form.html">Change Account Password</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div id="saves"></div>
                    <div id="profileResult"></div>
                </td>
                <td id="main">
                    <div class="block">
                        <div class="title"><p>Select Profile</p></div>
                        <div class="content">
                            <p>Pick which profile you wish to trade with:</p>

                        </div>
                    </div>
                    <div class="title"><strong><font size="+1"><p>PTD1 Profiles:</p></font></strong></div><div id="profile1_0" class="block" style="cursor: pointer;float: left;
    width: 300px;
    height: 120px;min-height: 32px;
    margin: 10px 5px 10px 10px;
    clear: none;">
                    <div class="title"><p>Current Profile:</p></div>
                    <div class="content">
                        <div class="profile_top">
                            <div class="avatar">
                                <img id="saveAvatar0" alt="" style="height:auto;width:auto;">
                            </div>
                            <div class="name" id="saveName0"></div>
                        </div>
                        <div class="profile_middle">
                            <span class="info_text">Badges:</span><span id="badges0"></span><br>
                            <span class="info_text">Money:</span><span id="money0"></span>
                        </div>
                    </div>
                </div><div id="profile1_1" class="block" style="cursor: pointer;float: left;
    height: 120px;width: 300px;
    min-height: 32px;
    margin: 10px 5px 10px 10px;
    clear: none;">
                    <div class="title"><p>Current Profile:</p></div>
                    <div class="content">
                        <div class="profile_top">
                            <div class="avatar">
                                <img id="saveAvatar1" alt="" style="height:auto;width:auto;">
                            </div>
                            <div class="name" id="saveName1"></div>
                        </div>
                        <div class="profile_middle">
                            <span class="info_text">Badges:</span><span id="badges1"></span><br>
                            <span class="info_text">Money:</span><span id="money1"></span>
                        </div>
                    </div>
                </div><div id="profile1_2" class="block" style="cursor: pointer;float: left;
    width: 300px;
    height: 120px;min-height: 32px;
    margin: 10px 5px 30px 10px;
    clear: none;">
                    <div class="title"><p>Current Profile:</p></div>
                    <div class="content">
                        <div class="profile_top">
                            <div class="avatar">
                                <img id="saveAvatar2" alt="" style="height:auto;width:auto;">
                            </div>
                            <div class="name" id="saveName2"></div>
                        </div>
                        <div class="profile_middle">
                            <span class="info_text">Badges:</span><span id="badges2"></span><br>
                            <span class="info_text">Money:</span><span id="money2"></span>
                        </div>
                    </div>
                </div><div class="title"><strong><font size="+1"><p style="clear: both;">PTD2 Profiles: Coming Soon!</p></font></strong><div class="title"><strong><font size="+1"><p style="clear: both;">PTD3 Profiles: Coming Soon!</p></font></strong></div></div>
                </td>
            </tr>
        </tbody>
    </table>
</div>
</body>
</html>
