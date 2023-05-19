<!DOCTYPE html>
<html lang="en">
<head>
    <title>Ruffle - {{ $game }}</title>
    <meta charset="UTF-8">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <script src="https://unpkg.com/@ruffle-rs/ruffle"></script>
    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        ruffle-embed {
            height: 100%;
            width: 100%;
        }
    </style>
</head>
<body>
    <embed src="{{ $game }}" type="application/x-shockwave-flash"/>
</body>
</html>
