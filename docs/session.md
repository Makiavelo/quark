## Session
We just provider a simple wrapper around sessions. There's no requirement for this class to be used, you can extend it if it helps, use any other one you want, or just access the $_SESSION calling session_start yourself.

Examples:
```php
use Makiavelo\Flex\Session;

$session = Session::get(); // This is a singleton

$session->start(); // Just calls session_start()
$session->destroy() // Just calls session_destroy();
$session->method($name, $params) // Call any session_{$name} function
                                 // with params (if any). $params is optional.

// Example of 'method'
$session->method('create_id', ['preffix']); // session_create_id('preffix')

// Get a parameter by path in $_SESSION, default if not found.
$session->param('path->to->variable', 'Default value');

// Set a value in $_SESSION using paths
// This case sets the first tag of a user
$session->set('user->tags->0', 'Some tag');
```

### Starting a session on every request
Example adding a middleware to start a session on every request:
```php
include('../vendor/autoload.php');

use Makiavelo\Quark\Quark;
use Makiavelo\Quark\Request;
use Makiavelo\Quark\Response;

$app = Quark::app();

$app->all('/.*', function(Request $req, Response $res) {
    Session::get()->start();
});

$app->start();
```