<!DOCTYPE html>
<html lang="en">
<head>
    <title>Pokémon Center - API Keys</title>
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
            loadProfile(() => {
                fetch('/tokens')
                    .then(response => response.json())
                    .then(data => {
                        const tokens = data['tokens'];

                        for (let i = 0; i < tokens.length; i++) {
                            const token = tokens[i];
                            const tokenLastUsedDate = token['last_used_at'];

                            const tokenDiv = document.createElement('div');
                            const tokenElement = document.createElement('p');
                            const tokenDeleteButton = document.createElement('button');
                            tokenElement.textContent = 'ID: ' + token['id']
                                + ' | Last Used: ' + (tokenLastUsedDate === null ? 'Never' : tokenLastUsedDate)
                                + ' | Created: ' + token['created_at'];

                            tokenDiv.appendChild(tokenElement)

                            document.getElementById('tokens').appendChild(tokenDiv);
                        }

                        console.log('Success: ', data);
                    })
                    .catch((error) => {
                        console.error('Error: ', error);
                    });
            })
        };

        function makeToken(event) {
            event.preventDefault();

            let target = event.target;
            let formData = {};

            for (let i = 0; i < target.length; i++) {
                const element = target.elements[i];

                if (element.getAttribute("type") !== 'submit') {
                    formData[element.getAttribute("name")] = element.value;
                }
            }

            // Default options are marked with *
            fetch(target.getAttribute('action'), {
                method: target.getAttribute('method'),
                //mode: 'cors', // no-cors, *cors, same-origin
                //cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached
                //credentials: 'include', // include, *same-origin, omit
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                    // 'Content-Type': 'application/x-www-form-urlencoded',
                },
                //redirect: 'follow', // manual, *follow, error
                //referrerPolicy: 'no-referrer', // no-referrer, *no-referrer-when-downgrade, origin, origin-when-cross-origin, same-origin, strict-origin, strict-origin-when-cross-origin, unsafe-url
                body: JSON.stringify(formData) // body data type must match "Content-Type" header
            }).then(response => {
                if(response.status !== 201) {

                }
            })
        }
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
                                    <li><a href="https://ptd1.jordanplayz158.xyz/games/ptd/changeNickname.html">Change Account Nickname</a></li>
                                    <li><a href="https://ptd1.jordanplayz158.xyz/games/ptd/changeAvatar.php">Change Account Avatar</a></li>
                                    <li><a href="https://ptd1.jordanplayz158.xyz/games/ptd/reset_password_form.html">Change Account Password</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div id="saves"></div>
                    <div id="profileResult"></div>
                </td>
                <td id="main">
                    <div class="block">
                        <div class="title"><p>API Keys</p></div>
                        <div class="content">
                            <form action="/tokens" method="POST" onsubmit="makeToken(event)" autocomplete="off">
                                @csrf
                                <input value="Create an API Key" type="submit" class="login_btn">
                            </form>

                            <p>These are all the API Keys currently active for your account:</p><br><br>
                            <div id="tokens"></div>
                        </div>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>
</body>
</html>
