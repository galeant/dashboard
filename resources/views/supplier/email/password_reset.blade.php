<!DOCTYPE html>
<html>
<head>
    <title>Reset Your Password</title>
</head>
 
<body>
<h2>Hi {{$supplier['fullName']}}</h2>
<br/>
Your registered email-id is {{$supplier['email']}} , Please click on the below link to reset your password account
<br/>
<a href="{{ env('APP_URL_SUPPLIER').('password_reset/'. $supplier['token']) }}">Verify Email</a>
</body>
 
</html>