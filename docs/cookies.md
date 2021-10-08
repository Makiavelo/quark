## Cookies
We just provider a simple wrapper around cookies. There's no requirement for this class to be used, you can extend it if it helps, use any other one you want, or just access the $_COOKIE array and add cookies using 'setcookie'.

Examples:
```php
use Makiavelo\Flex\Util\Cookies;

$cookies = Session::get(); // This is a singleton

// Get a parameter by path in $_COOKIE, default if not found.
$cookies->get('path->to->variable', 'Default value');

// Send a cookie
$cookies->send($params);

// Example params
// Taken from: https://www.php.net/manual/en/function.setcookie.php
$params = [
    'name' => "",         // The name of the cookie.
    'value' => "",        // The value of the cookie
    'expires' => 0,       // The time the cookie expires. This is a Unix timestamp so is in number of seconds since the epoch.
    'path' => "",         // The path on the server in which the cookie will be available on
    'domain' => "",       // The (sub)domain that the cookie is available to.
    'secure' => false,    // Only set if it's https connection
    'httponly' => false,  // Only set cookie on HTTP protocol
    'raw' => false        // setrawcookie() will be used instead of setcookie()
];
```

### Setting/reading cookies through middleware
Example adding a middleware to start a session on every request:
```php
include('../vendor/autoload.php');

use Makiavelo\Quark\Quark;
use Makiavelo\Quark\Request;
use Makiavelo\Quark\Response;

$app = Quark::app();

$app->all('/.*', function(Request $req, Response $res) {
    $cookies = Cookies::get();
    $cookies->send([
        'name' => 'welcome',
        'value' => 'Hello!',
        'expires' => time()+60*60*24*30, // 30 days
        'path' => "/", // Available for all routes
        'domain' => "domain.com",
    ]);
});

$app->get('/welcomed', function(Request $req, Response $res) {
    // Get cookie value for 'welcome', with a default fallback
    $welcomeText = Cookie::get()->param('welcome', 'No welcome...');

    // Output to browser
    $res->send(200)->body($welcomeText)->send();
});

$app->start();
```