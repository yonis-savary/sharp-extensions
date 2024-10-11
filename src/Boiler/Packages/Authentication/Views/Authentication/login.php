<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <form action="/login" method="post">
        <label class="form-section">
            <span>Login</span>
            <input type="text" name="login">
        </label>
        <label class="form-section">
            <span>Password</span>
            <input type="password" name="password">
        </label>
        <span class="info red hide-empty"><?= $error ?? "" ?></span>
        <input type="submit" value="Connect" class="button green">
    </form>
</body>
</html>