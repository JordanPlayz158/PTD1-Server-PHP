<!DOCTYPE html>
<html lang="en">
<head>
    <title>Pokémon Center - API Key Deletion</title>
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
        const apiKeyId = location.href.substring(location.href.lastIndexOf('/') + 1);

        window.onload = () => {
            loadProfile(() => {
                fetch('/api/tokens/' + apiKeyId)
                    .then(response => response.json())
                    .then(data => {
                        const token = data[0];

                        const tokenLastUsedDate = token['last_used_at'];

                        const tokenDiv = document.createElement('div');
                        const tokenElement = document.createElement('p');
                        tokenElement.textContent = 'ID: ' + token['id']
                            + ' | Last Used: ' + (tokenLastUsedDate === null ? 'Never' : tokenLastUsedDate)
                            + ' | Created: ' + token['created_at'];

                        tokenDiv.appendChild(tokenElement)

                        document.getElementById('tokens').appendChild(tokenDiv);

                        console.log('Success: ', data);
                    })
                    .catch((error) => {
                        console.error('Error: ', error);
                    });
            })
        };

        function deleteToken(event) {
            event.preventDefault();
            event.target.setAttribute('action', '/api/tokens/' + apiKeyId);

            jsonFetch(event, getCookie('apiToken')).then(response => {
                if(response.status === 204) {
                    location.href = '/apiKeys/';
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
                        <div class="title"><p>API Key Deletion</p></div>
                        <div class="content">
                            <p>The API Key you are about to delete:</p><br><br>
                            <div id="tokens"></div>

                            <form method="DELETE" onsubmit="deleteToken(event)" autocomplete="off">
                                @csrf
                                <input value="Delete API Key" type="submit" class="login_btn">
                            </form>
                        </div>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>
</body>
</html>
