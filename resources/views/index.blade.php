<!DOCTYPE html>
<html lang="en">
<head>
    <title>Welcome to my PTD1 Server!</title>
    <meta charset="UTF-8">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <style>
        #mainPage {
            width: 35em;
            margin: 0 auto;
            font-family: Tahoma, Verdana, Arial, sans-serif;
        }

        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        th {
            text-align: center;
        }

        td {
            vertical-align: top;
            text-align: left;
        }

        td, th {
            border: 1px solid #dddddd;
            padding: 8px;
        }

        tr:nth-child(even) {
            background-color: #dddddd;
        }

        pre, code {
            white-space: pre-line;
        }
    </style>
</head>
<body>
<div style="float: right">
    <a href="/games/ptd/login.html">PokeCenter:
        <br>
        <img src="/_static/images/logo.png" alt="Logo">
    </a>
</div>

<div id="mainPage">
<h1>Welcome to my PTD1 Server!</h1>

<p>In order to connect, you have 2 choices:</p>
<table>
    <thead>
    <tr>
        <th>Modified SWF (Recommended)</th>
        <th>Vanilla SWF</th>
    </tr>
    <tr>
        <th colspan="2">Instructions</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>Simply download <a href="PTD1.swf">this SWF</a>! and you can start playing right away!!!</td>
        <td>
            This method required a bit more work

            Open Notepad as administrator

            Hit File->Open then navigate to your hosts file for your specified OS found below

            <p>Add the lines below to your hosts file</p>
            <p>Windows: <code>C:\Windows\System32\drivers\etc\hosts</code></p>
            <p>Mac & Linux: <code>/etc/hosts</code></p>
            <pre>
                <code>
                    74.76.108.71 www.sndgames.com
                    74.76.108.71 samdangames.blogspot.com
                    74.76.108.71 www.snd-storage.com
                    2603:7081:2a3e:99ac:c38b:2e46:cdd:2e82 www.sndgames.com
                    2603:7081:2a3e:99ac:c38b:2e46:cdd:2e82 samdangames.blogspot.com
                    2603:7081:2a3e:99ac:c38b:2e46:cdd:2e82 www.snd-storage.com
                </code>
            </pre>
            <p>After that, just get the <b>Vanilla</b> SWF from <a
                    href="https://web.archive.org/web/20210309125235/https://www.snd-storage.com/games/ptd/main.swf">archive.org</a>.
            </p>
        </td>
    </tr>
    </tbody>
</table>

<h1>Pokemon Tower Defense 1</h1>
<p>If by chance your browser still supports Flash or you are using a browser made for running flash after it's EOL
    (End-of-Life), you have the easiest time, just play the swf below:</p>
<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
        codebase="https://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" width="100%"
        height="100%">
    <param name="movie" value="PTD1.swf"/>
    <param name="quality" value="high"/>
    <param name="scale" value="default">
    <embed src="PTD1.swf" type="application/x-shockwave-flash" width="100%" height="100%" scale="default"/>
</object>
<p>If you are using puffin browser, using this direct link gives better results, <a href="PTD1.swf">Play PTD1!</a></p>
<br>
<h1>Pokemon Tower Defense 1 Regional Forms</h1>
<p>If by chance your browser still supports Flash or you are using a browser made for running flash after it's EOL
    (End-of-Life), you have the easiest time, just play the swf below:</p>
<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
        codebase="https://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" width="100%"
        height="100%">
    <param name="movie" value="PTD1-RF.swf"/>
    <param name="quality" value="high"/>
    <param name="scale" value="default">
    <embed src="PTD1.swf" type="application/x-shockwave-flash" width="100%" height="100%" scale="default"/>
</object>
<p>If you are using puffin browser, using this direct link gives better results, <a href="PTD1-RF.swf">Play PTD1 Regional Forms!</a></p>
</div>
</body>
</html>
