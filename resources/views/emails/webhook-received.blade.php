<!DOCTYPE html>
<html>
<head>
    <title>Webhook Received</title>
</head>
<body>
    <p>From: {{ $data['MSISDN'] }}</p>
    <p>Message: {{ $data['text'] }}</p>
</body>
</html>
