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