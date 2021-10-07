## Responses
The response object is a pretty simple wrapper around common output types and header definitions.
The response object can do the output to the browser, but this is not required and it's only a convenience class for this.

Examples:
```php
$res = Response::get(); // This is a singleton

$res->body(); // Get the body of the response
$res->body($content); // Set the body of the response
$res->status(); // Get the http status to be sent
$res->status($status) // Set the http status tu be sent
$res->addHeaders($headers) // Add a set of headers
$res->sendHeaders() // Send all the previously set headers
$res->send($body) // Send the body and headers to the client
$res->redirect($url) // Redirect to any url, relative or absolute, and stop the chain.
```