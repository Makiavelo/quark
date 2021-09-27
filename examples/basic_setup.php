<?php

require '../vendor/autoload.php';

use Makiavelo\Quark\Quark;
use Makiavelo\Quark\Request;
use Makiavelo\Quark\Response;

$app = Quark::app();

$app->get('/', function(Request $req, Response $res) {
    $res->status(200)->send('Index page');
});

$app->get('/provider/:id', function(Request $req, Response $res) {
    $res->status(200)
        ->body('Provider get: ' . $req->param('id', 'N/A'))
        ->send();
});

$app->post('/provider/:id', function(Request $req, Response $res) {
    $res->status(200)->body('Provider edit: ' . $req->param('id', 'N/A'))->send();
});

$app->start();
