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