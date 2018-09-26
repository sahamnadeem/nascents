<!DOCTYPE html>
<html>
<head>
	<title>Saham</title>
</head>
<body>
	<form method="POST" action="/ceptraproj/register/">
		username: <input type="text" name="username"><br/>
		password: <input type="password" name="password"><br/>
		<input type="text" name="is_active" hidden value="1"><br/>
		first_name: <input type="text" name="first_name"><br/>
		last_name: <input type="text" name="last_name"><br/>
		email: <input type="email" name="email"><br/>
		<input type="submit" value="submit">
	</form>
</body>
</html>