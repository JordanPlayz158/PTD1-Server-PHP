<!DOCTYPE html>
<html lang="en">
<head>
    <title>Pokémon Center - Change Account Avatar</title>
    <meta charset="UTF-8">
</head>
<body>
@include('components.header')
<div id="content">
    @include('components.nav')
    <table id="content_table">
        <tbody>
        <tr>
            <x-profiles/>
            <td id="main">
                <div class="block">
                    <div id="pokemonResult"></div>
                    <div class="title"><p>Change Your Avatar - <a
                                href="{{ url()->previous() }}">Go Back</a></p></div>
                    <div class="content">
                        <p>CURRENT AVATAR: </p><b>"<span id="avatar">{{ $avatar }}</span>"</b>
                        <div class="block">
                            <form action="" method="POST">
                                <label><b>New Account Avatar:</b>
                                    <select name="avatar" id="avatars">
                                        <!-- These are commented out as they do not exist in the SWF and thus lead to issues
                                        <option value="b_-1">b_-1</option>
                                        <option value="b_0">b_0</option>-->
                                        <option value="b_1">b_1</option>
                                        <option value="b_2">b_2</option>
                                        <option value="b_3">b_3</option>
                                        <option value="b_4">b_4</option>
                                        <option value="b_5">b_5</option>
                                        <option value="b_6">b_6</option>
                                        <option value="b_7">b_7</option>
                                        <option value="b_8">b_8</option>
                                        <option value="b_9">b_9</option>
                                        <option value="b_10">b_10</option>
                                        <option value="b_11">b_11</option>
                                        <option value="b_12">b_12</option>
                                        <option value="b_13">b_13</option>
                                        <option value="b_14">b_14</option>
                                        <option value="b_15">b_15</option>
                                        <option value="b_16">b_16</option>
                                        <option value="b_17">b_17</option>
                                        <option value="b_18">b_18</option>
                                        <option value="b_19">b_19</option>

                                        <option value="g_1">g_1</option>
                                        <option value="g_2">g_2</option>
                                        <option value="g_3">g_3</option>
                                        <option value="g_4">g_4</option>
                                        <option value="g_5">g_5</option>
                                        <option value="g_6">g_6</option>
                                        <option value="g_7">g_7</option>
                                        <option value="g_8">g_8</option>
                                        <option value="g_9">g_9</option>
                                        <option value="g_10">g_10</option>
                                        <option value="g_11">g_11</option>
                                        <option value="g_12">g_12</option>
                                        <option value="g_13">g_13</option>
                                        <option value="g_14">g_14</option>
                                        <option value="g_15">g_15</option>
                                        <option value="g_16">g_16</option>
                                        <option value="g_17">g_17</option>
                                        <option value="g_18">g_18</option>
                                        <option value="g_19">g_19</option>

                                        <option value="none">none</option>
                                    </select>
                                </label>
                                <div class="login_actions">
                                    <input id="submitButton" value="Change" type="submit" class="login_btn">
                                </div>
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>
            </td>
        </tr>
        </tbody>
    </table>
</div>
</body>
</html>
