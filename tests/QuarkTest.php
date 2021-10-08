<?php

use PHPUnit\Framework\TestCase;

use \Mockery as m;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Makiavelo\Quark\Quark;
use Makiavelo\Quark\Route;
use Makiavelo\Quark\Router;

final class QuarkTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    protected function tearDown() : void {
        \Mockery::close();
        Quark::resetInstance();
    }

    public function testInstance()
    {
        $instance = Quark::app();
        $this->assertEquals(get_class($instance), 'Makiavelo\\Quark\\Quark');
    }

    public function testAddRoute()
    {
        Quark::app()->resetInstance();
        $this->assertEquals(count(Quark::app()->routes), 0);
        
        $route = new Route([
            'path' => '/test_add',
            'method' => 'GET'
        ]);

        Quark::app()->addRoute($route);
        $this->assertEquals(count(Quark::app()->routes), 1);

        Quark::app()->addRoute($route);
        $this->assertEquals(count(Quark::app()->routes), 2);
    }

    public function testApplyRoute()
    {
        $_SERVER['PATH_INFO'] = '/test';
        $app = Quark::app();
        
        $spy = m::spy('callback');

        $route = new Route([
            'path' => '/test',
            'method' => 'GET',
            'callback' => [$spy, 'exec']
        ]);

        $app->applyRoute($route);

        $spy->shouldHaveReceived('exec');
    }

    public function testUse()
    {
        Quark::app()->resetInstance();

        $this->assertEquals(count(Quark::app()->routes), 0);

        $spy = m::spy('callback');
        Quark::app()->use('/some/path', $spy);

        $this->assertEquals(count(Quark::app()->routes), 1);
        $this->assertEquals(Quark::app()->routes[0]->method, 'ALL');
        $this->assertEquals(get_class(Quark::app()->routes[0]), 'Makiavelo\\Quark\\Route');

        Quark::app()->use('/other/path', $spy);

        $this->assertEquals(count(Quark::app()->routes), 2);
        $this->assertEquals(Quark::app()->routes[1]->method, 'ALL');
        $this->assertEquals(get_class(Quark::app()->routes[1]), 'Makiavelo\\Quark\\Route');
    }

    public function testGet()
    {
        Quark::app()->resetInstance();

        $spy = m::spy('callback');
        Quark::app()->get('/some/get/path', $spy);

        $this->assertEquals(Quark::app()->routes[0]->method, 'GET');
    }

    public function testPost()
    {
        Quark::app()->resetInstance();

        $spy = m::spy('callback');
        Quark::app()->post('/some/post/path', $spy);

        $this->assertEquals(Quark::app()->routes[0]->method, 'POST');
    }

    public function testPut()
    {
        Quark::app()->resetInstance();

        $spy = m::spy('callback');
        Quark::app()->put('/some/put/path', $spy);

        $this->assertEquals(Quark::app()->routes[0]->method, 'PUT');
    }

    public function testDelete()
    {
        Quark::app()->resetInstance();

        $spy = m::spy('callback');
        Quark::app()->delete('/some/post/path', $spy);

        $this->assertEquals(Quark::app()->routes[0]->method, 'DELETE');
    }

    public function testPatch()
    {
        Quark::app()->resetInstance();

        $spy = m::spy('callback');
        Quark::app()->patch('/some/post/path', $spy);

        $this->assertEquals(Quark::app()->routes[0]->method, 'PATCH');
    }

    public function testRouter()
    {
        Quark::app()->resetInstance();
        $app = Quark::app();

        $router = new Router('/user');

        $router->add(new Route([
            'path' => '/view',
            'method' => 'GET'
        ]));

        $router->add(new Route([
            'path' => '/edit',
            'method' => 'POST'
        ]));

        $app->addRouter($router);
        $this->assertCount(2, $app->routes);
    }
}
