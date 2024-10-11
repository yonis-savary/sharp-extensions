# RemindMe

This extension allows you to use the typical 'Remember Me' features when a user logs in

## Usage

Make sure your login form contains one checkbox that the user can check to enable the feature

```html
<label class="flex-row align-center">
    <input type="checkbox" name="remember-me">
    Remember me
</label>
```

Then when the user is logged, you can use the `RemindMe` class to remember its informations

```php
if ($request->params("remember-me") === "on")
    RemindMe::getInstance()->rememberLoggedUser();
```

**Note: the cookie is set to match an IP, you cannot use the cookie to connect from another IP**

Then, put the `RememberUser` middleware before any middleware that is responsible to check for logged-in user

```php
$router->addGroup(
    ["middlewares" => [RememberUser::class, YourLoggedMiddleware::class]],
    Route::get(...)
);
```

## Configuration

You can edit the cookie name and its duration (in seconds)

```json
{
    "cookie-name" => "sharp_extensions_remind_me",
    "cookie-duration" => 3600*31,
    "same-ip-required" => false
}
```

The `same-ip-required` key means that for the cookie to be valid it must be used with the same IP used for registration