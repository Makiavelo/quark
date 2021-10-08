<?php

use PHPUnit\Framework\TestCase;

use \Mockery as m;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Makiavelo\Quark\Quark;
use Makiavelo\Quark\Route;
use Makiavelo\Quark\Router;

final class RouterTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    protected function tearDown() : void {
        \Mockery::close();
        Quark::resetInstance();
    }

    public function testInstance()
    {
        $router = new Router('/test');
        $this->assertEquals('/test', $router->basePath);

        $router->add(new Route([
            'path' => '/add',
            'method' => 'GET'
        ]));

        $this->assertCount(1, $router->routes());
        $this->assertEquals('/test/add', $router->routes()[0]->path);
    }

    public function testEmptyBase()
    {
        $router = new Router();
        $this->assertEquals('', $router->basePath);

        $router->add(new Route([
            'path' => '/user/add',
            'method' => 'POST'
        ]));

        $this->assertEquals('/user/add', $router->routes()[0]->path);
    }

    public function testMultiple()
    {
        $router = new Router('/user');
        $this->assertEquals('/user', $router->basePath);

        $router->add(new Route([
            'path' => '/view',
            'method' => 'GET'
        ]));

        $router->add(new Route([
            'path' => '/add',
            'method' => 'POST'
        ]));

        $router->add(new Route([
            'path' => '/edit',
            'method' => 'PATCH'
        ]));

        $router->add(new Route([
            'path' => '/delete',
            'method' => 'DELETE'
        ]));

        $this->assertCount(4, $router->routes());
        $this->assertEquals('/user/view', $router->routes()[0]->path);
        $this->assertEquals('/user/add', $router->routes()[1]->path);
        $this->assertEquals('/user/edit', $router->routes()[2]->path);
        $this->assertEquals('/user/delete', $router->routes()[3]->path);

        $router->remove('/view');
        $this->assertCount(3, $router->routes());
        $this->assertEquals('/user/add', $router->routes()[1]->path);
        $this->assertEquals('/user/edit', $router->routes()[2]->path);
        $this->assertEquals('/user/delete', $router->routes()[3]->path);

        $router->clear();
        $this->assertCount(0, $router->routes());
    }
}
