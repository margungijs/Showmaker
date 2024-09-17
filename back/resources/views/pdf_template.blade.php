<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title }}</title>
</head>
<body>
    <h1>{{ $title }}</h1>
    <p>{{ $content['host'] }}: {{ $content['title'] }}</p>
    <p>Date: {{ $content['date'] }}</p>
    <p>Location: {{ $content['location'] }}</p>
    <p>Price: {{ $content['price'] }}</p>
</body>
</html>
