<!DOCTYPE html>
<html>
<head>
    <title>Welcome Email</title>
</head>
 
<body>
<h2>Welcome to the site {{$data->email}}</h2>

<a href="{{ url('login') }}">Verify Email</a>
</body>
 
</html>