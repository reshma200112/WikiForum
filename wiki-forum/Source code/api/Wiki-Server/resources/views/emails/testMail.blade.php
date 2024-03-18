<!DOCTYPE html>
<html lang="en">
<head>
    <title>Hi,</title>
</head>
<body>
    <h3>{{ $testMailData['title'] }}</h3>
    <p>{{ $testMailData['body'] }}</p>
    <a href="{{ $testMailData['generatedString'] }}">Click here to verify</a>
    <P><strong>{{ $testMailData['highlight'] }}</strong></p>
</body>
</html>

