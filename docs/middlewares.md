## Middlewares
The framework is based on middlewares. Any middleware can be added to the list, but all of them are tied to a route.

The basic usage for a route is:
```php
$app->get('/route/to/use', $callable);
```

There is an alias for each of the common HTTP methods:
```php
$app->get('/route/to/use', $callable);
$app->post('/route/to/use', $callable);
$app->put('/route/to/use', $callable);
$app->delete('/route/to/use', $callable);
$app->patch('/route/to/use', $callable);
$app->all('/route/to/use', $callable); // Matches any HTTP method on that route
```

Middlewares can be anything, and can stop the chain of middlewares at any point. 
As soon as a route is matched, it's executed and passes the request to the next matching route (unless stopped).

There are two types of middlewares, 'renderers' and 'non-renderers'. Renderers are the ones that output content to the client, and only one of these can be executed in the chain. Non-renderers have no limits and can be added at any point of the chain. Non-renderers are added using the 'use' method.

Example
```php
$app->use('/backend/.*', function(Request $req, Response $res) {
    if ($req->param('token') !== '12345') {
        $res->redirect('/login');
    };
});

$app->use('/api/.*', function(Request $req, Response $res) {
    if ($req->param('api_token') !== '12345') {
        $res->status(401)->send('Unauthorized');
        return false; // Returning false stops the chain
    };
});

$app->get('/', function(Request $req, Response $res) {
    $res->status(200)->send('Site home');
});

$app->get('/backend/dashboard', function(Request $req, Response $res) {
    $res->status(200)->send('Secured dashboard');
});

$app->post('/api/user/add', function(Request $req, Response $res) {
    $res->status(200)->send('Secured api method');
});

$app->all('/.*', function(Request $req, Response $res) {
    $res->status(404)->send('This is a 404 page...');
});

$app->start();
```

In this case we are adding two non-rendering middlewares at the top, which will secure everything under '/backend' and '/api', stopping the chain in case of failure (the first case just a redirect, which internally stops the chain).

The '/' route will always be accessible since it doesn't belong to any of the protected paths.

The last middleware will is a 'catch-all' route which will only be executed if none of the previous renderer middlewares were executed, and it's ideal for handling 404's.