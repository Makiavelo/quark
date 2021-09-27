<?php

include('../vendor/autoload.php');

use Makiavelo\Quark\Quark;
use Makiavelo\Quark\Request;
use Makiavelo\Quark\Response;
use Makiavelo\Quark\View;
use Mak\TestProject\Security\Auth;

use Mak\TestProject\Controllers\User as UserController;
$userController = new UserController();

$app = Quark::app();
$auth = new Auth();

$app->use('/backend/.*', [$auth, 'backend']);
$app->use('/api/.*', [$auth, 'api']);

$app->get('/', function(Request $req, Response $res) {
    $res->status(200)->send('Yay! quark installed!');
});

$app->get('/test', function(Request $req, Response $res) {
    $res->status(200)->send('testing...');
});

$app->post('/create', function(Request $req, Response $res) {
    // Execute your inserts here...
    $res->status(200)->send('model created!');
});

$app->get('/test_template', function(Request $req, Response $res) {
    $view = new View('views/test_template.php');
    $res->status(200)->send($view->fetch([
        'name' => 'John'
    ]));
});

$app->get('/json', function(Request $req, Response $res) {
    $res->status(200)->json(['some' => 'value', 'other' => ['nested' => 'value']]);
});

$app->get('/user/list', [$userController, 'list']);

$app->get('/user/list/alt', function(Request $req, Response $res) {
    // Only instantiate if used
    $userController = new UserController();
    $userController->list($req, $res);
});

$app->get('/user/@id/get', function(Request $req, Response $res) {
    // Only instantiate if used
    $userController = new UserController();
    $userController->get($req->param('id'));
});

$app->all('/.*', function(Request $req, Response $res) {
    $res->status(404)->send('This is a 404 page...');
});

$app->start();