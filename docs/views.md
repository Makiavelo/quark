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

### Using layouts
There's no special functionality for layouts, but they can be created like this:

```php
$layout = new View('/path/to/layout.php');
$content = $layout->fetch([
    'content' => new View('/path/to/view.php', ['users' => $users])
]);

$res->status(200)->send($content);
```

We are creating a View object, which has a 'content' variable, which also is a view.

layout.php
```php
<html>
    <body>
        <?php echo $content; ?>
    </body>
</html>
```

So we just 'echo' the content, which will load the 'view.php' file with the list of users.

### Using a template engine
There's no limitation on using any engine, the 'send' method of the response only requires a string, so as long as a string is provided, we're good to go.

```php
$m = new Mustache_Engine;
$content = $m->render('Hello, {{planet}}!', array('planet' => 'World')); // "Hello, World!"
$res->status(200)->send($content);
```