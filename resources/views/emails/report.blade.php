<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Report Email</title>
</head>
<body>
    From: {{$emailData['start_date'] ?? ''}} to {{$emailData['end_date'] ?? ''}}
</body>
</html>
