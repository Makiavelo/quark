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