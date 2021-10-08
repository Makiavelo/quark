## Router
A router adds the possibility to group a set of Routes inside a single class. So let's say you have the entity 'user' and want to create CRUD operations for that entity, a router will be something like this:

```php
$app = Quark::app();

$router = new Router('/user');

$router->add(new Route([
    'path' => '/add',
    'method' => 'POST',
    'callback' => 'SomeCallable'
]));

$router->add(new Route([
    'path' => '/view',
    'method' => 'GET',
    'callback' => 'SomeCallable'
]));

$router->add(new Route([
    'path' => '/edit',
    'method' => 'POST',
    'callback' => 'SomeCallable'
]));

$router->add(new Route([
    'path' => '/delete',
    'method' => 'DELETE',
    'callback' => 'SomeCallable'
]));

$app->addRouter($router);

print_r($app); // the 'routes' collection will include all the routes with the 
               // '/user' preffix.
```

The idea behind this is to be able to move functionality to other classes and have a cleaner 'index.php' file.

So let's move the router to a class and then use it:

```php
use Makiavelo\Quark\Router;

class UserRouter extends Router
{
    // This method is called in the parent constructor
    public function init()
    {
        $this->add(new Route([
            'path' => '/add',
            'method' => 'POST',
            'callback' => 'SomeCallable'
        ]));

        $this->add(new Route([
            'path' => '/view',
            'method' => 'GET',
            'callback' => 'SomeCallable'
        ]));

        $this->add(new Route([
            'path' => '/edit',
            'method' => 'POST',
            'callback' => 'SomeCallable'
        ]));

        $this->add(new Route([
            'path' => '/delete',
            'method' => 'DELETE',
            'callback' => 'SomeCallable'
        ]));
    }
}
```

And now change our index.php file to use the router

```php
use Your\Namespace\UserRouter;

$app = Quark::app();

$app->addRouter(new UserRouter('/user')); // The base path

$app->start();
```