# Quark
Minimalistic framework to handle routes via middlewares.
The whole framework consists of 4 core files and two helper libraries, so It's truly minimalistic.
Source files are under 200 lines each (comments included).

## Install (with composer)
```
composer require makiavelo/quark
```
Or update dependencies in composer.json
```json
"require": {
    "makiavelo/quark": "dev-master"
}
```

## Install with single file
The repository contains a phar file which can be included directly to avoid using composer.
The phar can be found here: `/phar/quark.phar`

## Setup
Create a .htaccess file in your document root and make sure the server has rewrites enabled (apache, nginx, etc.)
```
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php [QSA,L]
```

## Usage
Create an 'index.php' file in your document root.
```php
<?php

include('../vendor/autoload.php');

use Makiavelo\Quark\Quark;
use Makiavelo\Quark\Request;
use Makiavelo\Quark\Response;

$app = Quark::app();

$app->get('/', function(Request $req, Response $res) {
    $res->status(200)->send('Yay! quark installed!');
});

$app->start();
```

Or using the single file installation:
```php
<?php

require_once "../quark.phar";

use Makiavelo\Quark\Quark;

$app = Quark::app();

$app->get('/test', function($req, $res) {
    $res->status(200)->send('Testing phars!');
});

$app->start();
```

The framework leaves open to the developer how to handle routes, controllers can be created if required.
If the app is just a simple REST api then they can be totally avoided.
When I say controllers, it's just for the MVC term, since there's no requiremente about how they can be implemented.

### Example 1
The most basic usage would be somehting like this
```php
include('../vendor/autoload.php');

use Makiavelo\Quark\Quark;

$app->get('/user/list', function($req, $res) {
    include('some_template.php');
    // or an echo, or whatever is needed.
});

$app->start();
```

### Example 2
Using a controller object method as a callable

index.php
```php
<?php

include('../vendor/autoload.php');

use Makiavelo\Quark\Quark;
use Makiavelo\Quark\Request;
use Makiavelo\Quark\Response;

use TestProject\Controllers\User as UserController;
$userController = new UserController();

$app->get('/user/list', [$userController, 'list']);

$app->start();
```

User.php
```php
<?php

namespace TestProject\Controllers;

use Makiavelo\Quark\Request;
use Makiavelo\Quark\Response;

class User
{
    public function list(Request $req, Response $res)
    {
        $res->status(200)->send('Got a list!');
    }
}
```

### Example 3
Using the controller inside the closure

index.php
```php
<?php

include('../vendor/autoload.php');

use Makiavelo\Quark\Quark;
use Makiavelo\Quark\Request;
use Makiavelo\Quark\Response;

use TestProject\Controllers\User as UserController;

$app->get('/user/list/alt', function(Request $req, Response $res) {
    // Only instantiate if used
    $userController = new UserController();
    $userController->list($req, $res);
});

$app->start();
```

User.php
```php
<?php

namespace TestProject\Controllers;

use Makiavelo\Quark\Request;
use Makiavelo\Quark\Response;

class User
{
    public function list(Request $req, Response $res)
    {
        $res->status(200)->send('Got a list!');
    }
}
```

### Example 4
Ignoring request and response parameters

index.php
```php
$app->get('/user/:id/get', function(Request $req, Response $res) {
    // Only instantiate if used
    $userController = new UserController();
    $userController->get($req->param('id'));
});
```

User.php
```php
<?php

namespace TestProject\Controllers;

use Makiavelo\Quark\Request;
use Makiavelo\Quark\Response;

class User
{
    public function list($id)
    {
        Response::get()->status(200)->send($id);
    }
}
```

## Middlewares
The framework is based on middlewares. Any middleware can be added to the list, but all of them are tied to a route.

The basic usage for a route is:
```php
$app->use('/route/to/use', $callable, 'METHOD');
```

There is an alias for each of the common HTTP methods:
```php
$app->get('/route/to/use', $callable);
$app->post('/route/to/use', $callable);
$app->put('/route/to/use', $callable);
$app->delete('/route/to/use', $callable);
$app->patch('/route/to/use', $callable);
```

Middlewares can be anything, and can stop the chain of middlewares at any point. 
As soon as a route is matched, it's executed and stops the chain unless specifically designed not to do so. 
So the order of the definitions is important.

## Responses
The response object is a pretty simple wrapper around common output types and header definitions.
The response object can do the output to the browser, but this is not required and it's only a convenience class for this.

Examples:
```php
$res = Response::get(); // This is a singleton

$req->body(); // Get the body of the response
$req->body($content); // Set the body of the response
$req->status(); // Get the http status to be sent
$req->status($status) // Set the http status tu be sent
$req->addHeaders($headers) // Add a set of headers
$req->send($body) // Send the body and headers to the client
```


## Request object
The request object is also a simple wrapper around superglobals and also can handle headers to simplify repetitive tasks.

Examples:
```php
$req = Request::get(); // This is a singleton

$req->path(); // Get the path part of the url, 
              // Example: http://somesite.com/path/to/resource
              // /path/to/resource is the path
$req->method(); // Get the HTTP method user (GET, POST, etc...)
$req->param($name, $default); // Get a parameter from all the available ones.
                              // $_POST, $_GET, $_REQUEST and url parameters
$req->getBody(); // Get the body of the request
$req->query($name, $default) // Get param from $_GET
$req->post($name, $default) // Get param from $_POST
$req->getScheme() // http or https
```

## Views
This is not required in any way, but we provide a simple way to load templates and send variables to it.
The usage is pretty simple:

```php
use Makiavelo\Quark\View;

$view = new View('path/to/file.php');
$view->render($params = [
    'name' => 'John'
]);
```

The template file has no requirements, anything can be used there.
The $params variable should be an associative array, which will be extracted to be used in the template.

```php
<html>
    <head></head>
    <body>
        <h1><?php echo $name; ?></h1>
    </body>
</html>
```

## Documentation
The documentation of the classes can be found in the 'docs' folder. Can be viewed online on github pages here: https://makiavelo.github.io/quark/
