# Quark
Minimalistic framework to handle routes via middlewares.
The whole framework consists of 4 core files and two helper libraries, so It's truly minimalistic.
Source files are under 200 lines each (comments included).

## Full documentation
Full documentation, examples and tutorials here: https://makiaveloquark.readthedocs.io

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