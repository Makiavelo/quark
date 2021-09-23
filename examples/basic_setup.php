<?php

require '../vendor/autoload.php';

use Makiavelo\Quark\Quark;
use Makiavelo\Quark\Request;
use Makiavelo\Quark\Response;

$app = Quark::app();

$app->use('/admin', function($req, $res) {
    // Auth here...
});

$app->get('/', function(Request $req, Response $res) {
    $res->status(200)->body('Provider index')->send();
});

$app->get('/provider/:id', function(Request $req, Response $res) {
    $res->status(200)
        ->body('Provider get: ' . $req->param('id', 'N/A'))
        ->send();
});

$app->post('/provider/:id', function(Request $req, Response $res) {
    $res->status(200)->body('Provider edit: ' . $req->param('id', 'N/A'))->send();
});

$app->use('/api', function (Request $req, Response $res) {
    // API OAuth 2.0
});

$app->start();
